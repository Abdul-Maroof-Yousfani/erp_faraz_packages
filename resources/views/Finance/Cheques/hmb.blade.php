@extends('layouts.default')


<style type="text/css">
 .container-fluid.head-sh{display:none;}
#mySidenav{display:none;}
.pos-rel{position:relative;width:800px;height:352px;}
.cheq-img img{width:800px;height:352px;}
.date{position:absolute;top:56px;right:47px;font-size:21px;letter-spacing:4px;}
.pay{position:absolute;top:108px;left:74px;font-size:17px;font-weight:600;}
.wordrupees{position:absolute;top:137px;left:106px;font-size:17px;font-weight:600;}
.numericrupees{position:absolute;top:165px;right:91px;font-size:17px;font-weight:600;}
.rotate{}
.cross-img{position:absolute;width:15%;top:-19px;left:-13px;}
.cross-imgs img{width:800px !important;height:352px !important;}




/* .dp_sdw.cheque_body{transform:rotate(90deg) !important;margin-top:200px !important ;} */
         @media print {
            /* .row.cheque_body{transform:rotate(90deg) !important;} */
         }
</style>   

<div class="but_1 text_left">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <span class="subHeadingLabelClass">Cheques View</span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
            @if($type != 'cash')
                    <button class="btn btn-primary" onclick="writeName()" style="">
                        <span class="glyphicon glyphicon-print"></span> &nbsp; Change Name
                    </button>
                @endif
                <button class="btn btn-primary" onclick="printView('PrintPanel','','1')" style="">
                    <span class="glyphicon glyphicon-print"></span> &nbsp; Print
                </button>
                
            </div>
        </div>
    </div>
</div>


<div class="well_N" id="PrintPanel">
    <div class="dp_sdw cheque_body">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pos-rel">
                                        @if($type == 'cross')                                    

                                            <div class="cross-img">
                                                <img src="{{ url('public/assets/img/cross.png') }}" onerror="this.onerror=null;this.src='{{ asset('assets/img/cross.png') }}'" />
                                            </div>
                                        @endif

                                            <div class="cheq-img">
                                            <!-- <img src="{{ URL::asset('assets/img/cheques/hmb.jpg') }}"> -->
                                            </div>
                                            <div class="rotate">

                                                <div class="date">{{date('dmY', strtotime($date))}}</div>
                                                <!-- <div class="date">2 5 0 5 2 0 2 4</div> -->

                                                <div class="pay">
                                                    @if($type == 'cash')                                    
                                                        Cash
                                                    @else    
                                                        {{ $to }}
                                                    @endif
                                                </div>
                                                <div class="wordrupees">{{ $amount_word }} only</div>
                                                <div class="numericrupees">{{ $amount }}</div>
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


<script>
var date_tt  = document.querySelector('.date').innerText
var date_new_tt="" ;

for (let index = 0; index < date_tt.length; index++) {

    date_new_tt += date_tt[index];
    if(index < date_tt.length)
    {
        date_new_tt += " ";
    }


}

document.querySelector('.date').innerText = date_new_tt
function writeName()
{
    let name = prompt("Please enter  name");

    
    (name) ? document.querySelector('.pay').innerText = name : '';
}
</script>
