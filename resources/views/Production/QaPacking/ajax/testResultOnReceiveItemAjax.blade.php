
<?php
use App\Helpers\CommonHelper;
$count = 1;
?>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="dp_sdw2">
                    <div class="row" id="printReport">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                                
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right hidden-print">
                                            <h1><?php CommonHelper::displayPrintButtonInView('printReport','','1');?></h1>
                                        </div>
                                    </div>

                                    <div class="contra">
                                        <div class="row">
                                            
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                <div class="">
                                                  <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?> 
  
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <div class="contr2">
                                                    <div class="con_rew3" style="text-align: center;">
                                                        <h2>ISSUE: 1 </h2>
                                                        <h2>PAGE: 1/1</h2>
                                                        <h2>QC/FM/17</h2>
                                                    </div>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="bro_src">
                                        <div class="row" id="printReport">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="secc">
                                                    <hr style="border:1px solid #000">
                                                        <h2>INTERNAL TESTING REPORT</h2>
                                                    <hr style="border:1px solid #000">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="ode">
                                      
                                            </div>
                                            <div class="sal">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
                                                        <div class="ordeno">
                                                            <h2>Customer:</h2>
                                                            <h2>Sale Order no:</h2>
                                                            <h2>P.O No. / Contract No:</h2>
                                                            <h2>Item under Test:</h2>
                                                            <h2>Date:</h2>
                                                            <h2>Pipe Sample No:</h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-7">
                                                        <div class="ors_pra">
                                                            <p>{{$mainData->customer_name}}</p>
                                                            <p>{{$mainData->so_no}}</p>
                                                            <p>{{$mainData->purchase_order_no}}</p>
                                                            <p>{{$mainData->qc_by}}</p>
                                                            <p>
                                                                {{date("d-M-Y", strtotime($mainData->qc_packing_date))}}
                                                            </p>
                                                            <p>{{$mainData->bundle_no}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="tasetb">
                                                <table class="userlittab3 table table-bordered sf-table-list3">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">S.No.</th>
                                                        <th class="text-center">Item Description</th>
                                                        <th class="wsale2 text-center">Required Values</th>
                                                        <th class="text-center">Test Observations</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="data">
                                                        <tr>
                                                            <th colspan="4" class="">Mechanical Characteristic</th>
                                                        </tr>
                                                        @foreach($mechanicaltest as $key => $value)
                                                        </tr>
                                                            <td class="text-center">
                                                                {{$count ++}}
                                                            </td>
                                                            <td class="text-center">
                                                                {{$value->name}}
                                                            </td>
                                                            <td class="text-center">
                                                                {{$value->test_value}}
                                                            </td>
                                                            <td class="text-center">
                                                                {{$value->test_result}}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        
                                                        @php $count = 1; @endphp
                                                        <tr>
                                                            <th colspan="4" class="">Physical Characteristic</th>
                                                        </tr>
                                                        @foreach($physicaltest as $key => $value)
                                                        </tr>
                                                            <td class="text-center">
                                                                {{$count ++}}
                                                            </td>
                                                            <td class="text-center">
                                                                {{$value->name}}
                                                            </td>
                                                            <td class="text-center">
                                                                {{$value->test_value}}
                                                            </td>
                                                            <td class="text-center">
                                                                {{$value->test_result}}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="contra">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="">
                                                    <h2>FOR Zahabiya Chemicals INDUSTRIES (PVT) LIMITED </h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <div class="con_rewB">
                                                   

                                                </div>    
                                            </div>
                                        </div>
                                    </div>


                                

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
