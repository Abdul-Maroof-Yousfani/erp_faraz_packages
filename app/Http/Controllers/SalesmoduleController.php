<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Helpers\SalesHelper;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Config;
use Redirect;
use Session;
use Exception;
use App\Models\Transactions;
use App\Models\SaleQuotation;


class SalesmoduleController extends Controller
{
    public function salemodule()
    
    {
        return view('selling.salemodule.salemodule');
    }
}
