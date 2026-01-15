@extends('layouts.default')

@section('content')
@include('select2')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Inventory Master</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Sale Tax Group Create</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
         
        </div>
    </div>
    <?php 

    use App\Helpers\CommonHelper;
    ?>
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
                           <h2 class="subHeadingLabelClass">Create Sale Tax Group</h2>
                        </div>
                                <form action="{{route('storeSaleTaxGroup')}}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="row qout-h">
                                            
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-12">
                                                
                                                    <div class="col-sm-6">
                                                        <label for="">Account Head</label>
                                                        <select style="width: 100%" class="form-control requiredField select2" name="account_id" id="account_id">
                                                            <option value="0,0">Select Account</option>
                                                            @foreach(CommonHelper::get_all_account_operat() as $key => $y)
                                                                <option value="{{ $y->id.','.$y->type.','.$y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                            @endforeach
                                                        </select>   
                                                    </div>                                            
                                               
                                                    <div class="col-sm-6">
                                                        <label for=""> Rate</label>
                                                        <input type="text" class="form-control" name="rate" id="">                                                   
                                                      </div>  
                                                </div>
                                                
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
    <script>
        $(document).ready(function(){
    
            $('#account_id').select2();
        });
    </script>

@endsection
