

<?php $data=DB::Connection('mysql2')->table('import_po')->where('status',1)->get(); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 old">

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label for="">Vendor</label>
            <select name="SupplierId" id="SupplierId" class="form-control select2" style="width: 100%" onchange="get_import_docs()">
                <option>Select</option>
                <?php foreach($data as $row):
                $vendor=\App\Helpers\CommonHelper::get_supplier_name($row->vendor);
                ?>
                <option value="{{$row->vendor}}">{{$vendor}}</option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label for="">IGM No</label>
            <select  style="width: 100%"  onchange="get_data()" class="form-control select2" id="voucher_no">

            </select>
            <span id="Loader"></span>
        </div>


    </div>
    <div class="lineHeight">&nbsp;</div>
    <div style="text-align: center" class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <span class="data_to_be_append"></span>

        </div>
    </div>

</div>

<script>

    function get_data()
    {
        var value=$('#voucher_no').val();
        $('.data_to_be_append').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            url:'{{url('/sdc/get_import_data')}}',
            data:{value:value},
            type:'GET',
            success:function(response)
            {

                $('.data_to_be_append').html(response);

            }
        })
    }


    function get_import_docs()
    {
        var SupplierId=$('#SupplierId').val();
        $('#Loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            url:'{{url('/sdc/get_import_docs')}}',
            data:{SupplierId:SupplierId},
            type:'GET',
            success:function(response)
            {
                $('#voucher_no').html(response);
                $('#Loader').html('');
            }
        })
    }
</script>