@extends('layouts.default')

@section('content')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Inventory Master</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Create Mode Delivery</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
         
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                            <div class="headquid">
                           <h2 class="subHeadingLabelClass">Create Mode Delivery</h2>
                        </div>
                                <form action="{{route('storeModeDelivery')}}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="row qout-h">
                                            
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="col-sm-4 control-label">Sale Mode Delivery  <span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <div class="col-sm-8">
                                                      <input type="text" class="form-control" name="mode_delivery" id="">                                                    </div>
                                                    </div>  
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-4">
                                                    
                                                </div>
                                            </div>


                        
                                            <div class="col-md-12  text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                    <button type="button" class="btnn btn-secondary " data-dismiss="modal">Cancel</button>
                                                    
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
    </div>


@endsection