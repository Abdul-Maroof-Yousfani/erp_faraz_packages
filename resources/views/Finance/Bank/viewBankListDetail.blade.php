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
                    <th class="text-center">Account Title</th>
                    <th class="text-center">Account No</th>
                    <th class="text-center">IBAN No</th>
                    <th class="text-center">Swift Code</th>
                    <th class="text-center">Bank Address</th>
                    <th class="text-center">Max Funded Facility</th>
                    <th class="text-center">Max Non-Funded Facility</th>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{{ $getBankListDetail->bank_name}}</td>
                        <td class="text-center">{{ $getBankListDetail->account_title}}</td>
                        <td class="text-center">{{ $getBankListDetail->account_no}}</td>
                        <td class="text-center">{{ $getBankListDetail->iban_no}}</td>
                        <td class="text-center">{{ $getBankListDetail->swift_code}}</td>
                        <td class="text-center">{{ $getBankListDetail->bank_address}}</td>
                        <td class="text-center">{{ $getBankListDetail->max_funded_facility}}</td>
                        <td class="text-center">{{ $getBankListDetail->max_non_funded_facility}}</td>
                    </tr>
                </tbody>
            <table>
        </div>
    </div>
    
