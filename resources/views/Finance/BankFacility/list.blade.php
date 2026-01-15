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
                            <h2 class="subHeadingLabelClass">View Bank Facility List</h2>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="tableData" class="userlittab_cre table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Bank Name</th>
                                            <th class="text-center">Facility Name</th>
                                            <th class="text-center hide">From Days</th>
                                            <th class="text-center">No. Of Days</th>
                                            <th class="text-center">Bank Limit</th>
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
                ajax: "{{ url('finance/bankFacility') }}",
                columns: [                
                    {data: 'DT_RowIndex', searchable: false, orderable: false},
                    {data: 'bank_name', name: 'bank_name'},
                    {data: 'facility_name', name: 'facility_name'},
                    {data: 'to_days', name: 'to_days'},
                    {data: 'loan_amount', name: 'loan_amount'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection