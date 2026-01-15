<table class="table table-bordered table-responsive">
    <thead>

    <th class="text-center">S.No</th>
    <th class="text-center">Select</th>
    <th class="text-center">Item Description</th>
    <th class="text-center">Item Code</th>


    </thead>

    <tbody>

    <?php
    $count=1;
    foreach ($data as $row): ?>
    <tr>
        <td> <?php echo $count++; ?> </td>
        <td> <input value="{{$row->id}}" @if($param==0) id="get_item{{$count}}" onclick="get_items_val(this.id,'{{$count}}')" @else id="bget_item{{$count}}" onclick="b_get_items_val(this.id,'{{$count}}')" @endif  type="radio" name="get_items" > </td>

        <td @if($param==0) id="des{{$count}}" @else id="bdes{{$count}}" @endif> <?php echo $row->description ?> </td>
        <td> <?php echo $row->item_code ?> </td>
    </tr>

    <?php    endforeach; ?>
    </tbody>
</table>