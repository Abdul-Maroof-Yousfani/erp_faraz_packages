@php
    use App\Helpers\CommonHelper;
    use App\Helpers\ImportHelper;

    // $data = DB::connection('mysql2')->table('lc_and_lg_against_po as lc')->groupby('lc.applicant_bank')->get();
    $data = CommonHelper::get_all_account_operat_with_unique_code('1-2-8');
    // $data = DB::connection('mysql2')->table('lc_and_lg_against_po as lc')
    // // ->join('exchange_rates as ex' , 'ex.currency' , '=' ,'lc.Currency_id')
    // ->join('purchase_request as p' , 'p.id' , '=' , 'lc.po_id')
    // ->join('purchase_request_data as pd' , 'pd.master_id' , '=' , 'p.id')
    // ->join('subitem as s' , 's.id' , '=' , 'pd.sub_item_id')
    // ->join('hs_codes as hc' , 'hc.id' , '=' , 's.hs_code_id')
    // ->join('exchange_rates as ex', function($join) {
    //     $join->on('ex.currency', '=', 'lc.Currency_id')
    //          ->where('ex.id', '=', DB::raw('(SELECT MAX(id) FROM exchange_rates)')) ;
    // })
    // ->select(DB::raw('MONTH(lc.created_at) as month'), DB::raw('YEAR(lc.created_at) as year'),'ex.rate',

    // DB::raw('SUM(lc.amount * ex.rate) as atotal') , DB::raw('SUM(lc.amount) as total') , 'lc.applicant_bank' , 'lc.Currency_id')

    // ->groupBy(DB::raw('YEAR(lc.created_at)'), DB::raw('MONTH(lc.created_at)') , 'applicant_bank')
    // ->orderBy('year', 'asc')
    // ->orderBy('month', 'asc')
    // // ->orderBy('ex.id', 'desc')
    // ->get()
    // // ->map(function($q){
    // //     $q->rate = DB::connection('mysql2')->table('exchange_rates')->where('currency' ,$q->Currency_id )->latest()->first()->rate;
    // //     return $q;
    // // })
    // ;

    // $data = DB::connection('mysql2')->table('lc_and_lg_against_po as lc')
    // ->select(DB::raw('MONTH(lc.created_at) as month'), DB::raw('YEAR(lc.created_at) as year'),
    // DB::raw('SUM(lc.pkr_amount) as atotal') , DB::raw('SUM(lc.amount) as total') , 'lc.applicant_bank' , 'lc.Currency_id')
    // ->groupBy('applicant_bank')
    // // ->groupBy(DB::raw('YEAR(lc.created_at)'), DB::raw('MONTH(lc.created_at)') , 'applicant_bank')
    // ->orderBy('year', 'asc')
    // ->orderBy('month', 'asc')
    // ->get() ;
    // dd($data->toArray());
@endphp


@php
    $counter = 1;
    $grand_total_pkr_amaount = 0;
    $grand_total_total_duty = 0;
@endphp
@foreach ($data as $key => $row)
    @php

        $applicant_bank = DB::connection('mysql2')
            ->table('lc_and_lg')
            ->where('acc_id', $row->id)
            ->get()
            ->pluck('id');
            
        // dd($applicant_bank);
        $get_data = DB::connection('mysql2')
            ->table('lc_and_lg_against_po as lc')
            ->select(
                DB::raw('MONTH(lc.lc_date) as month'),
                DB::raw('YEAR(lc.lc_date) as year'),
                // DB::raw('SUM(m.pkr) as pkr_amount'),
                
                DB::raw('SUM(lc.total_duty) as total_duty'),
                DB::raw('SUM(lc.amount) as total'),
                'lc.applicant_bank',
            )
            ->whereIn('lc.applicant_bank', $applicant_bank)
            // ->where('lc.applicant_bank', $row->applicant_bank)
            // ->groupBy('applicant_bank')
            ->groupBy(DB::raw('YEAR(lc.lc_date)'), DB::raw('MONTH(lc.lc_date)'));
        

        $data1 = $get_data->orderBy('year', 'asc')->orderBy('month', 'asc')->get();
        $count = $data1->count();
        $get_data2 = DB::connection('mysql2')
            ->table('lc_and_lg_against_po as lc')
            ->join('maturity_details as m' , 'm.lc_and_lg_against_po_id' , 'lc.id')
            ->select(
                DB::raw('MONTH(lc.lc_date) as month'),
                DB::raw('YEAR(lc.lc_date) as year'),
                DB::raw('SUM(m.pkr) as pkr_amount'),
                'lc.applicant_bank',
            )
            ->whereIn('lc.applicant_bank', $applicant_bank)
            ->groupBy(DB::raw('YEAR(lc.lc_date)'), DB::raw('MONTH(lc.lc_date)'))->orderBy('year', 'asc')->orderBy('month', 'asc')->get();
        

        // dd($data1->toArray() , $count);
        // if ($key == 1) {
        //         dd($get_data->get() ,$count);
        //     }

        $total_pkr_amount = 0;
        $total_total_duty = 0;
    @endphp
    @if ($data1->toArray())
        <table class="userlittab table table-bordered sf-table-list m-font mm-tabb">
            <thead>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center max-ww1">Name</th>
                    <th class="text-center">Month</th>
                    <th class="text-center">Amount in PKR – Maturity Date</th>
                    <th class="text-center">Total Duty</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="{{ $count + 1 }}">{{ $counter++ }}
                    </td>
                    <td rowspan="{{ $count + 1 }}" style="min-width: 380px;">
                        {{ CommonHelper::get_account_name($row->id) }}
                        {{-- {{ CommonHelper::get_applicant_bank_name($row->applicant_bank) }} --}}
                    </td>

                </tr>
                @foreach ($data1 as $key => $row1)
                    <tr>
                        <td>{{ date('F', strtotime('00-' . $row1->month . '-01')) }} {{ $row1->year }}</td>
                        <td>{{ number_format($get_data2[$key]->pkr_amount, 2) }}</td>
                        <td>{{ number_format($row1->total_duty, 2) }}</td>
                    </tr>
                    @php
                        $total_pkr_amount += $get_data2[$key]->pkr_amount;
                        $total_total_duty += $row1->total_duty;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3" style="background-color: #e5e5e5; text-align: center">
                        TOTAL</td>
                    <td style="background-color: #e5e5e5;">
                        {{ number_format($total_pkr_amount, 2) }}</td>
                    <td style="background-color: #e5e5e5;">
                        {{ number_format($total_total_duty, 2) }}</td>
                </tr>
                @php
                    $grand_total_pkr_amaount += $total_pkr_amount;
                    $grand_total_total_duty += $total_total_duty;
                @endphp
            </tbody>

        </table>
    @endif
@endforeach


<table class="userlittab table table-bordered sf-table-list m-font mm-tabb">
    <tbody>
        <tr>
            <td colspan="4" style="background-color: #e5e5e5; min-width: 306px;">
                GRAND TOTAL</td>
            <td style="background-color: #e5e5e5;">{{ number_format($grand_total_pkr_amaount, 2) }}</td>
            <td style="background-color: #e5e5e5;">{{ number_format($grand_total_total_duty, 2) }}</td>
        </tr>
    </tbody>
</table>
