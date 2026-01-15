@extends('layouts.default')

@section('content')
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Raw Material</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
          <ul class="cus-ul2">
                <li>
                    <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                </li>
                {{-- <li>
                    <input type="text" class="fomn1" placeholder="Search Anything" >
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul> 
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form action="" method="post">
                                    <input type="hidden" name="_token">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class="row qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>Material Requisition View</h1>
                                            </div>
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label class="control-label">Work Order No</label>    
                                                        </div>    
                                                        <div class="col-sm-8">
                                                            <label class="control-label">1001539</label>  
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label class="control-label">Date</label>    
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <label class="control-label">11-13-2023</label>    
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label class="control-label">Cable Size</label>    
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <label class="control-label">HDPE PIPE 57 MM (3.5MM)</label>     
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label class="control-label">Batch Qty</label>    
                                                        </div>    
                                                        <div class="col-sm-8">
                                                            <label class="control-label">50.00</label>     
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-4">
                                                    
                                                </div>
                                            </div>


                                            <div class="col-md-12 padt">
                                                <div class="col-md-12 padt">
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Product Name</th>
                                                                <th>Qty Required</th>
                                                            </tr>
                                                            <tbody>
                                                                <tr>
                                                                    <td>001</td>
                                                                    <td>C/C - M/B PIGMENT-WHITE (CLARIANT)dummy</td>
                                                                    <td>2.350 KGS.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>001</td>
                                                                    <td>C/C - M/B PIGMENT-WHITE (CLARIANT)dummy</td>
                                                                    <td>2.350 KGS.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>001</td>
                                                                    <td>C/C - M/B PIGMENT-WHITE (CLARIANT)dummy</td>
                                                                    <td>2.350 KGS.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>001</td>
                                                                    <td>C/C - M/B PIGMENT-WHITE (CLARIANT)dummy</td>
                                                                    <td>2.350 KGS.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>001</td>
                                                                    <td>C/C - M/B PIGMENT-WHITE (CLARIANT)dummy</td>
                                                                    <td>2.350 KGS.</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 padtb">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <label for="">
                                                        Total Quantity 
                                                    </label>
                                                    <input type="text" readonly value="28,502.350" name="grand_total" id="grand_total" class="form-control">
                                                
                                                    <div class="text-center mh3">
                                                        <h3>Production Manager</h3>
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
    </div>

   

@endsection