<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;

use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;

class ProductionDeleteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function delete_die(Request $request)
    {
        $Id = $request->input('id');
        $MachineData = DB::Connection('mysql2')->table('production_machine_data')->where('status',1)->select('master_id','dai_id')->get();
        $DaiDetailData = DB::Connection('mysql2')->table('production_dai_detail')->where('status',1)->where('main_id',$Id)->get();



        $MainIdArray = [];
        $MachineNames = "";
        $DaiDetailString = "";
        $DaiCounter = 1;
        foreach($DaiDetailData as $dd):
            $DaiDetailString.= $DaiCounter.'-( Batch Code = '.$dd->batch_code.', Life = '.$dd->life.', Value = '.$dd->value.', Cost = '.$dd->cost.')';
            $DaiCounter++;
        endforeach;

        foreach($MachineData as $Mfil):
            if (in_array($Id,explode(',',$Mfil->dai_id)))
            {
                array_push($MainIdArray,$Mfil->master_id);
            }
        endforeach;

        $Machine = DB::Connection('mysql2')->table('production_machine')->whereIn('id',$MainIdArray)->where('status',1)->select('machine_name')->get();
        $Counter = 1;
        foreach($Machine as $M):
            $MachineNames.= $Counter.'-('.$M->machine_name.')';
        $Counter++;
        endforeach;
        if($MachineNames !="" || $DaiDetailString != "")
        {
            echo $MachineNames.'@#@'.$DaiDetailString;
        }
        else{
            $UpdateData['status'] = 0;
            DB::Connection('mysql2')->table('production_dai')->where('id',$Id)->update($UpdateData);
            ProductionHelper::production_activity($Id,1,3);
            $MachineNames = "";
            $DaiDetailString = "";
            echo $MachineNames.'@#@'.$DaiDetailString;
        }
    }

    public function delete_mould(Request $request)
    {
        $Id = $request->input('id');
        $MachineData = DB::Connection('mysql2')->table('production_machine_data')->where('status',1)->select('master_id','mold_id')->get();
        $MouldDetailData = DB::Connection('mysql2')->table('mould_detail')->where('mould_id',$Id);
        $MainIdArray = [];
        $MachineNames = "";

        $MouldDetailString = "";
        $MouldCounter = 1;
        if($MouldDetailData->count() > 0):
            foreach($MouldDetailData->get() as $mm):
                $MouldDetailString.= $MouldCounter.'-( Batch Code = '.$mm->batch_code.', Life = '.$mm->life.', Value = '.$mm->value.', Cost = '.$mm->cost.')';
                $MouldCounter++;
            endforeach;
        endif;

        foreach($MachineData as $Mfil):
            if (in_array($Id,explode(',',$Mfil->mold_id)))
            {
                array_push($MainIdArray,$Mfil->master_id);
            }
        endforeach;
        $Machine = DB::Connection('mysql2')->table('production_machine')->whereIn('id',$MainIdArray)->where('status',1)->select('machine_name')->get();
        $Counter = 1;
        foreach($Machine as $M):
            $MachineNames.= $Counter.'-('.$M->machine_name.')';
            $Counter++;
        endforeach;
        if($MachineNames !="" || $MouldDetailString != "")
        {
            echo $MachineNames.'@#@'.$MouldDetailString;
        }
        else{
            $UpdateData['status'] = 0;
            DB::Connection('mysql2')->table('production_mold')->where('id',$Id)->update($UpdateData);
            ProductionHelper::production_activity($Id,3,3);
            $MachineNames = "";
            $MouldDetailString = "";
            echo $MachineNames.'@#@'.$MouldDetailString;

        }
    }

    public function delete_machine(Request $request)
    {
        $Id = $request->input('id');
        $OperationData = DB::Connection('mysql2')->table('production_work_order_data')->where('status',1)->select('master_id','machine_id')->get();
        $MainIdArray = [];
        $OperationNames = "";
        foreach($OperationData as $Ofil):
            if ($Id == $Ofil->machine_id)
            {
                array_push($MainIdArray,$Ofil->master_id);
            }
        endforeach;
        $Operation = DB::Connection('mysql2')->table('production_work_order')->whereIn('id',$MainIdArray)->where('status',1)->select('finish_good_id')->get();
        $Counter = 1;
        foreach($Operation as $Op):
            $FinishGood = CommonHelper::get_single_row('subitem','id',$Op->finish_good_id);
            $OperationNames.= $Counter.'-('.$FinishGood->sub_ic.')';
            $Counter++;
        endforeach;
        if($OperationNames !="")
        {
            echo $OperationNames;
        }
        else{
            $UpdateData['status'] = 0;
            DB::Connection('mysql2')->table('production_machine')->where('id',$Id)->update($UpdateData);
            DB::Connection('mysql2')->table('production_machine_data')->where('master_id',$Id)->update($UpdateData);
            ProductionHelper::production_activity($Id,5,3);
            echo $OperationNames;
        }
    }

    function delete_bom(Request $request)
    {

        $Id = $request->input('id');
        $finish_goods=  DB::Connection('mysql2')->table('production_bom')->where('id','=',$Id)->where('status',1)->select('finish_goods')->first()->finish_goods;
        $check_data=$this->check_bom_in_production_plan_data($finish_goods);
        if ($check_data>0):
            echo 'no';
            die;
        endif;
        $UpdateData['status'] = 0;
        DB::Connection('mysql2')->table('production_bom')->where('id','=',$Id)->update($UpdateData);
        DB::Connection('mysql2')->table('production_bom_data_direct_material')->where('master_id','=',$Id)->update($UpdateData);
        DB::Connection('mysql2')->table('production_bom_data_indirect_material')->where('main_id','=',$Id)->update($UpdateData);
        ProductionHelper::production_activity($Id,6,3);
        echo $Id;
    }

    function delete_operation(Request $request)
    {
        $Id = $request->input('id');
//        $RoutCount = DB::Connection('mysql2')->table('production_plane_data')->where('operation_id',$Id)->where('status',1)->count();
//        if($RoutCount > 0):
//            echo "no";
//        else:
            $UpdateData['status'] = 0;
            DB::Connection('mysql2')->table('production_work_order')->where('id','=',$Id)->update($UpdateData);
            DB::Connection('mysql2')->table('production_work_order_data')->where('master_id','=',$Id)->update($UpdateData);
            ProductionHelper::production_activity($Id,7,3);
        echo $Id;
//        endif;
    }

    function delete_route(Request $request)
    {
        $Id = $request->input('id');
        $PlanCount = DB::Connection('mysql2')->table('production_plane_data')->where('route',$Id)->where('status',1)->count();
        if($PlanCount > 0):
            echo "no";
        else:
            $UpdateData['status'] = 0;
            DB::Connection('mysql2')->table('production_route')->where('id','=',$Id)->update($UpdateData);
            DB::Connection('mysql2')->table('production_route_data')->where('master_id','=',$Id)->update($UpdateData);
            ProductionHelper::production_activity($Id,8,3);
        endif;
        echo $Id;
    }
    function delete_factory_over_head(Request $request)
    {
        $Id = $request->input('id');
        $UpdateData['status'] = 0;
        DB::Connection('mysql2')->table('production_factory_overhead')->where('id','=',$Id)->update($UpdateData);
        DB::Connection('mysql2')->table('production_factory_overhead_data')->where('master_id','=',$Id)->update($UpdateData);
        ProductionHelper::production_activity($Id,9,3);
        echo $Id;
    }
    function delete_production_plan(Request $request)
    {
        $Id = $request->input('id');
        $UpdateData['status'] = 0;

      $count=  DB::Connection('mysql2')->table('production_plane_issuence')->where('main_id','=',$Id)->count();
        if ($count==0):

        DB::Connection('mysql2')->table('production_plane')->where('id','=',$Id)->update($UpdateData);
        DB::Connection('mysql2')->table('production_plane_data')->where('master_id','=',$Id)->update($UpdateData);
            echo $Id;
        else:
            echo 'no';
            endif;
    //    DB::Connection('mysql2')->table('production_plane_issuence')->where('main_id','=',$Id)->update($UpdateData);

    }

    function  check_bom_in_production_plan_data($finish_good)
    {
     return   DB::Connection('mysql2')->table('production_plane_data')->where('finish_goods_id',$finish_good)->where('status',1)->count();
    }
}