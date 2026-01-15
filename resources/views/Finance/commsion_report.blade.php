
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">

                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Commision Report</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>


                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Account Head</label>
                                    <input type="hidden" readonly name="selectAccountHeadId" id="selectAccountHeadId" class="form-control" value="">
                                    <input list="selectAccountHead" name="selectAccountHead" id="selectAccountHeadTwo" class="form-control clearable">
                                    <?php echo CommonHelper::accountHeadSelectList($m);?>
                                </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('assets/custom/js/customFinanceFunction.js') }}"></script>
@endsection