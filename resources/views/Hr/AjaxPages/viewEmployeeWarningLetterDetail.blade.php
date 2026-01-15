<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="row text-center"><h3><b>View Employee Warning Letter Detail</b></h3></div>
<div class="" id="OvertimeDetailListPrint">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list table-hover" id="OvertimeDetailList">
                    <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Emp Code</th>
                    <th class="text-center">Emp Name</th>
                    </thead>
                    <tbody>
                    <?php $counter = 1;?>
                    @if(!empty($warningLetters))
                        @foreach($warningLetters as $value)
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++  }}</span></td>
                                <td class="text-center">{{$value['emp_code']}}</td>
                                <td class="text-center">{{HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name',$value['emp_code'],'emp_code')}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center" style="color:red;"><b>Record Not Found !</b></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>