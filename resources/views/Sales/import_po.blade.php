

<?php $data=DB::Connection('mysql2')->table('import_po')->where('status',1)->get(); ?>
<div style="display: none" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 old">

            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                    <label for="">Inserted Data</label>
                    <select  style="width: 100%"  onchange="get_data()" class="form-control select2" id="voucher_no">
                        <option>Select</option>
                        <?php foreach($data as $row):
                            $vendor=\App\Helpers\CommonHelper::get_supplier_name($row->vendor);
                            ?>
                  <option value="{{$row->id}}">{{$row->voucher_no .'('.$vendor.')'}}</option>
                        <?php endforeach ?>
                    </select>
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
</script>