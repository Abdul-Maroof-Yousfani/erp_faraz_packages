<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">S.No.</th>
                    <th class="text-center">Facility Name</th>
                    <th class="text-center hide">From Days</th>
                    <th class="text-center">No Days</th>
                    <th class="text-center">Bank Limit</th>
                    <th class="text-center hidden">Interest Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bankFacilityList as $bflKey => $bflRow):
                    <tr>
                        <td class="text-center">{{ ++$bflKey}}</td>
                        <td>{{ $bflRow->facility_name}}</td>
                        <td class="hide">{{ $bflRow->from_days}}</td>
                        <td class="text-center">{{ $bflRow->to_days}}</td>
                        <td class="text-right">{{ number_format($bflRow->loan_amount,2)}}</td>
                        <td class="text-center hidden">{{ $bflRow->interest_rate}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>