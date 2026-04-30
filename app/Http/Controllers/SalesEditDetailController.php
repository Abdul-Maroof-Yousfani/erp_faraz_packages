<?php

namespace App\Http\Controllers;
use App\Helpers\ReuseableCode;
use App\Models\Transactions;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Models\CreditNote;
use App\Models\CreditNoteData;
use App\Models\Type;
use App\Models\Conditions;
use App\Models\SurveryBy;
use App\Models\Client;
use App\Models\Branch;
use App\Models\Dispatch;
use App\Models\DispatchData;

use App\Models\ProductType;
use App\Models\ResourceAssigned;
use App\Models\Quotation;
use App\Models\Quotation_Data;
use App\Models\Complaint;
use App\Models\ComplaintProduct;
use App\Models\InvDesc;
use App\Helpers\SalesHelper;
use App\Models\Invoice_totals;

use Input;
use Auth;
use DB;
use Config;
use Redirect;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteData;
use App\Models\SalesTaxInvoice;
use App\Models\SalesTaxInvoiceData;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\Survey;
use App\Models\ClientJob;
use App\Models\ComplaintDocument;

class SalesEditDetailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('auth');

    }

    function updateDeliveryNote(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
            $count = (int) $request->input('count', 0);
            if ($count < 1) {
                throw new \Exception('No items provided in the delivery note.');
            }

            $buyers_id = $request->input('buyers_id');
            $buyers_id = explode('*', $buyers_id)[0] ?? $buyers_id;

            $salesTaxRate = (float) CommonHelper::check_str_replace($request->input('sales_tax_rate', 0));
            $salesTaxAmount = (float) CommonHelper::check_str_replace($request->input('sales_tax', 0));
            $furtherTaxRate = (float) CommonHelper::check_str_replace($request->input('sales_tax_further_per', 0));
            $furtherTaxAmount = (float) CommonHelper::check_str_replace($request->input('sales_tax_further', 0));
            $advanceTaxRate = (float) CommonHelper::check_str_replace($request->input('advance_tax_rate', 0));
            $advanceTaxAmount = (float) CommonHelper::check_str_replace($request->input('advance_tax_amount', 0));
            $cartageAmount = (float) CommonHelper::check_str_replace($request->input('cartage_amount', 0));

            $delivery_note = new DeliveryNote();
            $delivery_note = $delivery_note->SetConnection('mysql2');
            $delivery_note = $delivery_note->find($request->edit_id);
            $delivery_note->master_id = $request->master_id;
            $delivery_note->gd_no = $request->gd_no;
            $delivery_note->gd_date = $request->gd_date;
            $delivery_note->model_terms_of_payment = $request->model_terms_of_payment;
            $delivery_note->so_no = $request->so_no;
            $delivery_note->so_date = $request->so_date;
            $delivery_note->other_refrence = $request->other_refrence;
            $delivery_note->order_no = $request->order_no;
            $delivery_note->order_date = $request->order_date;
            $delivery_note->despacth_document_no = $request->despacth_document_no;
            $delivery_note->despacth_document_date = $request->despacth_document_date;
            $delivery_note->despacth_through = $request->despacth_through ?? '';
            $delivery_note->destination = $request->destination ?? '';
            $delivery_note->terms_of_delivery = $request->terms_of_delivery ?? '';
            $delivery_note->buyers_id = $buyers_id;
            $delivery_note->due_date = $request->due_date;
            $delivery_note->sales_tax_amount = $salesTaxAmount;
            $delivery_note->sales_tax_rate = $salesTaxRate;
            $delivery_note->sales_tax_further_per = $furtherTaxRate;
            $delivery_note->sales_tax_further = $furtherTaxAmount;
            $delivery_note->advance_tax_amount = $advanceTaxAmount;
            $delivery_note->advance_tax_rate = $advanceTaxRate;
            $delivery_note->cartage_amount = $cartageAmount;
            $delivery_note->description = $request->description;
            $delivery_note->status = 1;
            $delivery_note->date = date('Y-m-d');
            $delivery_note->username = Auth::user()->name;
            $delivery_note->save();
            $id = $delivery_note->id;

            DB::Connection('mysql2')->table('delivery_note_data')->where('master_id', $request->edit_id)->delete();
            DB::Connection('mysql2')->table('stock')->where('main_id', $request->edit_id)->where('voucher_no', $request->gd_no)->where('voucher_type', 5)->delete();

            $stock_rows = [];
            $group_counter = 1;
            $total_grand_qty = 0;
            $total_grand_amount = 0;

            for ($i = 1; $i <= $count; $i++) {
                $item_id = $request->input("item_id{$i}");
                if (!$item_id) {
                    continue;
                }

                $qty = (float) CommonHelper::check_str_replace($request->input("send_qty{$i}", 0));
                $send_rate = (float) CommonHelper::check_str_replace($request->input("send_rate{$i}", 0));
                $send_discount = (float) CommonHelper::check_str_replace($request->input("send_discount{$i}", 0));
                $send_amount = $qty * $send_rate;
                if ($send_discount > 0) {
                    $send_amount += ($send_amount * $send_discount) / 100;
                }

                $warehouse_id = $request->input("warehouse{$i}", 1);
                $desc = $request->input("desc{$i}", '');
                $data_id = $request->input("data_id{$i}");
                $bundles_id = $request->input("bundles_id{$i}", 0);

                if ($qty <= 0) {
                    continue;
                }

                $in = DB::Connection('mysql2')->table('stock')->where('status', 1)
                    ->whereIn('voucher_type', [1, 4, 6, 3, 10, 11])
                    ->where('sub_item_id', $item_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->select('stock.*', DB::raw('SUM(qty) As qty'), DB::raw('SUM(amount) As amount'))
                    ->first();

                $out = DB::Connection('mysql2')->table('stock')->where('status', 1)
                    ->whereIn('voucher_type', [2, 5, 9, 8])
                    ->where('sub_item_id', $item_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->select('stock.*', DB::raw('SUM(qty) As qty'), DB::raw('SUM(amount) As amount'))
                    ->first();

                $available_qty = (float) (($in->qty ?? 0) - ($out->qty ?? 0));
                if ($available_qty < $qty) {
                    throw new \Exception("Insufficient stock for item ID {$item_id} (requested: {$qty}, available: {$available_qty})");
                }

                $average_cost = ReuseableCode::average_cost_sales($item_id, $warehouse_id, 0);

                $stock_rows[] = [
                    'main_id' => $id,
                    'master_id' => 0,
                    'voucher_no' => $request->gd_no,
                    'voucher_date' => $request->gd_date,
                    'supplier_id' => 0,
                    'customer_id' => $buyers_id,
                    'voucher_type' => 5,
                    'rate' => $send_rate,
                    'sub_item_id' => $item_id,
                    'batch_code' => '',
                    'qty' => $qty,
                    'discount_percent' => $send_discount,
                    'discount_amount' => 0,
                    'amount' => $qty * $average_cost,
                    'status' => 1,
                    'warehouse_id' => $warehouse_id,
                    'username' => Auth::user()->username ?? Auth::user()->name,
                    'created_date' => date('Y-m-d'),
                    'opening' => 0,
                    'so_data_id' => $data_id,
                ];

                $lineSalesTaxAmount = ($send_amount * $salesTaxRate) / 100;
                $lineFurtherTaxAmount = ($send_amount * $furtherTaxRate) / 100;
                $lineAdvanceTaxAmount = ($send_amount * $advanceTaxRate) / 100;

                $delivery_note_data = new DeliveryNoteData();
                $delivery_note_data = $delivery_note_data->SetConnection('mysql2');
                $delivery_note_data->master_id = $id;
                $delivery_note_data->so_id = $request->master_id;
                $delivery_note_data->desc = $desc;
                $delivery_note_data->so_data_id = $data_id;
                $delivery_note_data->gd_no = $request->gd_no;
                $delivery_note_data->gd_date = $request->gd_date;
                $delivery_note_data->item_id = $item_id;
                $delivery_note_data->warehouse_id = $warehouse_id;
                $delivery_note_data->groupby = $group_counter;
                $delivery_note_data->bundles_id = $bundles_id;
                $delivery_note_data->tax = $salesTaxRate;
                $delivery_note_data->tax_amount = $lineSalesTaxAmount;
                $delivery_note_data->batch_code = '';
                $delivery_note_data->out_qty_details = (string) $qty;
                $delivery_note_data->rate = $send_rate;
                $delivery_note_data->amount = $send_amount;
                $delivery_note_data->qty = $qty;
                $delivery_note_data->sales_tax_further_per = $furtherTaxRate;
                $delivery_note_data->sales_tax_further = $lineFurtherTaxAmount;
                $delivery_note_data->advance_tax_rate = $advanceTaxRate;
                $delivery_note_data->advance_tax_amount = $lineAdvanceTaxAmount;
                $delivery_note_data->status = 1;
                $delivery_note_data->date = date('Y-m-d');
                $delivery_note_data->username = Auth::user()->name;
                $delivery_note_data->save();

                $stock_rows[count($stock_rows) - 1]['master_id'] = $delivery_note_data->id;

                $total_grand_qty += $qty;
                $total_grand_amount += $send_amount;
                $group_counter++;
            }

            if (empty($stock_rows)) {
                throw new \Exception('Please enter delivery quantity for at least one item.');
            }

            DB::connection('mysql2')->table('stock')->insert($stock_rows);

            if ($request->master_id) {
                $sale_order = Sales_Order::on('mysql2')->find($request->master_id);
                if ($sale_order) {
                    $sale_order->delivery_note_status = 1;
                    $sale_order->save();
                }
            }

            SalesHelper::sales_activity($request->gd_no, $request->gd_date, $total_grand_amount + $salesTaxAmount, 2, 'Update');
            DB::Connection('mysql2')->commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->withErrors(['error' => $ex->getMessage()]);
        }

        return Redirect::to('sales/viewDeliveryNoteList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m']);
    }

    function updateDispatch(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {
        $delivery_note = new Dispatch();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->find($request->edit_id);
        $delivery_note->dc_id= $request->dc_id;
        $delivery_note->production_plan_id= $request->pp_id;
        $delivery_note->packing_id= $request->p_id;
        $delivery_note->item_id=0;
        $delivery_note->so_id=$request->so_id;
        $delivery_note->customer_id=$request->customer_id;
        $delivery_note->material_requisition_id=$request->mr_id;

        $delivery_note->dispatch_location=$request->dispatch_location;
        $delivery_note->transporter_name=$request->transporter_name;
        $delivery_note->vehicle_type=$request->vehicle_type;
        $delivery_note->vehicle_no=$request->vehicle_no;
        $delivery_note->dispatch_no=$request->gd_no;
        $delivery_note->dispatch_date=$request->gd_date;
        // $delivery_note->other_refrence = $request->other_refrence;
        // $delivery_note->order_no = $request->order_no;;
        // $delivery_note->order_date = $request->order_date;;
        // $delivery_note->despacth_document_no = $request->despacth_document_no;
        // $delivery_note->despacth_document_date = $request->despacth_document_date;
        // $delivery_note->despacth_through = $request->despacth_through;
        // $delivery_note->destination = $request->destination;
        // $delivery_note->terms_of_delivery = $request->terms_of_delivery;
        // $delivery_note->buyers_id = $request->buyers_id;
        // $delivery_note->due_date = $request->due_date;
        // $delivery_note->sales_tax_amount = CommonHelper::check_str_replace($request->sales_tax_apply);
            $SalesTaxAmount = CommonHelper::check_str_replace($request->sales_tax_apply);
        // $delivery_note->description = $request->description;
        $delivery_note->status = 1;
        $delivery_note->date = date('Y-m-d');
        $delivery_note->username = Auth::user()->name;

        $delivery_note->save();

        DB::Connection('mysql2')->table('dispatch_datas')->where('dispatch_id', $request->edit_id)->delete();
        DB::Connection('mysql2')->table('stock')->where('main_id', $request->edit_id)->where('voucher_no',$request->gd_no)->where('voucher_type',5)->delete();
        $count = $request->count;
        $total_amount = 0;
        $actual_qty = 0;
        $Actualsend_qty =0;
        $total_send_qty = 0;
        for ($i = 1; $i <= $count; $i++):
            $delivery_note_data = new DispatchData();
            $delivery_note_data = $delivery_note_data->SetConnection('mysql2');
            $delivery_note_data->dispatch_id=$request->edit_id;
            $delivery_note_data->machine_proccess_data_id=$request->mr_id;

            // $delivery_note_data->so_id = $request->master_id;
            // $delivery_note_data->so_data_id = $request->input('data_id' . $i);
            // $delivery_note_data->gd_no = $request->gd_no;
            // $delivery_note_data->gd_date = $request->input('gd_date');
            $delivery_note_data->item_id = $request->input('item_id' . $i);
            $batch_code=$request->input('batch_code' . $i);
            if($batch_code==''):
                $batch_code=0;
            endif;

            $delivery_note_data->batch_code=$batch_code;

            $qty = CommonHelper::check_str_replace($request->input('qty' . $i));
            $actual_qty += DB::Connection('mysql2')->table('sales_order_data')->where('id',$request->input('data_id' . $i))->first()->qty;
            $send_qty = CommonHelper::check_str_replace($request->input('send_qty' . $i));


            $rate = CommonHelper::check_str_replace($request->input('send_rate' . $i));
            $amount = CommonHelper::check_str_replace($request->input('send_amount' . $i));


            $delivery_note_data->qty = $send_qty;

            $delivery_note_data->rate = $rate;
            // $delivery_note_data->tax=$request->input('send_discount' . $i);
            // $delivery_note_data->tax_amount=$request->input('send_discount_amount' . $i);
            // $delivery_note_data->amount = $amount;
            $total_amount+=$amount;


            $delivery_note_data->warehouse_id = $request->input('warehouse' . $i);
            // $delivery_note_data->groupby = $request->input('groupby' . $i);
            // $delivery_note_data->bundles_id = $request->input('bundles_id' . $i);
            $delivery_note_data->status = 1;
            $delivery_note_data->date = date('Y-m-d');
            $delivery_note_data->username = Auth::user()->name;

            $delivery_note_data->save();
            $master_data_id = $delivery_note_data->id;
            //$Actualsend_qty += DB::Connection('mysql2')->table('delivery_note_data')->where('so_data_id',$request->input('data_id' . $i))->first()->qty;
            $Actualsend_qty = DB::Connection('mysql2')->table('delivery_note_data')->where('so_data_id',$request->input('data_id' . $i))->sum('qty');
            $total_send_qty += $Actualsend_qty;


            $stock = array
            (
                'main_id' => $request->edit_id,
                'master_id' => $master_data_id,
                'voucher_no' => $request->gd_no,
                'voucher_date' => $request->gd_date,
                'supplier_id' => 0,
                'customer_id' => $request->buyers_id,
                'voucher_type' => 5,
                'rate' => $rate,
                'sub_item_id' => $request->input('item_id' . $i),
                'batch_code' => $request->input('batch_code' . $i),
                'qty' => $send_qty,
                'amount' => $rate * $send_qty,
                'status' => 1,
                'warehouse_id' => $request->input('warehouse' . $i),
                'username' => Auth::user()->username,
                'created_date' => date('Y-m-d'),
                'created_date' => date('Y-m-d'),
                'opening' => 0,
                'so_data_id' => $request->input('data_id' . $i)
            );
            DB::Connection('mysql2')->table('stock')->insert($stock);

        endfor;

        if ($total_send_qty == $actual_qty):


        //     $sale_order = new Sales_Order();
        //     $sale_order = $sale_order->SetConnection('mysql2');
        //     $sale_order = $sale_order->find($request->master_id);
        //     $sale_order->delivery_note_status = 1;
        //     $sale_order->save();
        // else:
        //     $sale_order = new Sales_Order();
        //     $sale_order = $sale_order->SetConnection('mysql2');
        //     $sale_order = $sale_order->find($request->master_id);
        //     $sale_order->delivery_note_status = 0;
        //     $sale_order->save();
        endif;
        echo $total_send_qty.' == '.$actual_qty;

            SalesHelper::sales_activity($request->gd_no,$request->gd_date,$total_amount+$SalesTaxAmount,2,'Update');
            DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex )
        {


            DB::rollBack();

        }
            

        return Redirect::to('sales/viewDispatchList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');

    }
}
