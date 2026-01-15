<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Config;
use Auth;
use Redirect;

class mysql2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!$request->session()->get('run_company')):
            return  Redirect::to('/dClient');
        endif;

        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$request->session()->get('run_company').'')->dbName;
        Config::set(['database.connections.mysql2.database' => $d]);

        return $next($request);
    }
}
