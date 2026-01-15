<?php
namespace App\Helpers;
use App\Models\Countries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\Input;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SupplierInfo;
use App\Models\Subitem;
use App\Models\Employee;
use App\Models\EmployeeExitClearance;
use App\Models\MenuPrivileges;
use App\Models\Menu;
use App\Models\WorkStation;
use App\Models\UOM;
use App\Models\PurchaseVoucher;
use App\Models\Account;
use App\Models\FinanceDepartment;
use App\Models\Transactions;
use App\Models\CostCenter;
use App\Models\DepartmentAllocation1;
use App\Models\SalesTaxDepartmentAllocation;
use App\Models\CostCenterDepartmentAllocation;
use App\Models\PurchaseType;
use App\Models\Currency;
use App\Models\GRNData;
use App\Models\DemandType;
use App\Models\Warehouse;
use App\Models\GoodsReceiptNote;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use App\Models\PurchaseVoucherThroughGrn;
use App\Models\PurchaseVoucherThroughGrnData;
use App\Models\SubItemCharges;
use App\Models\Pvs;
use App\Models\Pvs_data;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Region;
use App\Models\SurveryBy;
use App\Models\Client;
use App\Models\Type;
use App\Models\ProductType;
use App\Models\Cities;
use App\Models\ResourceAssigned;
use App\Models\ClientJob;
use App\Models\IncomeTaxDeduction;
use App\Models\NewPvData;
use App\Models\JobOrder;
use App\Models\JobOrderData;
use App\Models\Estimate;
use App\Models\NewPurchaseVoucherPayment;
use App\Models\NewPurchaseVoucher;
use App\Models\PaidTo;
use App\Models\Emp;
use App\Models\Branch;
use App\Models\CompanyGroup;
use App\Models\CompanyLocation;
use App\Models\MaterialRequisitionData;
use App\Models\ProductionWorkOrder;
use App\Models\Prospect;
use App\Models\Regions;
use App\Models\SaleQuotation;
use App\Models\Sales_Order;
use App\Models\SalesTaxGroup;
use App\User;
use Illuminate\Support\Facades\Storage;
use Session;

class DashboardHelper
{

    public static function SalesFlowChart($year)
    {
        $data = DB::Connection('mysql2')->table(DB::raw("
                        (SELECT 'January' as month_name
                        UNION SELECT 'February'
                        UNION SELECT 'March'
                        UNION SELECT 'April'
                        UNION SELECT 'May'
                        UNION SELECT 'June'
                        UNION SELECT 'July'
                        UNION SELECT 'August'
                        UNION SELECT 'September'
                        UNION SELECT 'October'
                        UNION SELECT 'November'
                        UNION SELECT 'December') calendar
                    "))
                    ->leftJoin('sales_tax_invoice as sti', function ($join) use ($year) {
                        $join->on(DB::raw('calendar.month_name'), '=', DB::raw('MONTHNAME(sti.gi_date)'));
                        $join->whereYear('sti.gi_date', $year);
                    })
                    ->leftJoin('sales_tax_invoice_data as stid', 'sti.id', '=', 'stid.master_id')
                    ->groupBy('calendar.month_name')
                    ->orderBy(DB::raw("MONTH(STR_TO_DATE(calendar.month_name, '%M'))"))
                    ->select('calendar.month_name', DB::raw('IFNULL(SUM(stid.amount), 0) as total_amount'))
                    ->get();

                    return $data;
    }


    public static  function BankBalanceReport()
    {
        // return $data =   DB::Connection('mysql2')->table('accounts as a')
        // ->join('transactions as t', 't.acc_id', '=', 'a.id')
        // ->where('a.code', 'LIKE', '%1-2-8-%')
        // ->groupBy('a.id')
        // ->select('a.name', DB::raw('SUM(t.amount) as total_amount'))
        // ->get();

            $data = DB::Connection('mysql2')->table('accounts as a')
                    ->join('transactions as t', 't.acc_id', '=', 'a.id')
                    ->where('a.code', 'LIKE', '%1-2-8-%')
                    ->groupBy('a.name', 't.debit_credit')
                    ->select('a.name', 't.debit_credit', DB::raw('SUM(t.amount) as total_amount'))
                    ->get();

                    $result =DB::Connection('mysql2')->table('accounts as a')
         ->select('a.id','a.name','a.code',
         DB::raw('(SELECT SUM(CASE
                         WHEN b.debit_credit = 1 and status=1 THEN b.amount
                         WHEN b.debit_credit = 0 and status=1 THEN -b.amount
                         ELSE 0
                     END) FROM transactions as b
                     WHERE b.acc_id = a.id
                     and b.status=1) AS balance')
     )
         ->where('a.code', 'LIKE', '%1-2-8-%')
         ->where('a.status', 1)
         ->where('operational',1)
             ->orderBy('a.level1', 'ASC')
             ->orderBy('a.level2', 'ASC')
             ->orderBy('a.level3', 'ASC')
             ->orderBy('a.level4', 'ASC')
             ->orderBy('a.level5', 'ASC')
             ->orderBy('a.level6', 'ASC')
             ->orderBy('a.level7', 'ASC')
             ->groupBy('a.id')
             ->get();




            // $result = $data->groupBy('name')
            //     ->map(function ($group) {
            //         return $group->sum(function ($item) {
            //             return $item->debit_credit == 1 ? $item->total_amount : -$item->total_amount;
            //         });
            //     });

            return $result;

    }
    public static  function CustomerWiseSales($year = null)
    {
        $data = DB::Connection('mysql2')->table('sales_tax_invoice as sti')
            ->join('sales_tax_invoice_data as stid', 'sti.id', '=', 'stid.master_id')
            ->join('customers as c', 'c.id', '=', 'sti.buyers_id');

        if ($year) {
            $financial_year = explode(" ",$year);
            $from_date = $financial_year[0].'-07-01';
            $to_date = $financial_year[1].'-06-30';
            $data = $data ->whereBetween('sti.gi_date',[$from_date,$to_date]);
        }
        $data = $data
            ->groupBy('c.id')
            ->select('c.name', DB::raw('SUM(stid.amount) as amount'))
            ->get();

        return $data;
    }
    
}
