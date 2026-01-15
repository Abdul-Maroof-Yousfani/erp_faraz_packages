@php
    use App\Helpers\FinanceHelper;
    $accType = Auth::user()->acc_type;
    if($accType == 'client'){
        $m = Session::get('run_company');
    }else{
        $m = Auth::user()->company_id;
    }
@endphp
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <th class="text-center">Bank Name</th>
                    <th class="text-center">Facility Name</th>
                    <th class="text-center hide">From Days</th>
                    <th class="text-center">No. Of Days</th>
                    <th class="text-center">Bank Limit</th>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{{ $getBankFacilityListDetail->bank_name}}</td>
                        <td class="text-center">{{ $getBankFacilityListDetail->facility_name}}</td>
                        <!-- <td class="text-center">{{ $getBankFacilityListDetail->from_days}}</td> -->
                        <td class="text-center">{{ $getBankFacilityListDetail->to_days}}</td>
                        <td class="text-center">{{ $getBankFacilityListDetail->loan_amount}}</td>
                    </tr>
                </tbody>
            <table>
        </div>
    </div>
    
