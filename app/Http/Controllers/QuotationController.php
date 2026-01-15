<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Quotation_Data;
use App\Models\Demand;
use Auth;
use Illuminate\Support\Arr;
use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
use DB;
use Session;
use phpDocumentor\Reflection\Types\Void_;

class QuotationController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->page='Purchase.Quotation.'.$request->segment(2);
    }

    public function create_quotation(Request $request)
    {
        return view($this->page);
    }

    public function create_quotation_ajax(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

         $data = DB::Connection('mysql2')->table('demand AS d')->join('demand_data AS dd','d.id','=', 'dd.master_id')
            ->leftJoin('quotation_data AS qd','qd.pr_data_id','=', 'dd.id')
            ->leftJoin('purchase_request_data AS prd','dd.id','=', 'prd.demand_data_id')
            ->select('d.*')
            ->where('d.status',1)
            ->whereBetween('d.demand_date', [$from, $to])
            ->where('d.demand_status',2)
            ->where('d.quotation_skip',0)
            ->where('d.quotation_approve',0)
            ->whereNull('prd.id')
            ->whereNull('qd.id')
            ->orderBy('d.id','desc')
            ->groupBy('dd.master_id');

        return view($this->page,compact('data'));
    }

    public function quotation_no($month , $year)
    {
        $quotation_no = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`voucher_no`,7,length(substr(`voucher_no`,3))-3),signed integer)) reg
        from `quotation` where substr(`voucher_no`,3,2) = " . $year . " and substr(`voucher_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $job_order_no = 'qo' . $year . $month . $str;
    }

    public function get_pr_for_quotaion($id)
    {
    //    $data= DB::Connection('mysql2')->table('demand as a')
    //         ->join('demand_data as b','a.id','=','b.master_id')
    //         ->select('b.*')
    //         ->where('a.status',1)
    //         ->where('a.id',$id);

        $data = DB::connection('mysql2')->table('demand as a')
        ->join('demand_data as b', 'a.id', '=', 'b.master_id')
        ->leftJoin('quotation_data as qd', 'qd.pr_data_id', '=', 'b.id')
        ->leftJoin('quotation as q', 'q.id', '=', 'qd.master_id')
        ->leftJoin('purchase_request_data as prd', 'prd.demand_data_id', '=', 'b.id')
        ->select('b.*')
        ->where('a.status', 1)
        ->where('a.id', $id)
        ->whereNull('prd.demand_data_id')
        ->where(function ($query) {
            $query->whereNull('q.id') // No matching quotation
                ->orWhereNotIn('q.quotation_status', [1, 2]); // Exclude statuses 1 and 2
        });
       return $data;
    }
    public function quotation_form(Request $request)
    {
        $id = $request->id;
        // $quotation = Quotation::where('pr_id',$id)->where('status',1)->where('quotation_status',2)->count();
        // if ($quotation > 0):
        //     echo 'no';
        //     die;
        // endif;

        $voucher_no= $this->quotation_no(date('m'),date('y'));
        $request_data = $this->get_pr_for_quotaion($id)->where('quotation_id',0)->get();
        return view($this->page,compact('voucher_no','request_data','id'));
    }

     public  function get_pr_no($id)
     {

         return DB::Connection('mysql2')->table('demand')->where('id', $id)->value('demand_no');
     }


    public function check_pr_status($id)
    { 
        return  $quotation = Quotation::where('pr_id',$id)->where('status',1)->where('quotation_status',2)->count();
    }

    public function insert_quotation(Request $request)
    {

        // if ($this->check_pr_status($request->pr_id)>0):
        //     return redirect()->back()->with('error', 'Quotation Againts This PR Alreday Approved');
        // endif;

        $quotation = Quotation::where('id',$request->id)->where('status',1)->where('quotation_status',2)->count();
        if ($quotation > 0):
            return redirect()->back()->with('error', 'Quotation Againts This PR Alreday Approved');
        endif;


        DB::Connection('mysql2')->beginTransaction();
        $voucher_no=$this->quotation_no(date('m'), date('y'));
        try {
            $quotation = new Quotation();
            $quotation = $quotation->SetConnection('mysql2');

            $demand = NotificationHelper::get_dept_id('demand','id',$request->pr_id)->select('sub_department_id','p_type')->first();
            

            $quotation->dept_id = $demand->sub_department_id;
            $quotation->p_type = $demand->p_type;


            $quotation->pr_id = $request->pr_id;
            $quotation->pr_no = $this->get_pr_no($request->pr_id);
            $quotation->voucher_no = $voucher_no;
            $quotation->voucher_date = $request->demand_date_1;
            $quotation->vendor_id = $request->supplier;
            $quotation->ref_no = $request->ref_no;
            $quotation->gst_id = $request->sales_taxx;
            $quotation->gst = $request->gst_rate;
            $quotation->currency_id = $request->currency_id;
            $quotation->currency_rate = $request->currency_rate;
            $quotation->gst_amount = CommonHelper::check_str_replace($request->sales_amount_td);
            $quotation->date = date('Y-m-d');
            $quotation->status = 1;
            $quotation->username = Auth::user()->name;
            $quotation->description = $request->description_1;
            $quotation->save();
            $master_id=$quotation->id;

            $quotation_data = $request->pr_data_id;

            $checkedRows = $request->input('checked_rows', []);
            $prDataIds = $request->input('pr_data_id', []);
            $rates = $request->input('rate', []);
            $amounts = $request->input('amount', []);


            foreach ($checkedRows as $key) {
                $quotation_data = new Quotation_Data();
                $quotation_data = $quotation_data->SetConnection('mysql2');
                $quotation_data->master_id = $master_id;
                $quotation_data->voucher_no = $voucher_no;
                $quotation_data->pr_id = $request->pr_id;
                $quotation_data->quotation_status = 1;
                $quotation_data->pr_data_id = $prDataIds[$key];
                $quotation_data->rate = $rates[$key];
                $quotation_data->amount = $amounts[$key];
                $quotation_data->save();

            }

            $demand_no= DB::Connection('mysql2')->table('demand')->where('id',$request->pr_id)->value('demand_no');
            $subject = 'Purchase Quotation For '.$demand_no; 
            NotificationHelper::send_email('Purchase Quotation','Create',$demand->sub_department_id,$voucher_no,$subject,$demand->p_type);

            DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex ) {
            DB::rollBack();
            return self::index($request)->withErrors($ex->getMessage());
        }
        Session::flash('dataInsert', 'Successfully Saved');
        return redirect('quotation/quotation_list');
    }

    public function quotation_list()
    {
      
   
        return view($this->page);
    }

    public function quotation_query()
    {
        return DB::Connection('mysql2')->table('quotation as a')
            ->join('quotation_data as b','a.id','=','b.master_id')
            ->join('demand as c','a.pr_id','=','c.id')
            ->select('a.*',DB::Connection('mysql2')->raw('SUM(b.amount) As amount'),'c.demand_date','c.quotation_approve','a.currency_id')
            ->where('a.status',1)
            ->groupBy('a.id')
            ->orderBy('a.id','Desc')
            ->orderBy('a.pr_no')
            ->get();
    }

    public function quotation_list_ajax()
    {
        $data=$this->quotation_query();        
        return view($this->page,compact('data'));
    }

    public function view_quotation(Request $request)
    {
        $id = $request->id;
        $quotation = Quotation::where('id',$id)->first();
        $quotation_data = DB::Connection('mysql2')->table('quotation_data as a')
            ->join('demand_data as b','a.pr_data_id','=','b.id')
            ->where('a.master_id',$id)->get();

        return view($this->page,compact('quotation','quotation_data','id'));
    }

    public function approve(Request $request)
    {
        $quotation = Quotation::where('id',$request->id)->where('status',1)->where('quotation_status',2)->count();
        if ($quotation > 0):
            echo 'no';
            die;
        endif;
        $quotation = Quotation::find($request->id);
        $quotation->quotation_status = 2;
        $quotation->approve_username = Auth::user()->name;
        $quotation->save();

        // $quotation_data = Quotation_Data::where('master_id', $request->id);
        // $quotation_data->update(['quotation_status' => 2]);

        $pr_id = $quotation->pr_id;

        $demand = DB::Connection('mysql2')->table('demand_data')->where('master_id',$pr_id)->where('quotation_id',0)->count();

        if($demand == 0) {
            $demand = new Demand();
            $demand = $demand->SetConnection('mysql2');
            $demand = $demand->find($pr_id);
            $demand->quotation_approve = 1;
            $demand->save();
        }

        $subject = 'Purchase Quotation Approved'; 
           
        // NotificationHelper::send_email('Purchase Quotation','Create',$sub_department_id,$voucher_no,$subject);
        echo $request->id;
    }


    public function qutation_summary(Request $request)
    {
          $vendor= DB::Connection('mysql2')
         ->table('quotation as a')
        ->join('supplier as b','a.vendor_id','=','b.id')
        ->join('demand as c','c.id','=','a.pr_id')
        ->select('a.pr_id','b.name','a.vendor_id','c.id as demand_id','a.dept_id','c.demand_no','a.p_type')
        ->where('a.pr_id',$request->id)
        ->get();


       $demand_data= DB::Connection('mysql2')->table('demand_data as a')
       ->join('quotation_data as c','c.pr_data_id','=','a.id')
       ->join('subitem as b','a.sub_item_id','=','b.id')
       ->select('b.sub_ic','a.id','c.quotation_status','a.qty','c.vendor','c.id as quotation_id','a.master_id','c.description','c.voucher_no','a.demand_no','a.required_date')
       ->where('a.master_id',$request->id)
       ->where('c.status',1)
       ->groupBy('a.id')
       ->get();


        return view($this->page,compact('vendor','demand_data'));
    }

   public function approved_quotation_summary(Request $request)
   {
    
    DB::Connection('mysql2')->beginTransaction();

    try {

        $vendor= $request->vendor;
        $desc= $request->desc;
        $pr_no= $request->pr_no;
        $dept_id= $request->dept_id;
        $p_type= $request->p_type;
        foreach ($request->array as $row):

         $data= explode(',',$row);
         $quotation_id = $data[0];
         $pr_data_id = $data[1];
         $pr_id = $data[2];    
         
          $data = array 
          (
            'quotation_status' => 1 ,
             'vendor' =>  $vendor,
             'description'=>$desc
          );
          DB::Connection('mysql2')->table('quotation_data')
          ->where('id',$quotation_id)
          ->update($data);

          DB::Connection('mysql2')->table('demand_data')
          ->where('id',$pr_data_id)
          ->update(['quotation_id'=>$quotation_id]);
        endforeach;


        $voucher_no = 'Quotation Against '.$pr_no;
        $subject = 'Purchase Quotation Approved For '.$pr_no;            
        NotificationHelper::send_email('Purchase Quotation','Approve',$dept_id,$voucher_no,$subject,$p_type);
        echo 'Done';

        $this->check_quotation_update($pr_id);

     
        DB::Connection('mysql2')->commit();

    }
    catch(\Exception $e)
    {
        DB::Connection('mysql2')->rollback();
        echo "EROOR"; //die();
        dd($e->getMessage());

    }

   }

   public function check_quotation_update($id = null)
   {
      $demand=  DB::Connection('mysql2')->table('demand_data')->where('master_id',$id)->where('quotation_id',0)->count();

      if ($demand==0):
        DB::Connection('mysql2')->table('demand')->where('id',$id)->update(['quotation_approve'=>1]);
      endif;

   }

    public function edit_quotation($pr_id, $q_id)
    {
        $id = $pr_id;
        $quotation = Quotation::find($q_id);
        $quotationData = Quotation_Data::where('master_id',$q_id)->where('status',1)->where('vendor','!=',0)->count();

        if ($quotationData > 0) :
            return redirect()->back()->with('error', 'Quotation Againts This PR Alreday Approved');
        endif;

        $voucher_no = $this->quotation_no(date('m'), date('y'));
        $request_data = $this->get_pr_for_quotaion($pr_id)->where('quotation_id', 0)->get();
        return view($this->page, compact('voucher_no', 'request_data', 'id','quotation'));
    }

    public function update_quotation($id,Request $request)
    {
        // dd($request->all());
        DB::Connection('mysql2')->beginTransaction();
        $voucher_no = $this->quotation_no(date('m'), date('y'));
        try {
            $quotation = Quotation::find($id);
            // dd($quotation);
            // $quotation = $quotation->SetConnection('mysql2');

            $demand = NotificationHelper::get_dept_id('demand', 'id', $request->pr_id)->select('sub_department_id', 'p_type')->first();


            $quotation->dept_id = $demand->sub_department_id;
            $quotation->p_type = $demand->p_type;
            $quotation->description = $request->description_1;

            $quotation->pr_id = $request->pr_id;
            $quotation->pr_no = $this->get_pr_no($request->pr_id);
            $quotation->voucher_no = $request->pr_no;
            $quotation->voucher_date = $request->demand_date_1;
            $quotation->vendor_id = $request->supplier;
            $quotation->ref_no = $request->ref_no;
            $quotation->gst_id = $request->sales_taxx;
            $quotation->gst = $request->gst_rate;
            $quotation->currency_id = $request->currency_id;
            $quotation->currency_rate = $request->currency_rate;
            $quotation->gst_amount = CommonHelper::check_str_replace($request->sales_amount_td);
            $quotation->date = date('Y-m-d');
            $quotation->status = 1;
            $quotation->quotation_status = 1;
            $quotation->username = Auth::user()->name;
            // dd($quotation);
            $quotation->update();
            $master_id = $id;

            $quotation_data = $request->quotation_data_id;

            foreach ($quotation_data  as $key => $row) :
                $quotation_data = Quotation_Data::find($row);
                $quotation_data = $quotation_data->SetConnection('mysql2');
                $quotation_data->master_id = $master_id;
                $quotation_data->voucher_no = $request->pr_no;
                $quotation_data->pr_id = $request->pr_id;
                $quotation_data->pr_data_id = $request->pr_data_id[$key];
                $quotation_data->rate = $request->input('rate')[$key];
                $quotation_data->amount = $request->input('amount')[$key];
            
                $quotation_data->update();
            endforeach;
            // $demand_no = DB::Connection('mysql2')->table('demand')->where('id', $request->pr_id)->value('demand_no');
            // $subject = 'Purchase Quotation For ' . $demand_no;
            // NotificationHelper::send_email('Purchase Quotation', 'Create', $demand->sub_department_id, $voucher_no, $subject, $demand->p_type);
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {
            DB::rollBack();
            // return self::index($request)->withErrors($ex->getMessage());
        }
        return redirect('quotation/quotation_list')->with('message', 'Quotation Successfully Update');
    }

    public function delete_quotation(Request $request)
    {
        try {
            $quotationData = Quotation_Data::where('master_id',$request->id)->where('status',1);
            if ($quotationData->where('vendor','!=',0)->count() > 0) {
                return response()->json(['status'=>'error', "message"=> "Cannot remove approved quotation"]);
            }
            $quotationData->where('vendor',0)->update(['status'=>0]);
            Quotation::find($request->id)->update(['status'=>0]);
            return response()->json(['status'=>'Success', "message"=> "Successfully Deleted"]);
        } catch (Exception $th) {
            return response()->json(['status'=>'error', "message"=> $th->getMessage()]);
        }
    }
}
