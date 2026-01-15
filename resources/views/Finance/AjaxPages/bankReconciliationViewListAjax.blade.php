


<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
$from=Input::get('fromDate');
$to=Input::get('toDate');
$acc_id=explode(',',Input::get('accountName'));
$acc_id  =$acc_id[0];
$count = 1;

// paid to
$cost_center=Input::get('paid_to');


        if ($cost_center!=0):
        $clause='and sub_department_id="'.$cost_center.'"';
        else:
            $clause='';
        endif;

// end
$m=Input::get('m');

$total_credit = 0 ;
$total_debit  = 0 ;


?>
<style>
    .hov:hover {
        background-color: yellow;
    }

</style>



<div id="">
    <div class="row">
       

    <table class="table table-bordered sf-table-th sf-table-list" id="table_export1" >
        <thead>
            <tr>
                <td colspan="11" style="background-color:#ffffff; color:black;">

                </td>
            </tr>
            <tr>
                <th class="text-center">S.No</th>
                <th class="text-center">Bank</th>
                <th class="text-center">from Date</th>
                <th class="text-center">To Date</th>
                <th class="text-center">bank opening balance erp</th>
                <th class="text-center">bank closing balance</th>
                <th class="text-center">bank statement balance</th>
                <th class="text-center">company book balance</th>
                <th class="text-center">outstanding</th>
                <th class="text-center">difference</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody id="<?php // echo $member_id; ?>">
            @foreach($BankReconciliation as $key => $value)
                <tr class="hov" >
                    <td>
                        {{$count++}}
                    </td>
                    <td>
                        @php echo CommonHelper::get_account_name($value->account_id) ?? ''; @endphp
                    </td>
                    <td>
                        @php echo CommonHelper::new_date_formate($value->from_date); @endphp
                    </td>
                    <td>
                        @php echo CommonHelper::new_date_formate($value->to_date); @endphp
                    </td>
                    <td>
            
                        {{ $value->bank_opening_balance_erp }}
                    </td>
                    <td>
                        {{ $value->bank_closing_balance }}
                    </td>
                    <td>
                        {{ $value->bank_statement_balance }}
                    </td>
                    <td>
                        {{ $value->company_book_balance }}
                    </td>
                    <td>
                        {{ $value->outstanding }}
                    </td>
                    <td>
                        {{ $value->difference }}
                    </td>
                    <td>
                        <a href="{{route('bankReconciliationViewData', ['id' => $value->id])}}"  class="btn btn-sm btn-warning " ><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                    </td>
                    
                </tr>
            @endforeach

        </tbody>
    </table>
    <div style="line-height:5px;">&nbsp;</div>


</div>

