<?php
namespace App\Helpers;

use App\Models\ProductionBom;
use App\Models\ProductionBomData;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductionHelper
{

    public static function get_all_machine()
    {
        return DB::Connection('mysql2')->table('production_machine')->where('status', 1)->get();
    }
    public static function get_all_bom()
    {
        return DB::Connection('mysql2')->table('production_bom')->where('status', 1)->get();
    }
    public static function get_all_labour_working()
    {
        return DB::Connection('mysql2')->table('production_labour_working')->where('status', 1)->get();
    }

    public static function get_all_over_head_category()
    {
        return DB::Connection('mysql2')->table('production_over_head_category')->where('status', 1)->get();
    }


    public static function get_all_routing()
    {
        return DB::Connection('mysql2')->table('production_route')->where('status', 1)->get();
    }



    public static function get_finish_goods_on_machine($id)
    {
        return DB::Connection('mysql2')->table('production_machine_data')->where('status', 1)->sum('id');
    }

    public static function get_all_operations()
    {
        return DB::Connection('mysql2')->table('production_operation')->where('status', 1)->get();
    }


    public static function get_machine_name($id)
    {

        return $data = DB::Connection('mysql2')->table('production_machine')->where('id', $id)->select('machine_name')->first()->machine_name;

    }
    public static function get_over_head_cagegory_name($id)
    {

        return $data = DB::Connection('mysql2')->table('production_over_head_category')->where('id', $id)->select('name')->first()->name;

    }

    public static function get_unique_code_for_routing()
    {
        $id = DB::Connection('mysql2')->selectOne('SELECT MAX(id) as id  FROM `production_route`')->id;
        if ($id == ''):
            $no = 0;
        else:
            $no = DB::Connection('mysql2')->selectOne('SELECT voucher_no  FROM `production_route` where id =  ' . $id . '')->voucher_no;
        endif;
        $no = str_replace('R', '', $no);

        $str = $no + 1;
        $str = sprintf("%'03d", $str);
        $str = 'R' . $str;


        return $str;
    }


    public static function ppc_no($year, $month)
    {

        $variable = 100;
        $str = DB::Connection('mysql2')->selectOne("SELECT MAX(SUBSTR(order_no, 8, 3)) AS ExtractString
        FROM production_plane  where substr(`order_no`,4,2) = " . $year . " and substr(`order_no`,6,2) = " . $month . "")->ExtractString;

        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        $order_no = 'ppc' . $year . $month . $str;

        return $order_no;
    }


    public static function get_bom_for_direct($id)
    {
        return $data = DB::Connection('mysql2')->table('production_bom as a')
            ->join('production_bom_data_direct_material as b', 'a.id', '=', 'b.master_id')
            ->select('b.id as bom_data_id', 'b.item_id', 'b.qty_ft', 'b.recover_sreacp', 'b.recover_chip', 'turning_scrap')
            ->where('a.status', 1)
            ->where('a.finish_goods', $id)
            ->get();

    }

    public static function get_bom_for_indirect_workstation($id, $workStationId)
    {
        return $data = DB::Connection('mysql2')->table('production_bom as a')
            ->join('production_bom_data_indirect_material as b', 'a.id', '=', 'b.main_id')
            ->select('b.id as bom_data_id', 'b.work_station_id', 'b.item_id', 'b.qty', 'a.qty as bomQty', 'b.type', 'a.id as bom_id')
            ->where('a.status', 1)
            ->where('a.finish_goods', $id)
            ->where(function ($query) use ($workStationId) {
                if ($workStationId != 0 || $workStationId != null) {
                    $query->where('b.work_station_id', $workStationId);
                }
            })
            ->where('b.type', 1)
            ->get();

    }
    public static function get_bom_for_indirect_extra($id, $workStationId)
    {
        return $data = DB::Connection('mysql2')->table('production_bom as a')
            ->join('production_bom_data_indirect_material as b', 'a.id', '=', 'b.main_id')
            ->select('b.id as bom_data_id', 'b.item_id', 'b.qty', 'b.type', 'b.work_station_id', 'a.id as bom_id')
            ->where('a.status', 1)
            ->where('a.finish_goods', $id)
            ->where('b.work_station_id', $workStationId)
            ->get();

    }
    public static function get_bom_for_indirect($id)
    {
        return $data = DB::Connection('mysql2')->table('production_bom as a')
            ->join('production_bom_data_indirect_material as b', 'a.id', '=', 'b.main_id')
            ->select('b.id as bom_data_id', 'b.item_id', 'b.qty')
            ->where('a.status', 1)
            ->where('a.finish_goods', $id)
            ->where('b.type', 1)
            ->get();

    }
    public static function check_product_id($table, $id, $column)
    {
        return DB::Connection('mysql2')->table($table)->where('status', 1)->where($column, $id)->count();
    }

    public static function check_issue_data($bom_data_id, $master_id)
    {

        return DB::Connection('mysql2')->table('production_plane_issuence')
            ->selectRaw('*, sum(issue_qty) as sum')
            ->where('status', 1)
            ->where('bom_data_id', $bom_data_id)
            ->where('master_id', $master_id)
            ->groupBy('bom_data_id')->first();
    }
    public static function check_issue_production_floor($voucher_no, $sub_item_id, $work_station_id, $type = 1)
    {
        $in = DB::Connection('mysql2')->table('stock as a')
            ->selectRaw('a.id, a.qty, a.voucher_type')
            // ->join('production_plane_data as b','a.voucher_no','=','b.order_no')
            // ->join('production_plane as c','a.voucher_no','=','c.order_no')
            // ->join('production_plane_issuence as d','a.master_id','=','d.id')
            ->where('a.status', 1)
            ->whereIn('a.voucher_type', array(1, 4, 6, 10, 11, 12))
            ->where('a.sub_item_id', $sub_item_id)
            ->where('a.voucher_no', $voucher_no)
            ->where('a.warehouse_id', 11)
            // ->where('d.work_station_id',$work_station_id)
            // ->where('d.type',$type)
            // ->groupBy('a.id')
            ->sum('a.qty');
        // ->get();
        // dd($in);
        $out = DB::Connection('mysql2')->table('stock as a')
            // ->selectRaw('a.id, a.qty, a.voucher_type')
            // ->join('production_plane_data as b','a.voucher_no','=','b.order_no')
            // ->join('production_plane as c','a.voucher_no','=','c.order_no')
            // ->join('production_plane_issuence as d','a.master_id','=','d.id')
            ->where('a.status', 1)
            ->whereIn('a.voucher_type', array(2, 5, 3, 9))
            ->where('a.sub_item_id', $sub_item_id)
            ->where('a.voucher_no', $voucher_no)
            ->where('a.warehouse_id', 11)
            // ->where('d.work_station_id',$work_station_id)
            // ->where('d.type',$type)
            // ->groupBy('a.id')
            ->sum('a.qty');
        $temp = $in - $out;
        // dd($in, $out, $voucher_no, $sub_item_id, $work_station_id);
        return $temp;
    }


    public static function get_route_code($id)
    {
        return DB::Connection('mysql2')->table('production_route')->where('status', 1)->where('id', $id)->first()->voucher_no;
    }

    public static function plan_issue_qty($id)
    {
        return DB::Connection('mysql2')->table('production_plane_return')->where('status', 1)
            ->where('production_plan_issuence_id', $id)
            ->sum('return_qty');
    }

    public static function production_activity($MainId, $Table, $Action)
    {
        date_default_timezone_set("Asia/Karachi");
        $data = array
        (
            'main_id' => $MainId,
            'table' => $Table,
            'status' => 1,
            'username' => Auth::user()->name,
            'create_date' => date('Y-m-d'),
            'created_time' => date('h:i:sa'),
            'action' => $Action
        );
        DB::Connection('mysql2')->table('production_activity')->insert($data);
    }

    public static function check_conversion($id)
    {
        return DB::Connection('mysql2')->table('production_conversion')->where('status', 1)->where('production_plan_id', $id)->count();
    }
    public static function check_cost($id)
    {
        return DB::Connection('mysql2')->table('costing_data')->where('status', 1)->where('production_plan_id', $id)->count();
    }

    public static function get_conversion_data($id)
    {

        return DB::Connection('mysql2')->table('prouction_conversion_data')->where('status', 1)->where('master_id', $id)->get();
    }

    public static function get_conversion_id($id)
    {
        return DB::Connection('mysql2')->table('production_conversion')->where('status', 1)->where('production_plan_id', $id)->select('id')->first();
    }

    public static function hours_to_minuts($time)
    {
        $setup_time = $time;
        $setup_time = explode(':', $setup_time);
        $hour = $setup_time[0];
        $hour_to_minut = 0;
        if ($hour > 0):
            $hour_to_minut = $hour * 60;
        endif;
        $minut = $setup_time[1];
        return $setup_time = $hour_to_minut + $minut;
    }


    public static function hours_to_minutss($time)
    {

        $setup_time = $time;
        $setup_time = explode(':', $setup_time);
        return ($setup_time[0] * 60) + ($setup_time[1]) + ($setup_time[2] / 60);
    }
    public static function get_labour_rate()
    {
        $data = DB::Connection('mysql2')->table('production_labour_working as a')
            ->join('production_labour_working_data as b', 'a.id', '=', 'master_id')
            ->select('a.total_working_hours', DB::raw('SUM(b.yearly_wages_amount)wages'))
            ->where('a.status', 1)
            ->orderBy('a.id', 'desc')
            ->first();
        return $data->wages / $data->total_working_hours;
    }

    public static function get_die_detail($id)
    {
        $data = [];
        if ($id != '' && $id != 0):
            $dataa = DB::Connection('mysql2')->table('production_dai')->where('id', $id)->where('status', 1)->first();
            $data[0] = $dataa->dai_name;
            $data[1] = $dataa->size;
        else:
            $data[0] = '';
        endif;
        return $data;
    }

    public static function get_mould_detail($id)
    {
        $data = [];
        if ($id != '' && $id != 0):
            $dataa = DB::Connection('mysql2')->table('production_mold')->where('id', $id)->where('status', 1)->first();
            $data[0] = $dataa->mold_name;
            $data[1] = $dataa->size;
        else:
            $data[0] = '';
        endif;
        return $data;
    }

    public static function get_die_cost($id)
    {
        return $dataa = DB::Connection('mysql2')->table('production_dai_detail')
            ->where('main_id', $id)
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->value('cost');
    }

    public static function get_mould_cost($id)
    {
        return $dataa = DB::Connection('mysql2')->table('mould_detail')
            ->where('mould_id', $id)
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->value('cost');
    }

    public static function get_foh_amount()
    {
        return DB::Connection('mysql2')->table('production_factory_overhead as a')
            ->join('production_factory_overhead_data as b', 'a.id', '=', 'b.master_id')
            ->select(DB::raw('SUM(b.amount) as amount'))
            ->where('a.status', 1)
            ->first();
    }

    public static function get_labour_rate_rows()
    {
        $data = DB::Connection('mysql2')->table('production_labour_working as a')
            ->join('production_labour_working_data as b', 'a.id', '=', 'master_id')
            ->select('a.working_hours', DB::raw('SUM(b.yearly_wages_amount)wages'))
            ->where('a.status', 1)
            ->orderBy('a.id', 'desc')
            ->first();
        return $data;
    }


    public static function get_labour_rate_from_costing_data($id)
    {
        return $dataa = DB::Connection('mysql2')->table('costing_data')
            ->where('id', $id)
            ->where('status', 1)
            ->first();
    }

    public static function get_conversion_data_row($id)
    {

        return DB::Connection('mysql2')->table('prouction_conversion_data')->where('status', 1)->where('production_plan_data_id', $id)->first();
    }


    public static function check_production_plan_issuence($production_plane_id)
    {
        $count = false;
        $data = DB::Connection('mysql2')->table('production_plane_issuence')->where('status', 1)->where('main_id', $production_plane_id)->count();
        if ($data > 0):
            $count = true;
        endif;
        return $count;
    }

    public static function get_production_plane_detail($id)
    {
        return DB::Connection('mysql2')->table('production_plane')->where('status', 1)->where('id', $id)->first();
    }

    public static function get_production_plane_detail_data($id)
    {
        return DB::Connection('mysql2')->table('production_plane_data')->where('status', 1)->where('id', $id)->first();
    }

    public static function get_approved_plan($ppc_no)
    {

        return DB::Connection('mysql2')->table('transactions')->where('status', 1)->where('voucher_no', $ppc_no)->where('voucher_type', 19)->count();
    }

    public static function get_total_cost_of_plane($id)
    {
        $data = DB::Connection('mysql2')->selectOne
        (
            'select *
            from  costing_data
            where status=1
            and production_plan_data_id	="' . $id . '"
            '
        );

        $ppc_data = static::get_production_plane_detail_data($id);

        $value = $data->direct_material_cost + $data->indirect_material_cost + $data->direct_labour + $data->die_mould + $data->foh;
        $machine_cost = $ppc_data->planned_qty * $data->machine_cost;


        return $value = $value + $machine_cost;
    }


    public static function get_bom_detail($table, $id)
    {
        return DB::Connection('mysql2')->table($table)->where('status', 1)->where('id', $id)->first();
    }

    public static function get_all_ppc_no()
    {
        return DB::Connection('mysql2')->table('production_plane')->where('status', 1)->get();
    }

    public static function get_completion_date($ppc_no)
    {
        return DB::Connection('mysql2')->table('transactions')->where('status', 1)->where('voucher_no', $ppc_no)->where('voucher_type', 19)->first();
    }

    public static function get_costing_data($id)
    {
        return DB::Connection('mysql2')->table('costing_data')->where('status', 1)->where('production_plan_data_id', $id)->first();
    }

    public static function get_costing_direct_material($id)
    {
        return DB::Connection('mysql2')->table('direct_material_costing')->where('status', 1)->where('master_id', $id)->first();
    }

    public static function get_costing_direct_indirect_material($id)
    {
        return DB::Connection('mysql2')->table('indirect_material_costing')->where('status', 1)->where('master_id', $id)->first();
    }

    public static function get_die_detaild($id)
    {
        return $dataa = DB::Connection('mysql2')->table('production_dai_detail')
            ->where('main_id', $id)
            ->whereIn('status', array(1, 2))
            ->get();
    }

    public static function get_machine_data($id, $finish_good_id)
    {
        return DB::Connection('mysql2')->table('production_machine_data')->where('master_id', $id)->where('finish_good', $finish_good_id)->first();
    }

    public static function get_die_bacth_code($id)
    {
        return DB::Connection('mysql2')->table('production_dai_detail')->where('id', $id)->first();
    }

    public static function get_mould_bacth_code($id)
    {
        return DB::Connection('mysql2')->table('mould_detail')->where('id', $id)->first();
    }

    public static function get_die($id)
    {
        return DB::Connection('mysql2')->table('production_dai')->where('id', $id)->first();
    }
    public static function get_mould($id)
    {
        return DB::Connection('mysql2')->table('production_mold')->where('id', $id)->first();
    }

    public static function get_die_bacth_detail($id)
    {
        return DB::Connection('mysql2')->table('production_dai_detail')->where('main_id', $id)->first();
    }

    public static function get_mould_bacth_detail($id)
    {
        return DB::Connection('mysql2')->table('mould_detail')->where('mould_id', $id)->first();
    }

    public static function getProductionRequestStatus($param)
    {
        if ($param == 1) {
            return 'Pending';
        } else if ($param == 2) {
            return 'Approved';
        } else if ($param == 3) {
            return 'Rejected';
        }
    }

    public static function getAllProductionRequests($edit = null)
    {
        if ($edit) {
            return DB::connection('mysql2')->table('production_request AS pr')
                ->join('production_request_data AS prd', 'pr.id', '=', 'prd.master_id')
                ->select('pr.*')
                ->where([['pr.status', '=', 1], ['pr.approval_status', '=', 2]])
                ->orderBy('pr.id', 'desc')->groupBy('prd.master_id')->get();
        }
        return DB::connection('mysql2')->table('production_request AS pr')
            ->join('production_request_data AS prd', 'pr.id', '=', 'prd.master_id')
            ->select('pr.*')
            ->where([['pr.status', '=', 1], ['pr.approval_status', '=', 2], ['prd.production_status', '=', 1]])
            ->orderBy('pr.id', 'desc')->groupBy('prd.master_id')->get();
    }

    public static function getNewBatchCode()
    {
        $maxNumber = Stock::selectRaw("
            MAX(CAST(REGEXP_REPLACE(batch_code, '[^0-9]', '') AS UNSIGNED)) as max_num
        ")->value('max_num');

        $newNumber = $maxNumber ? $maxNumber + 1 : 1;
        return $newNumber;
    }

}
