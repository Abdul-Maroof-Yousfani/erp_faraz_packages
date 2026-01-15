<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
class AjaxController extends Controller
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
    public function get_data(Request $request)
    {
      $finish_goods=$request->finish_goods;
      $data=DB::Connection('mysql2')->table('routing')->where('finish_goods',$finish_goods)->get();?>

    <?php

        if ($data->isEmpty()): ?>
            <option>No Route Found On this Finish Goods</option>
         <?php    endif;
     foreach($data as $row): ?>
    <option value="<?php echo $row->id ?>"><?php echo $row->voucher_no ?></option>
    <?php endforeach;

    }
}
