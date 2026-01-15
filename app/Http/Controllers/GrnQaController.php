<?php

namespace App\Http\Controllers;

use App\Models\Subitem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sales_Order;
use App\Models\Production\QaTest;
use App\Models\Production\QcGrn;
use App\Models\Production\QcGrnData;
use App\Models\Production\QcValue;
use App\Models\Production\QcValueData;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class GrnQaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('mysql2')->table('qc_grns as qg')
                ->join('goods_receipt_note as grn', 'grn.id', '=', 'qg.grn_id')
                ->select('qg.id', 'qg.qc_by', 'qg.qc_grn_date', 'grn.grn_no', 'grn.supplier_id', 'qg.id as qg_id')
                ->where([
                    ['qg.status', 1],
                    ['grn.status', 1]
                ])->orderBy('qg.id', 'desc')->get();

            return view('purchase.QaGRN.ajax.listQaGrnAjax', compact('data'));
        }

        return view('purchase.QaGRN.listQaGrn');
    }

    public function create()
    {
        $QaTest = QaTest::where('status', 1)->get();
        
        // Get GRNs that are ready for QC
        $grns = DB::Connection('mysql2')
            ->table('goods_receipt_note as grn')
            ->leftJoin('supplier as s', 's.id', '=', 'grn.supplier_id')
            ->select('grn.id', 'grn.grn_no', 'grn.grn_date', 'grn.po_no', 'grn.supplier_id', 's.name as supplier_name')
            ->where('grn.status', 1)
            ->where('grn.grn_status', 2)
            ->orderBy('grn.id', 'desc')
            ->get();

        return view('purchase.QaGRN.grnCreateQaGrn', compact('grns', 'QaTest'));
    }

    public function store(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        
        // Debug: Log all request values (uncomment for testing)
        // \Log::info('QC GRN Store Request:', $request->all());
        // dd($request->all());
        
        try {
            // Get grn_data_id - QC is done for ONE item at a time
            $grn_data_id = $request->input('grn_data_id');
            if (!$grn_data_id) {
                throw new \Exception('GRN Data ID is required');
            }
            
            // Get actual_qty and tested_qty from form (for this specific item)
            $actual_qty = floatval($request->input('actual_qty') ?? 0);
            $tested_qty = floatval($request->input('tested_qty') ?? 0);
            $return_qty = $actual_qty - $tested_qty;
            
            // Get GRN details
            $grn = DB::Connection('mysql2')->table('goods_receipt_note')
                ->where('id', $request->grn_id)
                ->first();
            
            if (!$grn) {
                throw new \Exception('GRN not found');
            }
            
            // Get the SPECIFIC GRN data item (only one item is being QC'd)
            $grn_item = DB::Connection('mysql2')->table('grn_data')
                ->where('id', $grn_data_id)
                ->where('master_id', $request->grn_id)
                ->where('status', 1)
                ->first();
            
            if (!$grn_item) {
                throw new \Exception('GRN Data Item not found');
            }
            
            // Create QC GRN record
            $QcGrn = new QcGrn;
            $QcGrn->new_pv_id = $request->new_pv_id ?? 0;
            $QcGrn->pr_id = 0; // pr_id not available in goods_receipt_note, using po_no instead
            $QcGrn->grn_id = $request->grn_id;
            $QcGrn->purchase_return_id = null; // Will be set later if purchase return is created
            $QcGrn->supplier_id = $request->supplier_id ?? 0;
            $QcGrn->qc_grn_date = $request->qc_grn_date;
            $QcGrn->qc_by = $request->qc_by;
            $QcGrn->status = 1;
            $QcGrn->username = Auth::user()->name;
            $QcGrn->save();
            $id = $QcGrn->id;

            $hasFailedTest = false;
            
            // Process QC test data
            if ($request->qa_test_id) {
                foreach ($request->qa_test_id as $key => $value) {
                    if ($request->input('checkBox' . $value) == 1) {
                        $standard_value = $request->input('standard_value' . $value) ?? '';
                        $test_value = $request->input('test_value' . $value) ?? '';

                        $qc_grn_data = new QcGrnData;
                        $qc_grn_data->qc_grn_id = $id;
                        $qc_grn_data->qa_test_id = $value;
                        $qc_grn_data->standard_value = $standard_value;
                        $qc_grn_data->test_value = $test_value;
                        $qc_grn_data->test_status = $request->input('test_status' . $value) ?? '';
                        $qc_grn_data->test_type = $request->input('test_type' . $value) ?? '';
                        $qc_grn_data->remarks = $request->input('remarks' . $value) ?? '';
                        $qc_grn_data->status = 1;
                        $qc_grn_data->username = Auth::user()->name;
                        $qc_grn_data->save();
                        
                        // Check if test failed (test_status is "not ok")
                        $test_status = strtolower($request->input('test_status' . $value) ?? '');
                        if ($test_status == 'not ok') {
                            $hasFailedTest = true;
                        }
                    }
                }
            }
            
            // Check if quantity failed (tested_qty < actual_qty)
            if ($return_qty > 0) {
                $hasFailedTest = true;
            }
            
            $purchase_return_id = null;
            
            // If any test failed or return_qty > 0, create purchase return for THIS SPECIFIC ITEM
            if ($hasFailedTest && $return_qty > 0) {
                // Generate purchase return number
                $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`pr_no`,3,length(substr(`pr_no`,3))-4),signed integer)) reg from `purchase_return` where substr(`pr_no`,-4,2) = " . date('m') . " and substr(`pr_no`,-2,2) = " . date('y') . "");
                $purchaseReturnNo = 'dr' . (($str->reg ?? 0) + 1) . date('my');
                
                // Check if purchase voucher exists for this GRN
                $count_invoice = DB::Connection('mysql2')->table('new_purchase_voucher')
                    ->where('grn_id', $request->grn_id)
                    ->count();
                $type = ($count_invoice > 0) ? 2 : 1;
                
                // Create purchase return record
                $purchaseReturnInsert = [
                    'grn_id' => $request->grn_id,
                    'pr_no' => $purchaseReturnNo,
                    'pr_date' => $request->qc_grn_date,
                    'supplier_id' => $request->supplier_id ?? 0,
                    'grn_no' => $grn->grn_no ?? '',
                    'grn_date' => $grn->grn_date ?? date('Y-m-d'),
                    'remarks' => 'Auto generated due to QC failure: Return quantity = ' . $return_qty . ' for item ID: ' . $grn_data_id,
                    'created_date' => date('Y-m-d'),
                    'status' => 1,
                    'type' => $type,
                    'username' => Auth::user()->name,
                ];
                
                $purchase_return_id = DB::Connection('mysql2')->table('purchase_return')->insertGetId($purchaseReturnInsert);
                
                // Update QcGrn with purchase_return_id
                $QcGrn->purchase_return_id = $purchase_return_id;
                $QcGrn->save();
                
                // Create purchase return data for THIS SPECIFIC ITEM ONLY
                $received_qty = floatval($grn_item->purchase_recived_qty ?? 0);
                $rate = floatval($grn_item->rate ?? 0);
                $amount = $return_qty * $rate;
                $discount_percent = floatval($grn_item->discount_percent ?? 0);
                $discount_amount = ($amount / 100) * $discount_percent;
                $net_amount = $amount - $discount_amount;
                
                $purchaseReturnData = [
                    'master_id' => $purchase_return_id,
                    'pr_no' => $purchaseReturnNo,
                    'grn_data_id' => $grn_item->id,
                    'sub_item_id' => $grn_item->sub_item_id ?? 0,
                    'description' => $grn_item->description ?? '',
                    'warehouse_id' => $grn_item->warehouse_id ?? 0,
                    'batch_code' => $grn_item->batch_code ?? '',
                    'recived_qty' => $tested_qty,
                    'rate' => $rate,
                    'amount' => $amount,
                    'discount_percent' => $discount_percent,
                    'discount_amount' => $discount_amount,
                    'net_amount' => $net_amount,
                    'return_qty' => $return_qty,
                    'status' => 1,
                ];
                
                DB::Connection('mysql2')->table('purchase_return_data')->insert($purchaseReturnData);
            }
            
            // Always create stock transactions and purchase voucher for accepted items (THIS SPECIFIC ITEM ONLY)
            $goods_rece = DB::Connection('mysql2')->table('goods_receipt_note')
                ->where('id', $request->grn_id)
                ->first();
            
            if ($goods_rece) {
                // Delete previous stock entries for THIS SPECIFIC GRN ITEM
                DB::Connection('mysql2')->table('stock')
                    ->where('main_id', $request->grn_id)
                    ->where('master_id', $grn_data_id)
                    ->where('voucher_type', 1)
                    ->where('status', 1)
                    ->update(['status' => 0]);
                
                // Check if there's an existing purchase voucher for this GRN
                $existing_pv = DB::Connection('mysql2')->table('new_purchase_voucher')
                    ->where('grn_id', $request->grn_id)
                    ->where('status', 1)
                    ->first();
                
                if ($existing_pv) {
                    // Delete previous transactions related to this purchase voucher for THIS ITEM
                    DB::Connection('mysql2')->table('transactions')
                        ->where('voucher_no', $existing_pv->pv_no)
                        ->where('voucher_type', 4)
                        ->where('status', 1)
                        ->update(['status' => 0]);
                    
                    // Delete previous purchase voucher data for THIS ITEM
                    DB::Connection('mysql2')->table('new_purchase_voucher_data')
                        ->where('master_id', $existing_pv->id)
                        ->where('grn_data_id', $grn_data_id)
                        ->delete();
                    
                    // Only delete purchase voucher if no items remain
                    $remaining_items = DB::Connection('mysql2')->table('new_purchase_voucher_data')
                        ->where('master_id', $existing_pv->id)
                        ->count();
                    
                    if ($remaining_items == 0) {
                        DB::Connection('mysql2')->table('new_purchase_voucher')
                            ->where('id', $existing_pv->id)
                            ->update(['status' => 0]);
                    }
                }
                
                // Get supplier account ID
                $supp_acc_id = CommonHelper::get_supplier_acc_id($request->supplier_id ?? 0);
                
                // Get terms of payment
                $terms_of_payment = DB::Connection('mysql2')->table('supplier')
                    ->where('id', $request->supplier_id ?? 0)
                    ->select('terms_of_payment')
                    ->value('terms_of_payment');
                $due_date = date('Y-m-d', strtotime($request->qc_grn_date . ' + ' . ($terms_of_payment ?? 0) . ' days'));
                
                // Check if purchase voucher already exists for this GRN (might have other items)
                $existing_pv = DB::Connection('mysql2')->table('new_purchase_voucher')
                    ->where('grn_id', $request->grn_id)
                    ->where('status', 1)
                    ->first();
                
                if ($existing_pv) {
                    $master_id = $existing_pv->id;
                    $pv_no = $existing_pv->pv_no;
                } else {
                    // Generate purchase voucher number
                    $pv_no = CommonHelper::uniqe_no_for_purcahseVoucher(date('y'), date('m'));
                    
                    // Create purchase voucher
                    $data1 = [
                        'pv_no' => $pv_no,
                        'pv_date' => $request->qc_grn_date,
                        'grn_no' => $goods_rece->grn_no ?? '',
                        'grn_id' => $request->grn_id,
                        'slip_no' => $goods_rece->supplier_invoice_no ?? '',
                        'bill_date' => $goods_rece->grn_date ?? $request->qc_grn_date,
                        'due_date' => $due_date,
                        'supplier' => $request->supplier_id ?? 0,
                        'description' => ($goods_rece->po_no ?? '') . '---' . ($goods_rece->po_date ?? ''),
                        'username' => Auth::user()->name,
                        'status' => 1,
                        'pv_status' => 2,
                        'date' => date('Y-m-d'),
                    ];
                    
                    $master_id = DB::Connection('mysql2')->table('new_purchase_voucher')->insertGetId($data1);
                }
                
                // Update QcGrn with new_pv_id
                $QcGrn->new_pv_id = $master_id;
                $QcGrn->save();
                
                // Process THIS SPECIFIC ITEM ONLY
                $item_return_qty = 0;
                
                // Check if this item is in purchase return
                if ($purchase_return_id) {
                    $return_data_item = DB::Connection('mysql2')->table('purchase_return_data')
                        ->where('master_id', $purchase_return_id)
                        ->where('grn_data_id', $grn_data_id)
                        ->where('status', 1)
                        ->first();
                    
                    if ($return_data_item) {
                        $item_return_qty = floatval($return_data_item->return_qty ?? 0);
                    }
                }
                
                $received_qty = floatval($grn_item->purchase_recived_qty ?? 0);
                $accepted_qty = $tested_qty; // Use tested_qty as accepted quantity
                
                // Only process if there's accepted quantity
                if ($accepted_qty > 0) {
                    // Calculate amounts based on accepted quantity
                    $unit_rate = floatval($grn_item->rate ?? 0);
                    $proportional_amount = $accepted_qty * $unit_rate;
                    $discount_percent = floatval($grn_item->discount_percent ?? 0);
                    $proportional_discount_amount = ($proportional_amount / 100) * $discount_percent;
                    $proportional_net_amount = $proportional_amount - $proportional_discount_amount;
                    
                    // Check if purchase voucher data already exists for this item
                    $existing_pv_data = DB::Connection('mysql2')->table('new_purchase_voucher_data')
                        ->where('master_id', $master_id)
                        ->where('grn_data_id', $grn_data_id)
                        ->first();
                    
                    if ($existing_pv_data) {
                        // Update existing purchase voucher data
                        DB::Connection('mysql2')->table('new_purchase_voucher_data')
                            ->where('id', $existing_pv_data->id)
                            ->update([
                                'qty' => $accepted_qty,
                                'rate' => $unit_rate,
                                'amount' => $proportional_amount,
                                'discount_amount' => $proportional_discount_amount,
                                'net_amount' => $proportional_net_amount,
                                'username' => Auth::user()->name,
                                'date' => date('Y-m-d'),
                            ]);
                        $pv_data_id = $existing_pv_data->id;
                    } else {
                        // Create purchase voucher data
                        $data2 = [
                            'master_id' => $master_id,
                            'pv_no' => $pv_no,
                            'slip_no' => '',
                            'grn_data_id' => $grn_item->id,
                            'category_id' => 97,
                            'sub_item' => $grn_item->sub_item_id ?? 0,
                            'uom' => 0,
                            'qty' => $accepted_qty,
                            'rate' => $unit_rate,
                            'amount' => $proportional_amount,
                            'discount_amount' => $proportional_discount_amount,
                            'net_amount' => $proportional_net_amount,
                            'staus' => 1,
                            'pv_status' => 2,
                            'username' => Auth::user()->name,
                            'date' => date('Y-m-d'),
                            'additional_exp' => 0
                        ];
                        
                        $pv_data_id = DB::Connection('mysql2')->table('new_purchase_voucher_data')->insertGetId($data2);
                    }
                    
                    // Create stock entry for THIS ITEM
                    $stock = [
                        'voucher_no' => $goods_rece->grn_no ?? '',
                        'main_id' => $request->grn_id,
                        'master_id' => $grn_item->id,
                        'supplier_id' => $request->supplier_id ?? 0,
                        'voucher_date' => $request->qc_grn_date,
                        'voucher_type' => 1,
                        'sub_item_id' => $grn_item->sub_item_id ?? 0,
                        'qty' => $accepted_qty,
                        'rate' => $unit_rate,
                        'amount_before_discount' => $proportional_amount,
                        'discount_percent' => $discount_percent,
                        'discount_amount' => $proportional_discount_amount,
                        'amount' => $proportional_net_amount,
                        'warehouse_id' => $grn_item->warehouse_id ?? 0,
                        'description' => $grn_item->description ?? '',
                        'batch_code' => $grn_item->batch_code ?? '',
                        'status' => 1,
                        'created_date' => date('Y-m-d'),
                        'username' => Auth::user()->name,
                    ];
                    
                    DB::Connection('mysql2')->table('stock')->insert($stock);
                    
                    // Delete old transactions for this item and create new ones
                    DB::Connection('mysql2')->table('transactions')
                        ->where('master_id', $pv_data_id)
                        ->where('voucher_no', $pv_no)
                        ->where('voucher_type', 4)
                        ->where('status', 1)
                        ->update(['status' => 0]);
                    
                    // Create transaction for this item (debit side)
                    $data4 = [
                        'master_id' => $pv_data_id,
                        'acc_id' => 97,
                        'acc_code' => FinanceHelper::getAccountCodeByAccId(97),
                        'paid_to' => 0,
                        'paid_to_type' => 0,
                        'particulars' => ($goods_rece->po_no ?? '') . '---' . ($goods_rece->po_date ?? ''),
                        'opening_bal' => 0,
                        'debit_credit' => 1,
                        'amount' => $proportional_net_amount,
                        'voucher_no' => $pv_no,
                        'voucher_type' => 4,
                        'v_date' => $request->qc_grn_date,
                        'date' => date('Y-m-d'),
                        'action' => 'insert',
                        'username' => Auth::user()->name,
                        'status' => 1
                    ];
                    
                    DB::Connection('mysql2')->table('transactions')->insert($data4);
                }
                
                // Update supplier transaction (credit side) - recalculate total
                // Delete old supplier transaction
                DB::Connection('mysql2')->table('transactions')
                    ->where('master_id', $master_id)
                    ->where('voucher_no', $pv_no)
                    ->where('voucher_type', 4)
                    ->where('acc_id', $supp_acc_id)
                    ->where('status', 1)
                    ->update(['status' => 0]);
                
                // Create supplier transaction (credit side) with updated total
                $total_amount = DB::Connection('mysql2')->table('new_purchase_voucher_data')
                    ->where('master_id', $master_id)
                    ->sum('net_amount');
                
                if ($total_amount > 0) {
                    $data5 = [
                        'master_id' => $master_id,
                        'acc_id' => $supp_acc_id,
                        'acc_code' => FinanceHelper::getAccountCodeByAccId($supp_acc_id),
                        'paid_to' => 0,
                        'paid_to_type' => 0,
                        'particulars' => ($goods_rece->po_no ?? '') . '---' . ($goods_rece->po_date ?? ''),
                        'opening_bal' => 0,
                        'debit_credit' => 0,
                        'amount' => $total_amount,
                        'voucher_no' => $pv_no,
                        'voucher_type' => 4,
                        'v_date' => $request->qc_grn_date,
                        'date' => date('Y-m-d'),
                        'action' => 'insert',
                        'username' => Auth::user()->name,
                        'status' => 1
                    ];
                    
                    DB::Connection('mysql2')->table('transactions')->insert($data5);
                }
            }
            
            // Final save to ensure purchase_return_id is persisted
            if ($purchase_return_id) {
                $QcGrn->refresh();
                $QcGrn->purchase_return_id = $purchase_return_id;
                $QcGrn->save();
            }

            DB::Connection('mysql2')->commit();

            return redirect()->back()->with('dataInsert', 'Successfully Saved');
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            \Log::error('QC GRN Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->back()->withErrors('Error inserting record: ' . $e->getMessage())->withInput();
        }
    }



    public function grnViewQaGrnDetail(Request $request)
    {
        $qc_grn = DB::Connection('mysql2')->table('qc_grns AS qg')
            ->join('goods_receipt_note AS grn', 'grn.id', '=', 'qg.grn_id')
            ->leftJoin('supplier AS s', 's.id', '=', 'qg.supplier_id')
            ->select('qg.*', 'grn.grn_no', 'grn.grn_date', 'grn.po_no', 's.name as supplier_name')
            ->where('qg.id', $request->id)->first();

        $qc_grn_data = DB::Connection('mysql2')->table('qc_grn_datas AS qgd')
            ->join('qa_tests AS qt', 'qt.id', '=', 'qgd.qa_test_id')
            ->where('qgd.qc_grn_id', $request->id)
            ->where('qgd.status', 1)
            ->select('qgd.*', 'qt.name as test_name')
            ->get();

        return view('purchase.QaGRN.grnViewQaGrnDetail', compact('qc_grn', 'qc_grn_data'));
    }

    public function show($id)
    {
        $qc_grn = DB::connection('mysql2')->table('qc_grns as qg')
            ->join('goods_receipt_note as grn', 'qg.grn_id', '=', 'grn.id')
            ->leftJoin('supplier as s', 's.id', '=', 'qg.supplier_id')
            ->where('qg.id', $id)
            ->select('qg.*', 'grn.grn_no', 'grn.grn_date', 'grn.po_no', 's.name as supplier_name')
            ->first();

        $qc_grn_data = QcGrnData::where('qc_grn_id', $id)->where('status', 1)->get();

        return view('purchase.QaGRN.grnViewQaGrn', compact('qc_grn', 'qc_grn_data'));
    }

    public function edit($id)
    {
        $QcGrn = QcGrn::where('id', $id)->where('status', 1)->first();

        if (!$QcGrn) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        $QaTest = QaTest::where('status', 1)->get();
        
        // Get GRNs that are ready for QC
        $grns = DB::Connection('mysql2')
            ->table('goods_receipt_note as grn')
            ->leftJoin('supplier as s', 's.id', '=', 'grn.supplier_id')
            ->select('grn.id', 'grn.grn_no', 'grn.grn_date', 'grn.po_no', 'grn.supplier_id', 's.name as supplier_name')
            ->where('grn.status', 1)
            ->where('grn.grn_status', 2)
            ->orderBy('grn.id', 'desc')
            ->get();

        // Get existing QC GRN data
        $qc_grn_data = QcGrnData::where('qc_grn_id', $id)->where('status', 1)->get();

        return view('purchase.QaGRN.grnUpdateQaGrn', compact('QcGrn', 'grns', 'QaTest', 'qc_grn_data'));
    }

    public function update(Request $request, $id)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {
            $QcGrn = QcGrn::find($id);

            if (!$QcGrn) {
                return redirect()->back()->withErrors('Record not found')->withInput();
            }

            // Update main QC GRN record
            $QcGrn->qc_grn_date = $request->qc_grn_date;
            $QcGrn->qc_by = $request->qc_by;
            $QcGrn->username = Auth::user()->name;
            $QcGrn->save();

            // Delete existing QC GRN data
            QcGrnData::where('qc_grn_id', $id)->update(['status' => 0]);

            // Insert updated QC GRN data
            if ($request->qa_test_id) {
                foreach ($request->qa_test_id as $key => $value) {
                    if ($request->input('checkBox' . $value) == 1) {
                        $qc_grn_data = new QcGrnData;
                        $qc_grn_data->qc_grn_id = $id;
                        $qc_grn_data->qa_test_id = $value;
                        $qc_grn_data->standard_value = $request->input('standard_value' . $value) ?? '';
                        $qc_grn_data->test_value = $request->input('test_value' . $value) ?? '';
                        $qc_grn_data->test_status = $request->input('test_status' . $value) ?? '';
                        $qc_grn_data->test_type = $request->input('test_type' . $value) ?? '';
                        $qc_grn_data->remarks = $request->input('remarks' . $value) ?? '';
                        $qc_grn_data->status = 1;
                        $qc_grn_data->username = Auth::user()->name;
                        $qc_grn_data->save();
                    }
                }
            }

            DB::Connection('mysql2')->commit();

            return redirect('QaGrn/')->with('success', 'Record updated successfully');
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            return redirect()->back()->withErrors('Error updating record. Please try again.')->withInput();
        }
    }

    public function grnDeleteQaPacking(Request $request)
    {
        QcGrn::where('id', $request->qc_grn_id)->update([
            'status' => 0
        ]);
        QcGrnData::where('qc_grn_id', $request->qc_grn_id)->update([
            'status' => 0
        ]);

        return response()->json(['success' => true, 'message' => 'QC GRN deleted successfully']);
    }

    public function productionPlanAndCustomerAgainstSo(Request $request)
    {
        return $this->grnProductionPlanAndCustomerAgainstSo($request);
    }

    public function grnProductionPlanAndCustomerAgainstSo(Request $request)
    {
        // Get GRNs based on purchase request or other criteria
        $grns = DB::Connection('mysql2')->table('goods_receipt_note as grn')
            ->leftJoin('supplier as s', 's.id', '=', 'grn.supplier_id')
            ->select('grn.id', 'grn.grn_no', 'grn.grn_date', 'grn.po_no', 'grn.supplier_id', 's.name as supplier_name')
            ->where('grn.status', 1)
            ->where('grn.grn_status', 1)
            ->get();

        return compact('grns');
    }

    public function getGrnListNo(Request $request)
    {
        return $this->grnGetGrnListNo($request);
    }

    public function grnGetGrnListNo(Request $request)
    {
        $grns = DB::Connection('mysql2')->table('goods_receipt_note as grn')
            ->leftJoin('supplier as s', 's.id', '=', 'grn.supplier_id')
            ->select('grn.id', 'grn.grn_no', 'grn.grn_date', 'grn.po_no', 'grn.supplier_id', 's.name as supplier_name')
            ->where('grn.status', 1)
            ->where('grn.grn_status', 1)
            ->where('grn.po_no', $request->po_no ?? '')
            ->get();

        return compact('grns');
    }

    public function testingOnReceiveItem(Request $request)
    {
        return $this->grnTestingOnReceiveItem($request);
    }

    public function grnTestingOnReceiveItem(Request $request)
    {
        $qc_grn = DB::connection('mysql2')->table('qc_grns as qg')
            ->select('qg.id as qc_grn_id', 'grn.id as grn_id', 'grn.grn_no', 'grn.grn_date', 'qg.qc_grn_date', 'qg.qc_by', 's.name as supplier_name')
            ->join('goods_receipt_note as grn', 'grn.id', '=', 'qg.grn_id')
            ->leftJoin('supplier as s', 's.id', '=', 'qg.supplier_id')
            ->where('qg.status', '=', 1)
            ->where('grn.status', '=', 1)
            ->where('qg.id', '=', $request->id)
            ->first();

        $test_column = DB::connection('mysql2')->table('qc_grn_datas as qgd')
            ->select('qt.id', 'qt.name', 'qgd.remarks')
            ->join('qa_tests as qt', 'qt.id', '=', 'qgd.qa_test_id')
            ->where('qgd.status', '=', 1)
            ->where('qt.status', '=', 1)
            ->where('qgd.qc_grn_id', '=', $request->id)
            ->orderBy('qgd.id')
            ->get();

        // Get GRN items for testing
        $items = DB::connection('mysql2')->table('grn_data as gd')
            ->join('qc_grns as qg', 'qg.grn_id', '=', 'gd.master_id')
            ->join('subitem as s', 's.id', '=', 'gd.sub_item_id')
            ->select('gd.id', 'gd.sub_item_id', 's.sub_ic', 'gd.purchase_recived_qty', 'gd.batch_code')
            ->where('qg.status', '=', 1)
            ->where('gd.status', '=', 1)
            ->where('qg.id', '=', $request->id)
            ->get();

        return view('purchase.QaGRN.grnTestingOnReceiveItem', compact('qc_grn', 'test_column', 'items'));
    }

    public function testResultOnReceiveItem(Request $request)
    {
        return $this->grnTestResultOnReceiveItem($request);
    }

    public function grnTestResultOnReceiveItem(Request $request)
    {
        $qc_grn = DB::connection('mysql2')->table('qc_grns as qg')
            ->select('qg.id as qc_grn_id', 'grn.id as grn_id', 'grn.grn_no', 'grn.grn_date', 'qg.qc_grn_date', 'qg.qc_by', 's.name as supplier_name')
            ->join('goods_receipt_note as grn', 'grn.id', '=', 'qg.grn_id')
            ->leftJoin('supplier as s', 's.id', '=', 'qg.supplier_id')
            ->where('qg.status', '=', 1)
            ->where('grn.status', '=', 1)
            ->where('qg.id', '=', $request->id)
            ->first();

        $test_column = DB::connection('mysql2')->table('qc_grn_datas as qgd')
            ->select('qt.id', 'qt.name', 'qgd.remarks')
            ->join('qa_tests as qt', 'qt.id', '=', 'qgd.qa_test_id')
            ->where('qgd.status', '=', 1)
            ->where('qt.status', '=', 1)
            ->where('qgd.qc_grn_id', '=', $request->id)
            ->orderBy('qgd.id')
            ->get();

        // Get GRN items with test results
        $items = DB::connection('mysql2')->table('grn_data as gd')
            ->join('qc_grns as qg', 'qg.grn_id', '=', 'gd.master_id')
            ->join('subitem as s', 's.id', '=', 'gd.sub_item_id')
            ->select('gd.id', 'gd.sub_item_id', 's.sub_ic', 'gd.purchase_recived_qty', 'gd.batch_code')
            ->where('qg.status', '=', 1)
            ->where('gd.status', '=', 1)
            ->where('qg.id', '=', $request->id)
            ->get();

        return view('purchase.QaGRN.grnTestResultOnReceiveItem', compact('qc_grn', 'test_column', 'items'));
    }

    public function testResultOnReceiveItemAjax(Request $request)
    {
        return $this->grnTestResultOnReceiveItemAjax($request);
    }

    public function grnTestResultOnReceiveItemAjax(Request $request)
    {
        $mainData = DB::connection('mysql2')->table('grn_data AS gd')
            ->join('qc_grns AS qg', 'qg.grn_id', '=', 'gd.master_id')
            ->join('goods_receipt_note AS grn', 'grn.id', '=', 'gd.master_id')
            ->join('subitem AS s', 's.id', '=', 'gd.sub_item_id')
            ->leftJoin('supplier AS sup', 'sup.id', '=', 'grn.supplier_id')
            ->select('grn.grn_no', 'grn.grn_date', 'qg.qc_by', 'qg.qc_grn_date', 'gd.batch_code', 's.sub_ic', 'sup.name as supplier_name')
            ->where('gd.status', 1)
            ->where('qg.status', 1)
            ->where('grn.status', 1)
            ->where('gd.id', $request->id)
            ->first();

        // Get test results for this item
        $test_results = DB::connection('mysql2')->table('qc_grn_datas as qgd')
            ->join('qa_tests as qt', 'qt.id', '=', 'qgd.qa_test_id')
            ->join('qc_grns as qg', 'qg.id', '=', 'qgd.qc_grn_id')
            ->join('grn_data as gd', 'gd.master_id', '=', 'qg.grn_id')
            ->select('qt.name', 'qgd.remarks', 'qgd.test_value', 'qgd.standard_value', 'qgd.test_status', 'qgd.test_type')
            ->where('qgd.status', 1)
            ->where('qt.status', 1)
            ->where('gd.id', $request->id)
            ->orderBy('qgd.test_type')
            ->orderBy('qgd.id')
            ->get();

        // Group tests by type
        $mechanicaltest = $test_results->where('test_type', 'Mechanical')->values();
        $physicaltest = $test_results->where('test_type', 'Physical')->values();
        $chemicaltest = $test_results->where('test_type', 'Chemical')->values();

        return view('purchase.QaGRN.ajax.grnTestResultOnReceiveItemAjax', compact('mainData', 'test_results', 'mechanicaltest', 'physicaltest', 'chemicaltest'));
    }

    public function storeTestResult(Request $request)
    {
        return $this->grnStoreTestResult($request);
    }

    public function grnStoreTestResult(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {
            // Update QC GRN data with test results
            if ($request->grn_data_id && $request->qa_test_id) {
                foreach ($request->grn_data_id as $key => $grn_data_id) {
                    foreach ($request->qa_test_id as $test_id) {
                        $qc_grn_data = QcGrnData::where('qc_grn_id', $request->qc_grn_id)
                            ->where('qa_test_id', $test_id)
                            ->first();
                        
                        if ($qc_grn_data) {
                            $qc_grn_data->remarks = $request->input('test_result_' . $grn_data_id . '_' . $test_id) ?? '';
                            $qc_grn_data->username = Auth::user()->name;
                            $qc_grn_data->save();
                        }
                    }
                }
            }

            DB::Connection('mysql2')->commit();

            return redirect('QaGrn/')->with('success', 'Test results saved successfully');
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            return redirect()->back()->withErrors('Error saving test results. Please try again.')->withInput();
        }
    }

    public function getPackingListNoForDispatch(Request $request)
    {
        // Get GRNs ready for dispatch
        $grns = DB::Connection('mysql2')->table('qc_grns as qg')
            ->join('goods_receipt_note as grn', 'grn.id', '=', 'qg.grn_id')
            ->select('grn.id', 'grn.grn_no', 'grn.grn_date')
            ->where('qg.status', 1)
            ->where('grn.status', 1)
            ->get();

        return compact('grns');
    }

    public function getGrnDataItems(Request $request)
    {
        $grn_id = $request->grn_id ?? 0;
        
        if (!$grn_id) {
            return response()->json(['success' => false, 'message' => 'GRN ID is required']);
        }
        
        // Get GRN data items
        $grn_data_items = DB::Connection('mysql2')->table('grn_data as gd')
            ->leftJoin('subitem as si', 'si.id', '=', 'gd.sub_item_id')
            ->select('gd.id', 'gd.sub_item_id', 'gd.description', 'gd.purchase_recived_qty', 'si.sub_ic as item_name')
            ->where('gd.master_id', $grn_id)
            ->where('gd.status', 1)
            ->get();
        
        return response()->json(['success' => true, 'data' => $grn_data_items]);
    }

    public function grnGetQcValueForm(Request $request)
    {
        // Get GRN ID and GRN Data ID from request
        $grn_id = $request->grn_id ?? $request->id ?? 0;
        $grn_data_id = $request->grn_data_id ?? 0;
        
        // Get specific GRN data item if grn_data_id is provided, otherwise get first item
        if ($grn_data_id > 0) {
            $grn_data = DB::Connection('mysql2')->table('grn_data')
                ->where('id', $grn_data_id)
                ->where('master_id', $grn_id)
                ->where('status', 1)
                ->first();
        } else {
            // Get first item from GRN data to get standard QC values
            $grn_data = DB::Connection('mysql2')->table('grn_data')
                ->where('master_id', $grn_id)
                ->where('status', 1)
                ->first();
        }
        
        $item_id = $grn_data->sub_item_id ?? 0;
        $actual_qty = floatval($grn_data->purchase_recived_qty ?? 0);
        $grn_data_id = $grn_data->id ?? 0;
        
        // Get QC values for this item
        $qc_values = QcValue::with('qcValuesData')
            ->join('qc_values_data', 'qc_values.id', '=', 'qc_values_data.master_id')
            ->join('qa_tests', 'qa_tests.id', '=', 'qc_values_data.test_id')
            ->where('qc_values.item_id', $item_id)
            ->where('qc_values.status', 1)
            ->where('qc_values.qc_type', 2)
            ->where('qc_values_data.status', 1)
            ->select('qa_tests.id AS qa_test_id', 'qa_tests.name', 'qc_values_data.standard_value')
            ->get();
        
        // If editing, get existing QC GRN data
        $existing_qc_data = [];
        $tested_qty = 0;
        if (!empty($request->qc_grn_id)) {
            $existing_qc_records = QcGrnData::where('qc_grn_id', $request->qc_grn_id)
                ->where('status', 1)
                ->get();
            
            foreach ($existing_qc_records as $record) {
                $existing_qc_data[$record->qa_test_id] = [
                    'test_value' => $record->test_value ?? '',
                    'test_status' => $record->test_status ?? '',
                    'test_type' => $record->test_type ?? '',
                    'remarks' => $record->remarks ?? '',
                ];
            }
            
            // Get tested qty from QcGrn if exists
            $qc_grn = QcGrn::where('id', $request->qc_grn_id)->first();
            if ($qc_grn && isset($qc_grn->tested_qty)) {
                $tested_qty = floatval($qc_grn->tested_qty ?? 0);
            }
        }
        
        return view('purchase.QaGRN.grnGetQcValueForm', compact('qc_values', 'existing_qc_data', 'actual_qty', 'tested_qty', 'grn_data_id'));
    }
}
