<div  class="modal fade" id="items" role="dialog" class="modal hide" data-backdrop="static" data-keyboard="false">
    <div style="width: 80%" class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Search Items</h2>
            </div>
            <div class="modal-body row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <p><input type="" class="form-control enter" id="searc_items"> </p>

                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <input  onclick="items_search()" value="search" type="button" class="btn btn-primary" />
                </div>
            </div>

            <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <span class="" id="dataa">

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
    var selected_id=0;
    function items_search()
    {
        var item_id=$('#searc_items').val();

        if (item_id=='')
        {
            return false;
        }
        $('#dataa').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            url:'{{url('/pdc/search')}}',
            data:{item_id:item_id},
            type:'GET',
            success:function(response)
            {
                $('#dataa').html(response);
            }
        })
    }



    $('#items').on('shown.bs.modal', function (e) {
        $('#searc_items').focus();
    })

    function get_items_val(id,number)
    {

        if ($("#"+id).prop("checked"))
        {
            var item_id=  $('#'+id).val();
            var des=  $('#des'+number).text();
            $('#'+selected_id).val(des);
            if(selected_id == 'master_sub_ic')
            {
                $('#get_master_sub_ic').val(item_id);
            }
            var sub_id=selected_id.split('_');
            $('#sub_'+sub_id[1]).val(item_id);
            $('#sub_'+sub_id[1]).val(item_id);
            $('#items').modal('hide');

            if (typeof get_detail === "function") {
                get_detail('sub_'+sub_id[1]+'',sub_id[1]);
            }

            if (typeof get_stock === "function") {

                get_stock('warehouse_from'+sub_id[1],sub_id[1]);
            }


        }
    }

    $('.enter').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            items_search();
        }
    });
</script>

