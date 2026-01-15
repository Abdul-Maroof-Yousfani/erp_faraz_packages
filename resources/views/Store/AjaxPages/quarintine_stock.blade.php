
<?php use App\Helpers\CommonHelper; ?>

<div>
    <h3 style="text-align: center">Quarantine Stock</h2>
    <div class="table-responsive">
        <table id="data" class="table table-bordered ">
    


            <thead>
            <th class="text-center">S.No</th>
            <th class="text-center">Item</th>
            <th class="text-center">UOM</th>
            <th class="text-center">Stock</th>

            </thead>
            <tbody id="filterDemandVoucherList">
            <?php
            $counter=1;
            ?>
            @foreach($quarintine as $data)
            <tr class="text-center">
                <td>{{ $counter++ }}</td>
                <td>{{ $data->sub_ic }}</td>
                <td>{{ CommonHelper::get_uom_name($data->uom) }}</td>
                <td>{{ number_format($data->qty) }}</td>
            </tr>
            @endforeach

            </tbody>
        </table>

    </div>

</div>