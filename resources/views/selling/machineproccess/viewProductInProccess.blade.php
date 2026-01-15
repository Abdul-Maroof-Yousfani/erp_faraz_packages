@php 
    $count = 1;
    use App\Helpers\CommonHelper;

@endphp
@foreach($machine_process as $machine_proces)
    <tr class="main">
        <td class="text-center">{{ $count++ }}</td>
        {{-- <td class="text-center">{{$machine_proces->machine_no}}</td> --}}
        <td class="text-center">{{$machine_proces->order_no}}</td>
        <!-- <td class="text-center">{{$machine_proces->sale_order_no}}</td> -->
        <td class="text-center">{{$machine_proces->item_code}}</td>

        {{-- <td class="text-center">-</td> --}}
        <td class="text-center">{{$machine_proces->finish_good_qty}}</td>
        <!-- <td class="text-center">{{$machine_proces->received_qty}}</td> -->
        <td class="text-center">{{ CommonHelper::changeDateFormat($machine_proces->machine_process_date) }}</td>
        @if($machine_proces->finish_good_qty != $machine_proces->received_qty)
            <td class="text-center">Ongoing</td>
        @else
            <td class="text-center">Complete</td>
        @endif
        <td class="text-center hidden-print printListBtn">
            @if($machine_proces->finish_good_qty != $machine_proces->received_qty)
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown"
                        aria-expanded="false">Receive</button>
                    <div class="dropdown-menu" style=" ">
                        <form
                            style="padding: 10px;display: flex;justify-content: flex-start;etween;align-items: center;width:1200px"
                            class="printListBtn">
                            <div class="form-group" style="margin-left:10px">
                                <label for="exampleDropdownFormEmail1">Machine</label>
                                <select name="machine_id" id="machine_id" class="form-control" id="">
                                    <option value="">Select Machine</option>
                                    @foreach($Machine as $key => $value)
                                        <option value="{{ $value->id}}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-left:10px">
                                <label for="exampleDropdownFormEmail1">Operator</label>
                                <select name="operator_id" id="operator_id" class="form-control" id="">
                                    <option value="">Select Operator</option>
                                    @foreach($Operator as $key => $value)
                                        <option value="{{ $value->id}}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-left:10px">
                                <label for="exampleDropdownFormEmail1">Shift</label>
                                <select name="shift" id="shift" class="form-control" id="">
                                    <option value="">Select Shift</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-left:10px">
                                <input type="hidden" value="{{$machine_proces->id}}" name="machine_proccess_id"
                                    id="machine_proccess_id">
                                <label for="exampleDropdownFormEmail1">Qty</label>
                                <input type="text" placeholder="" class="form-control requiredField" name="received_length"
                                    id="received_length" value="">
                            </div>
                            <!-- <div class="form-group" style="margin-left:10px">
                                        <label for="exampleDropdownFormEmail1">Bundle</label>
                                        <input type="number" placeholder="" class="form-control requiredField" name="Bundle" id="Bundle"
                                            min="1" value="1">
                                    </div>
                                    <div class="form-group" style="margin-left:10px">
                                        <label for="exampleDropdownFormEmail1">Color Line</label>
                                        <input type="text" placeholder="" value="{{$machine_proces->color_line ?? '-'}}"
                                            class="form-control requiredField" name="color" id="color">
                                    </div> -->
                            <div class="form-group" style="margin-left:10px">
                                <label for="exampleDropdownFormEmail1">Remarks</label>
                                <input type="text" placeholder="" value="{{$machine_proces->remarks ?? '-'}}"
                                    class="form-control requiredField" name="remarks" id="remarks">
                            </div>
                            <input type="button" style="margin-left:10px" onclick="received(this)"
                                class="btn btn-xs btn-success" value="Submit">
                        </form>
                    </div>
            @endif
            </div>

        </td>
    </tr>

@endforeach
<script>

    function received(data) {

        // Get the form element by ID
        var machine_proccess_id = $(data).closest('.main').find('#machine_proccess_id').val();
        var Bundle = $(data).closest('.main').find('#Bundle').val();
        var machine_id = $(data).closest('.main').find('#machine_id').val();
        var operator_id = $(data).closest('.main').find('#operator_id').val();
        var shift = $(data).closest('.main').find('#shift').val();
        var remarks = $(data).closest('.main').find('#remarks').val();
        var color = $(data).closest('.main').find('#color').val();
        var received_length = $(data).closest('.main').find('#received_length').val();

        var form;
        var formData = new FormData(form);

        formData.append("machine_proccess_id", machine_proccess_id);
        formData.append("received_length", received_length);
        formData.append("remarks", remarks);
        formData.append("machine_id", machine_id);
        formData.append("operator_id", operator_id);
        formData.append("shift", shift);
        formData.append("color", color);
        formData.append("Bundle", Bundle);


        $.ajax({
            type: "POST",
            url: "{{route('received_length')}}",
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {
                // Handle the success response
                console.log(response);
                if (response == 1) {
                    //  $(data).remove();
                    viewProductInProccess();
                    viewProductProccessComplete();
                    alert('Received Successfully');
                }
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.error(error);
            }
        });
    }


    $('.dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });

</script>