
<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;

?>
<style>
    .modalWidth{
        width: 100%;
    }
</style>

    @include('modal')
    <?php

    ?>

    <div class="">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive well">
                    <input type="hidden" name="plan_id" id="plan_id" value="{{ $planId }}">
                    <select name="work_station_name" id="work_station_name" class="form-control select2">
                        <option value="">Select work station</option>
                        @foreach ($workName as $item)
                            <option value="{{ $item->id }}">{{ $item->work_station_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="renderResponse">

                </div>
            </div>        
    </div>

    <script>
        $('#work_station_name').on('change', function(){
            var plan_id = $('#plan_id').val();
            var data = {work_station: $(this).val(),plan_id:plan_id};
            var uri = '{{url('/production/create_make_product_issue_items?m='. Session::get('run_company'))}}';
            $.ajax({
                   url:uri,
                   data:data,
                   type:'GET',
                   success:function(response)
                   {
                       $('#renderResponse').html(response);
                   }
               });
        });
        function get_detail(id,number)
        {
            //alert(number); return false;


           if (number==1)
           {
               var location_id = $('#location_id'+number).val();
               $('.location').val(location_id);

               $('.location').each(function(i, obj) {
                var   number=$(this).attr('id');
                 number=number.replace("location_id", "");


                   var item=$('#item_id'+number).val();
                   var location_id = $('#location_id'+number).val();
                   if (location_id=='')
                   {
                       $('#batch_code'+number).val('');
                       $('#instock_'+number).val(0);

                   }
                   else
                   {
                       $.ajax({
                           url:'{{url('/pdc/get_batch_code')}}',
                           data:{item:item,location_id:location_id},
                           type:'GET',
                           success:function(response)
                           {
                               //var data=response.split(',');
                               $('#batch_code'+number).html(response);
                               issued_qty(number);
                           }
                       });
                   }

               });
           }
        else
           {
               var item=$('#'+id).val();
               var location_id = $('#location_id'+number).val();
               $.ajax({
                   url:'{{url('/pdc/get_batch_code')}}',
                   data:{item:item,location_id:location_id},
                   type:'GET',
                   success:function(response)
                   {
                       //var data=response.split(',');
                       $('#batch_code'+number).html(response);
                       issued_qty(number);
                   }
               });
           }


        }

        function get_stock_qty(warehouse,number)
        {


            var warehouse=$('#location_id'+number).val();
            var item=$('#item_id'+number).val();
            var batch_code=$('#batch_code'+number).val();



            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
                type: "GET",
                data: {warehouse:warehouse,item:item},
                success:function(data)
                {

                    data=data.split('/');
                    $('#instock_'+number).val(data[0]);

                    if (data[0]==0)
                    {
                        $("#"+item).css("background-color", "red");
                    }
                    else
                    {
                        $("#"+item).css("background-color", "");
                    }
                    issued_qty(number);
                }
            });

        }

        function issued_qty(number)
        {
          var issue_qty=parseFloat($('#issu_qty'+number).val());
          issue_qty= checkNan(issue_qty);
          var avaiable=parseFloat($('#instock_'+number).val());
           console.log(avaiable);
           avaiable=checkNan(avaiable);
            if (issue_qty >avaiable)
            {
                $('#btn'+number).prop("disabled", true);
                $('#issu_qty'+number).css("background-color",'red');
            }
            else
            {
                $('#btn'+number).prop("disabled", false);
                $('#issu_qty'+number).css("background-color",'');
            }
        }

        function save(number)
        {
            $('#btn'+number).prop("disabled", true);
            var main_id=$('#main_id'+number).val();
           
            var master_id=$('#master_id'+number).val();
            var type=$('#type'+number).val();
            var bom_data_id=$('#bom_data_id'+number).val();
            var issue_qty=$('#issu_qty'+number).val();
            var location_id=$('#location_id'+number).val();
            var batch_code=$('#batch_code'+number).val();
            var item_id=$('#item_id'+number).val();
            var request_qty=$('#req_qty'+number).val();
            $.ajax({
                url:'{{url('/production/save_issue_material')}}',
                data:{main_id:main_id,master_id:master_id,type:type,bom_data_id:bom_data_id,issue_qty:issue_qty,location_id:location_id,batch_code:batch_code,item_id:item_id,request_qty:request_qty},
                type:'GET',
                success:function(response)
                {
                    $('#btn'+number).css("display", 'none');
                    $('#td'+number).html('&#9989;');
                  //  alert('successfully Saved');


                },
                error: function() {
                    $('#td'+number).html('&#x2716;');
                }

            });
        }
    </script>
