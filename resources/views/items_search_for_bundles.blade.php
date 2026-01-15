<div  class="modal fade"  id="items_searc_for_bundless" role="dialog" class="modal hide" data-backdrop="static" data-keyboard="false">
    <div style="width: 80%" class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Search Items</h2>
            </div>
            <div class="modal-body row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p><input type="" class="form-control enter" id="b_searc_items"> </p>

                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <input  onclick="b_items_search()" value="search" type="button" class="btn btn-primary" />
                </div>
            </div>

            <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <span class="" id="b_dataa">

                </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

</div>

<script>
    var selected_idd=0;
    function b_items_search()
    {

        var item_id=$('#b_searc_items').val();
        $('#b_dataa').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            url:'{{url('/pdc/search?param=1')}}',
            data:{item_id:item_id},
            type:'GET',
            success:function(response)
            {
                $('#b_dataa').html(response);
            }
        })
    }



    $('#items_searc_for_bundless').on('shown.bs.modal', function (e) {
        $('#b_searc_items').focus();
    })

    function b_get_items_val(id,number)
    {


        if ($("#"+id).prop("checked"))
        {
            var item_id=  $('#'+id).val();
            var des=  $('#bdes'+number).text();


            $('#'+selected_idd).val(des);
            var sub_id=selected_idd.split('_');
            $('#bsub_'+sub_id[1]).val(item_id);
            $('#items_searc_for_bundless').modal('hide');



            if (typeof b_get_detail === "function") {

                b_get_detail('bsub_'+sub_id[1]+'',sub_id[1]);
            }



        }
    }


    function b_get_detail(id,number)
    {


        var item=$('#'+id).val();


        $.ajax({
            url:'{{url('/pdc/get_data')}}',
            data:{item:item},
            type:'GET',
            success:function(response)
            {

                var data=response.split(',');
                $('#buom_id'+number).val(data[0]);


            }
        })



    }
    $('.enter').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            b_items_search();
        }
    });
</script>

