<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\DailyTask;
use App\Models\DailyTaskData;
use App\Models\Cluster;
use App\Models\Subitem;
use Auth;
use Redirect;
use DB;

class ReportsController extends Controller
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
    public function toDayActivity()
    {
        return view('Reports.toDayActivity');
    }

    public function viewBankDepositSummary(){
        return view('Reports.Finance.BankingReport.viewBankDepositSummary');
    }

    public function viewBranchPerformanceReports(){
        return view('Reports.Finance.PerformanceReports.viewBranchPerformanceReports');
    }

    public function viewBranchExpenseSummaryReports(){
        return view('Reports.Finance.PerformanceReports.viewBranchExpenseSummaryReports');
    }

    public function viewBranchExpenseSummaryDetailReports(){
        return view('Reports.Finance.PerformanceReports.viewBranchExpenseSummaryDetailReports');
    }

    public function viewInventoryPerformanceDetailReports(){
        return view('Reports.Inventory.viewInventoryPerformanceDetailReports');
    }

    public function p_detail_report()
    {
        return view('Reports.p_detail_report');
    }

    public function create_daily_activity()
    {
        $Cluster = new Cluster();
        $Cluster = $Cluster->SetConnection('mysql2')->where('status',1)->get();
        return view('Reports.create_daily_activity',compact('Cluster'));
    }

    public function insertDailyTask(Request $request)
    {
        // print_r($_POST);
        $DailyTask = new DailyTask;
        $DailyTask = $DailyTask->SetConnection('mysql2');
        $DailyTask->task_date = $request->task_date;
        $DailyTask->status    = 1;
        $DailyTask->username  = Auth::user()->name;
        $DailyTask->date      = date('Y-m-d');
        $DailyTask->save();
        $master_id = $DailyTask->id;

        $Rowcount = $request->Rowcount;
        foreach ($Rowcount as $key => $value) {
            $DailyTaskData = new DailyTaskData;
            $DailyTaskData = $DailyTaskData->SetConnection('mysql2');
            $DailyTaskData->client      = $request->input('account_id'.$value);
            $DailyTaskData->description = $request->input('desc'.$value);
            $DailyTaskData->acc_officer = $request->input('acc_officer'.$value);
            $DailyTaskData->vendor      = $request->input('vendor'.$value);
            $DailyTaskData->region      = $request->input('region_id'.$value);
            $DailyTaskData->status      = 1;
            $DailyTaskData->username    = Auth::user()->name;
            $DailyTaskData->date        = date('Y-m-d');
            $DailyTaskData->daily_task_id = $master_id;
            $DailyTaskData->action      = 1;
            $DailyTaskData->save();
        }
        return Redirect::to('reports/daily_activity_list?pageType=add&&parentCode=82&&m=1#SFR');
    }

    public function daily_activity_list()
    {
        $DailyTask = DB::Connection('mysql2')->table('daily_task')->where('status',1)->get();
        return view('Reports.daily_activity_list',compact('DailyTask'));
    }

    public function get_daily_task(Request $request)
    {
        //print_r($_GET);
        $id = $request->input('id');
        $m = $request->input('m');
        $DailyTask = DB::Connection('mysql2')->table('daily_task')
            ->join('daily_task_data', 'daily_task.id', '=', 'daily_task_data.daily_task_id')
            ->where('daily_task.status',1)
            ->where('daily_task.id',$id)
            ->select('daily_task.task_date', 'daily_task_data.*')
            ->get();

        return view('Reports.get_daily_task',compact('DailyTask'));
    }

    public function get_remarks(Request $request)
    {
        $id = $request->input('id');
        $m = $request->input('m');
        return view('Reports.get_remarks',compact('id'));
    }

    public function job_Done(Request $request)
    {
        $id = $request->input('id');
        if($id !="") {
            DB::Connection('mysql2')->table('daily_task_data')
                ->where('id', $id)
                ->update(['action' => 2]);
        }
    }

    public function job_Delay(Request $request)
    {
        $id = $request->input('id');
        if($id !="") {
            DB::Connection('mysql2')->table('daily_task_data')
                ->where('id', $id)
                ->update(['action' => 4]);
        }
    }

    public function job_Hold(Request $request)
    {
        $id = $request->input('id');
        if($id !="") {
            DB::Connection('mysql2')->table('daily_task_data')
                ->where('id', $id)
                ->update(['action' => 3]);
        }
    }

    public function edit_daily_activity(Request $request)
    {
        $id = $request->input('id');
        $Cluster = new Cluster();
        $Cluster = $Cluster->SetConnection('mysql2')->where('status',1)->get();
        $DailyTask = DB::Connection('mysql2')->table('daily_task')->where('status',1)->where('id',$id)->first();
        $DailyTaskData = DB::Connection('mysql2')->table('daily_task_data')->where('status',1)->where('daily_task_id',$id)->get();
        return view('Reports.edit_daily_activity',compact('DailyTask','DailyTaskData','Cluster'));
    }

    public function UpdateRemarks(Request $request)
    {
//        echo "<pre>";
//        print_r($_POST);
//        die;
        $m = $request->input('m');
        $id = $request->input('id');
        $remarks = $request->input('remarks');
        if($id !="") {
            DB::Connection('mysql2')->table('daily_task_data')
                ->where('id', $id)
                ->update(['remarks' => $remarks]);
        }
    }

    public function updateDailyTask(Request $request)
    {
//        echo "<pre>";
//        print_r($_POST);
//        die;
        $id = $request->id;
        $DailyTask = new DailyTask;
        $DailyTask = $DailyTask->SetConnection('mysql2');
        $DailyTask = $DailyTask->find($id);
        $DailyTask->task_date = $request->task_date;
        $DailyTask->status    = 1;
        $DailyTask->username  = Auth::user()->name;
        $DailyTask->date      = date('Y-m-d');
        $DailyTask->save();
        //$master_id = $DailyTask->id;

        DB::Connection('mysql2')->table('daily_task_data')->where('status', '=', 1)->where('daily_task_id', '=', $id)->delete();

        $Rowcount = $request->Rowcount;
        foreach ($Rowcount as $key => $value) {
            $DailyTaskData = new DailyTaskData;
            $DailyTaskData = $DailyTaskData->SetConnection('mysql2');
            $DailyTaskData->client      = $request->input('account_id'.$value);
            $DailyTaskData->description = $request->input('desc'.$value);
            $DailyTaskData->acc_officer = $request->input('acc_officer'.$value);
            $DailyTaskData->vendor      = $request->input('vendor'.$value);
            $DailyTaskData->region      = $request->input('region_id'.$value);
            $DailyTaskData->status      = 1;
            $DailyTaskData->username    = Auth::user()->name;
            $DailyTaskData->date        = date('Y-m-d');
            $DailyTaskData->daily_task_id = $id;
            $DailyTaskData->action      = 1;
            $DailyTaskData->save();
        }
        return Redirect::to('reports/daily_activity_list?pageType=add&&parentCode=82&&m=1#SFR');
    }

    public function full_daily_activity_list()
    {
        return view('Reports.full_daily_activity_list');
    }

    public function full_daily_activity_list_ajax(Request $request)
    {
        //print_r($_GET);
        //$from   = $request->input('from_date');
        //$to     = $request->input('to_date');
        $task_date = ($request->input('from_date')!="" && $request->input('to_date')!="")?'AND daily_task.task_date BETWEEN "'.$request->input('from_date').'" AND "'.$request->input('to_date').'"':'';
        $client = ($request->input('client')!="")?'AND daily_task_data.client='.$request->input('client'):'';
        $region = ($request->input('region')!="")?'AND daily_task_data.region='.$request->input('region'):'';

        $DailyTask = DB::Connection('mysql2')->select('SELECT daily_task.*, daily_task_data.* FROM daily_task
                              INNER JOIN daily_task_data ON daily_task.id = daily_task_data.daily_task_id
                              WHERE daily_task_data.status=1 '.$task_date.' '.$client.' '.$region.'
                              ORDER BY daily_task.task_date');

        $pending = DB::Connection('mysql2')->select('SELECT count(daily_task_data.id) as pending FROM daily_task INNER JOIN daily_task_data ON daily_task.id = daily_task_data.daily_task_id
                              WHERE daily_task_data.status=1 '.$task_date.' '.$client.' '.$region.' AND daily_task_data.action=1');

        $jobdone = DB::Connection('mysql2')->select('SELECT count(daily_task_data.id) as jobdone FROM daily_task INNER JOIN daily_task_data ON daily_task.id = daily_task_data.daily_task_id
                              WHERE daily_task_data.status=1 '.$task_date.' '.$client.' '.$region.' AND daily_task_data.action=2');

        $hold    = DB::Connection('mysql2')->select('SELECT count(daily_task_data.id) as hold FROM daily_task INNER JOIN daily_task_data ON daily_task.id = daily_task_data.daily_task_id
                              WHERE daily_task_data.status=1 '.$task_date.' '.$client.' '.$region.' AND daily_task_data.action=3');

        $delay   = DB::Connection('mysql2')->select('SELECT count(daily_task_data.id) as delay FROM daily_task INNER JOIN daily_task_data ON daily_task.id = daily_task_data.daily_task_id
                              WHERE daily_task_data.status=1 '.$task_date.' '.$client.' '.$region.' AND daily_task_data.action=4');

        return view('Reports.full_daily_activity_list_ajax',compact('DailyTask','pending','jobdone','hold','delay'));
    }
    

    public function rmplaningReport(Request $request)
    {
        if($request->ajax())
        {
           $sub_items = Subitem::where('status',1)->where('main_ic_id',8)->get();
           return view('selling.report.rmplaningReportAjax',compact('sub_items'));

        }
        return view('selling.report.rmplaningReport');
    }

}
