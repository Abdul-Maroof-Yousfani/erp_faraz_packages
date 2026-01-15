
<?php use App\Helpers\CommonHelper;

?>
<?php use App\Helpers\PurchaseHelper; ?>
@extends('layouts.default')
@section('content')
    @include('select2')
    @include('modal')

    <style>
        element.style {
            width: 183px;
        }
    </style>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">




        <div class="row">

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group ">
                    <label for="email">Item</label>
                    <input class="form-control sam_jass" readonly type="text" name="item" id="item_1" />

                    <input readonly type="hidden" name="item" id="sub_1" />
                </div>
            </div>




            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div>&nbsp;</div>
                <button type="button" class="btn btn-success" onclick="BookDayList();">Submit</button>
            </div>

        </div>

    </div>
    <!-- On rows -->
    <table class="table table-sm">
        <thead>
        <tr>
            <th scope="col">Voucher Type</th>
            <th scope="col">Voucher No</th>
            <th scope="col">WareHouse</th>
            <th scope="col">Voucher Date</th>
            <th scope="col">Qty</th>
            <th scope="col">Amount</th>
            <th scope="col">Unit Price</th>
            <th scope="col">Balance Qty</th>
            <th scope="col">Balance Amount</th>
            <th scope="col">Average Cost</th>

        </tr>
        </thead>
        <tbody>
        <?php $data=DB::Connection('mysql2')->table('stock')->where('status',1)->where('sub_item_id',Input::get('item'))->orderBy('voucher_date','ASC')->get();  ?>
        <?php $total_amount=0;
              $total_qty=0;
             $average_cost=0;

                $actual_qty=0;
                $actual_amount=0;

        ?>
        @foreach($data as $row)
         <?php

         if ($row->qty>0):
         $voucher_type='';






         if ($row->voucher_type==1):
             $voucher_type='GRN';
             $amount= $row->amount;
             $total_amount+=$amount;
             $total_qty+=$row->qty;
         elseif($row->voucher_type==5):

             $amount=$average_cost;
             $total_qty-=$row->qty;
             // $total_qty.'*'.$average_cost;
             $amount=$total_qty*$average_cost;
             $total_amount=$amount;
         elseif($row->voucher_type==2):
             $amount= $row->amount;
             $total_amount-=$amount;
             $total_qty-=$row->qty;
         endif;

                 if ($row->voucher_type!=5):
                  $average_cost=$total_amount/$total_qty;
                 endif;
         ?>
        <tr @if($row->voucher_type==1) class="table-primary" @endif>
        <td>{{CommonHelper::get_voucher_type($row->voucher_type,$row->opening)}}</td>
            <td>{{$row->voucher_no}}</td>
        <td>{{CommonHelper::get_name_warehouse($row->warehouse_id)}}</td>
        <td>{{$row->voucher_date}}</td>
        <td>{{$row->qty}}</td>
        <td>{{number_format($amount,2)}}</td>
        <td>{{$row->amount/$row->qty}}</td>
        <td>{{$total_qty}}</td>
        <td>{{number_format($total_amount,2)}}</td>
        <td>{{number_format($average_cost,2)}}</td>
        <?php $actual_amount+=$row->amount; $actual_qty+=$row->qty ?>
        </tr>
        <?php endif; ?>    @endforeach
       <tr>
           <td colspan="3">Total</td>
           <td>{{number_format($actual_qty,2)}}</td>
           <td>{{number_format($actual_amount,2)}}</td>
       </tr>
        </tbody>
    </table>





    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function subItemListLoadDepandentCategoryId(id,value) {
            //alert(id+' --- '+value);
            var arr = id.split('_');
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/subItemListLoadDepandentCategoryId',
                type: "GET",
                data: { id:id,m:m,value:value},
                success:function(data) {
                    $('#sub_item_id_'+arr[2]+'_'+arr[3]+'').html(data);
                }
            });
        }

    </script>


    <script>



        $('.sam_jass').bind("enterKey",function(e){


            $('#items').modal('show');
            e.preventDefault();

        });
        $('.sam_jass').keyup(function(e){
            if(e.keyCode == 13)
            {
                selected_id=this.id;
                $(this).trigger("enterKey");
                e.preventDefault();

            }

        });




    </script>
@endsection