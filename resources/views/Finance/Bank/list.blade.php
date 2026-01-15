<?php
    $accType = Auth::user()->acc_type;
    if($accType == 'client'){
        $m = Session::get('run_company');
    }else{
        $m = Auth::user()->company_id;
    }
?>
@extends('layouts.default')

@section('content')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="headquid">
                            <h2 class="subHeadingLabelClass">View Bank List</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="userlittab_cre table table-bordered sf-table-list" id="tableData">
                                        <thead>
                                            <tr>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Bank Name</th>
                                                <th class="text-center">Account Title</th>
                                                <th class="text-center">Account No</th>
                                                <th class="text-center">IBAN No</th>
                                                <th class="text-center">Swift Code</th>
                                                <th class="text-center">Bank Address</th>
                                                <th class="text-center">Max Funded Facility</th>
                                                <th class="text-center">Max Non-Funded Facility</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets\js\dataTables\jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets\js\dataTables\dataTables.bootstrap.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
            $('#tableData').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('finance/bank') }}",
                columns: [                
                    {data: 'DT_RowIndex', searchable: false, orderable: false},
                    {data: 'bank_name', name: 'bank_name'},
                    {data: 'account_title', name: 'account_title'},
                    {data: 'account_no', name: 'account_no'},
                    {data: 'iban_no', name: 'iban_no'},
                    {data: 'swift_code', name: 'swift_code'},
                    {data: 'bank_address', name: 'bank_address'},
                    {data: 'max_funded_facility', name: 'max_funded_facility'},
                    {data: 'max_non_funded_facility', name: 'max_non_funded_facility'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection