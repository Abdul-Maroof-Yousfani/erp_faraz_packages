@extends('layouts.default')

@section('content')

<style>
 .compmir h5{color:#91989F;font-family:Poppins;font-size:16px;font-style:normal;font-weight:500;line-height:normal;}
.cpal h5{color:#91989F;font-family:Poppins;font-size:14px;font-style:normal;font-weight:400;line-height:normal;}
.chek_met{display:flex;gap:60px;}
.ye{display:flex;gap:14px;}
.nos{display:flex;gap:14px;}
.newseca h2{text-align:center;color:#fff;border-radius:5px;background:var(--Dark-Graydient,linear-gradient(180deg,#18283A 0%,#413C74 100%));padding:13px 20px;margin:0px 45%;}
.compmir2 h5{color:#1E1E1E;font-family:Poppins;font-size:14px;font-style:normal;font-weight:400;line-height:normal;}
.row.br_line{border-top:1px solid #ddd;padding:10px 0px;}
</style>


    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Sale Order List</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <ul class="cus-ul2">
                <li>
                    <a href="{{route('createSaleOrder')}}" class="btn btn-primary"  >Create New Sale Order</a>
                </li>
                <li>
                    <input type="text" class="fomn1" onkeypress="viewRangeWiseDataFilter()" id="search" placeholder="Search Anything" >
                </li>
                {{-- <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="headquid newseca">
                                        <h2 class="subHeadingLabelClass">Section - B </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <div class=" compmir">
                                            <h5>to be completed by (1) Managing Director</h5>
                                            </div>
                                        </div> 
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"></div> 
                                    </div>

                                    <div class="row">

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <div class="cpal">
                                                <h5>Capable of Meeting Sepcified requirements ?</h5> 
                                            </div>
                                        </div> 
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="chek_met">
                                                <div class="ye">
                                                    <label for="">Yes</label>
                                                    <input class="form-control" type="checkbox" name="capable_requirement" id="">
                                                </div>
                                                <div class="nos">   
                                                    <label for="">no</label>
                                                    <input class="form-control" type="checkbox" name="capable_requirement" id="">
                                                </div>
                                            </div>
                                        </div>  

                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <div class="cpal">
                                                <h5>Any Special problem (s) ?</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="chek_met">
                                                <div class="ye">
                                                    <label for="">Yes</label>
                                                    <input class="form-control" type="checkbox" name="sepecial_problem" id="">
                                                </div>
                                                <div class="nos">
                                                    <label for="">no</label>
                                                    <input class="form-control" type="checkbox" name="sepecial_problem" id="">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <div class=" compmir">
                                                <h5>Date</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <input type="date" class="form-control" name="" id="">
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>       
                    </div>


                    {{-- Section C---}}

                    <div class="row" >
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="headquid newseca">
                                        <h2 class="subHeadingLabelClass">Section - C </h2>
                                    </div>
                                </div>
                            </div>
                    

                            <div class="row">
                           
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class=" compmir">
                                        <h5>Zahabiya Chemicalss</h5>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"></div>
                            </div>
                       

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class=" compmir2">
                                        <h5>To Be completed by the concerned Director Marketing</h5>
                                    </div>
                                </div>
                            </div>


                            <div class="row br_line">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class=" compmir2">
                                        <h5>1. Decision On the Sasis of answer given in Section-B, it is decided to:</h5>
                                    </div>
                                </div>
    
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="chek_met">
                                        <label for="">Accept the order</label>
                                        <input type="checkbox" name="" id="">
                                        <label for="">Reject the order</label>
                                        <input type="checkbox" name="" id="">
                                        <label for="">Negotiate Amendment</label>
                                        <input type="checkbox">
                                    </div>
                                </div>
                            </div>

                            <div class="row br_line">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class="compmir2">
                                        <h5>2.Confirmation send </h5>
                                    </div>
                                </div>

                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="chek_met">
                                        <div class="ye">
                                            <label for="">Yes</label>
                                            <input class="form-control" type="checkbox" name="" id="">
                                        </div>

                                        <div class="nos">
                                            <label for="">no</label>
                                            <input class="form-control" type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row br_line">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <div class=" compmir2">
                                        <h5>3.Obtain Customer's Agrrement </h5>
                                    </div>
                                </div>

                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="chek_met">
                                        <div class="ye">
                                            <label for="">Yes</label>
                                            <input class="form-control" type="checkbox" name="" id="">
                                        </div>
                                        <div class="nos">
                                            <label for="">no</label>
                                            <input class="form-control" type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row br_line">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">

                                    <div class=" compmir2">
                                        <h5>3.Amendment required after acceptance of an order :</h5>
                                    </div>
                                </div>

                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="chek_met">
                                        <div class="ye">

                                            <label for="">Yes</label>
                                            <input class="form-control" type="checkbox" name="" id="">
                                        </div>
                                        <div class="nos">
                                            <label for="">no</label>
                                            <input class="form-control" type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row br_line">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class=" compmir2">
                                        <h5>**Please fill new contract review from and send it to managining Directort .</h5>
                                    </div>
                                    <div class=" compmir2">   
                                        <h5>This is a computer generated document and does not require any signature.</h5>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection