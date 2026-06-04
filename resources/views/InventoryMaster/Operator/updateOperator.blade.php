@extends('layouts.default')

@section('content')
@include('select2')
<style>
    .my-lab label {
        padding-top:0px; 
    }
</style>
<div class="row well_N align-items-center">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <ul class="cus-ul">
            <li>
                <h1>Inventory Master</h1>
            </li>
            <li>
                <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Edit Operator</h3>
            </li>
        </ul>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
    
    </div>
</div>

<div class="row">
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                @if(session('success'))
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-success alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                {{ session('success') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <form action="{{ route('Operator.update', $Operator->id) }}" method="post">
                                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                  
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                            <div class="qout-h">
                                                <div class="col-md-12 bor-bo">
                                                    <h1>Edit Operator</h1>
                                                </div>
                                                
                                                <div class="col-md-12 padt pos-r">
                                                    <div class="row">
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Name</label>
                                                                <div class="col-sm-8">
                                                                    <input name="name" id="name" value="{{ old('name', $Operator->name) }}" class="form-control" type="text" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Department</label>
                                                                <div class="col-sm-8">
                                                                    @php
                                                                        $selectedDepartmentIds = array_map('intval', (array) old('department_id', $operatorDepartmentIds));
                                                                    @endphp
                                                                    <select name="department_id[]" id="department_id" class="form-control select2" multiple required>
                                                                        @foreach($departments as $department)
                                                                            <option value="{{ $department->id }}" {{ in_array((int) $department->id, $selectedDepartmentIds, true) ? 'selected' : '' }}>
                                                                                {{ $department->department_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small class="text-muted">Multiple departments select kar sakte hain.</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><br><br>

                                                    <div class="row">
                                                        <div class="col-md-4 padtb text-right">
                                                            <div class="col-md-9"></div>
                                                            <div class="col-md-3 my-lab" style="display: flex;">
                                                                <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                                <a href="{{ route('Operator.cancel') }}" class="btnn btn-secondary">Cancel</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function () {
        var selectedDepartmentIds = @json($selectedDepartmentIds);
        $('#department_id').select2({
            placeholder: 'Select Department(s)'
        });
        if (selectedDepartmentIds.length) {
            $('#department_id').val(selectedDepartmentIds).trigger('change');
        }
    });
</script>
@endsection
