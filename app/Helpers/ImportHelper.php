<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\Input;
use App\Models\Import\HsCode;
use App\Models\UOM;
use App\User;
use Illuminate\Support\Facades\Storage;
use Session;

class ImportHelper
{

    public static function getAllHsCode()
    {
        $HsCode = HsCode::where('status',1)->select('id','hs_code')->get();

        return $HsCode;
    }

    public static function get_hs_code($id)
    {
        if ($id != 0):

            $hs_codes = DB::connection('mysql2')->table('subitem as s')
                        ->join('hs_codes as hc', 's.hs_code_id', '=', 'hc.id')
                        ->select('hc.hs_code')
                        ->where('s.id',$id )
                        ->first();

            return (!empty($hs_codes)) ?  strtoupper($hs_codes->hs_code) : '';
        else:
            return '';
        endif;
    }

    public static function get_hs_code_array($ids)
    {
        if ($ids != 0):

            $hs_codes = DB::connection('mysql2')->table('subitem as s')
                        ->join('hs_codes as hc', 's.hs_code_id', '=', 'hc.id')
                        // ->select('hc.hs_code')
                        ->whereIn('s.id',$ids )
                        ->pluck('hs_code')->toArray();
                        
            return $hs_codes ?? '';
        else:
            return '';
        endif;
    }

    public static function get_uom_name($id)
    {
        if ($id != 0):

            $uom = DB::connection('mysql2')->table('subitem as s')
                        ->join('uom as u', 's.uom', '=', 'u.id')
                        ->select('u.uom_name')
                        ->where('s.id',$id )
                        ->first();

            return (!empty($uom)) ?  strtoupper($uom->uom_name) : '';
        else:
            return '';
        endif;
    }

    public static function get_hs_code_data($id)
    {
        if ($id != 0):

            $hs_codes = DB::connection('mysql2')->table('hs_codes as hc')
                       ->join('subitem as s', 's.hs_code_id', '=', 'hc.id')
                        ->select('hc.*','hc.total_duty_with_taxes')
                        ->where('s.status',1 )
                        ->where('s.id',$id )
                        ->first();

            return (!empty($hs_codes)) ? $hs_codes : '';
        else:
            return '';
        endif;
    }

}
