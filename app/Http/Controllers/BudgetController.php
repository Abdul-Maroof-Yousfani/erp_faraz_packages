<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Budget;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('auth');
        $this->page = 'Purchase.Budget.';
    }

    public function BudgetForm()
    {
        return view($this->page.'BudgetForm');
    }
    public function Budgetdata(Request $request)
    {
        
        $data['amount']  = $request->amount;
        $data['date'] = $request->b_date;
        $data['user']      = Auth::user()->name;

        DB::Connection('mysql2')->table('budget')->insert($data);
         return redirect('store/BudgetList');;
       
    }
    public function Budgetlist()
    {
        $data = DB::Connection('mysql2')->table('budget')->get();
        return view($this->page.'Budgetlist',compact('data'));
    }
}
