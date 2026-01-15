<?php use App\Helpers\CommonHelper; ?>
<div class="modal-body row">


    <div class="row" id="bundle_table">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



            <div class="table-responsive" >


                <h3 style="text-align: center;font-family: cursive"><u>Location Wise Data</u></h3>


                <table class="table table-bordered" id="">
                    <thead>
                    <tr>
                        <th class="text-center" style="">SR No</th>
                        <th style="" class="text-center" >Year<span class="rflabelsteric"><strong>*</strong></span></th>
                        <th style="" class="text-center" > Closing Stock<span class="rflabelsteric"><strong>*</strong></span></th>
                        <th class="text-center">Closing Value<span class="rflabelsteric"><strong>*</strong></span></th>
                        <th class="text-center">Batch Number<span class="rflabelsteric"><strong>*</strong></span></th>

                    </tr>
                    </thead>
                    <tbody id="append_bundle">
                    <?php $counter=1; ?>
                    @if ($stock->isEmpty())

                    @foreach(CommonHelper::get_all_warehouse() as $row)
                        <tr>
                            <td>{{$counter++}}</td>
                            <input type="hidden" name="warehouse[]" value="{{$row->id}}"/>
                            <td class="text-center">{{$row->name}}</td>
                            <td><input step="any" type="number" class="form-control requiredField" value="0" name="closing_stock[]" id="closing_stock{{$counter}}" /> </td>
                            <td><input step="any" type="number" class="form-control requiredField" value="0" name="closing_val[]" id="closing_val{{$counter}}" /> </td>
                            <td><input type="text" class="form-control requiredField" value="0" name="batch_code[]" id="batch_code{{$counter}}" /> </td>
                        </tr>
                    @endforeach
                        @else

                        @foreach($stock as $row1)

                            <tr>
                                <td>{{$counter++}}</td>
                                <input type="hidden" name="warehouse[]" value="{{$row1->warehouse_id}}"/>
                                <td class="text-center">{{CommonHelper::get_name_warehouse($row1->warehouse_id)}}</td>
                                <td><input step="any" type="number" class="form-control requiredField" value="{{$row1->qty}}" name="closing_stock[]"  id="closing_stock{{$counter}}" /> </td>
                                <td><input step="any" type="number" class="form-control requiredField" value="{{$row1->amount}}" name="closing_val[]" id="closing_val{{$counter}}" /> </td>
                                <td><input type="text" value="{{$row1->batch_code}}" class="form-control requiredField" value="" name="batch_code[]" id="batch_code{{$counter}}" /> </td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>

                    <tbody>
                    <tr  style="font-size:large;font-weight: bold">
                        <td class="text-center" colspan="2">Total</td>
                        <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_qty"/> </td>
                        <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_rate"/> </td>


                    </tr>
                    </tbody>

                </table>



            </div>


            {{-- <div class="table-responsive" >


                                <h3 style="text-align: center;font-family: cursive"><u>Year Wise Data</u></h3>


                <table class="table table-bordered" id="">
                    <thead>
                    <tr>
                        <th class="text-center" style="">SR No</th>
                        <th style="" class="text-center" >Year<span class="rflabelsteric"><strong>*</strong></span></th>
                        <th style="" class="text-center" > Sales Qty.<span class="rflabelsteric"><strong>*</strong></span></th>
                        <th class="text-center">Purchase Qty<span class="rflabelsteric"><strong>*</strong></span></th>

                    </tr>
                    </thead>
                    <tbody id="append_bundle">
                    @if ($yearwise_ope->isEmpty())
                    @php $year=2015; @endphp
                    @for($i=1; $i<=7; $i++)
                        <tr>
                            <td>{{$i}}</td>

                            <td class="text-center">{{$year}}</td>

                            <input type="hidden" name="year[]" value="{{$year}}"/>
                            <td><input type="number" class="form-control" value="0" name="sales_qty[]" /> </td>
                            <td><input type="number" class="form-control" value="0" name="purchase_qty[]" /> </td>
                       </tr>
                        @php $year++ @endphp
                     @endfor
                    @else
                    @php $counter=1; @endphp
                        @foreach($yearwise_ope as $row1)
                    <tr>
                        <td>{{$counter++}}</td>

                        <td class="text-center">{{$row1->year}}</td>

                        <input type="hidden" name="year[]" value="{{$row1->year}}"/>
                        <td><input type="number" class="form-control" value="{{$row1->sales_qty}}" name="sales_qty[]" /> </td>
                        <td><input type="number" class="form-control" value="{{$row1->purchase_qty}}" name="purchase_qty[]" /> </td>
                    </tr>

                        @endforeach
                        @endif
                    </tbody>

                    <tbody>
                    <tr  style="font-size:large;font-weight: bold">
                        <td class="text-center" colspan="2">Total</td>
                        <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_qty"/> </td>
                        <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_rate"/> </td>


                    </tr>
                    </tbody>

                </table>



            </div> --}}
        </div>
    </div>

    @if (Session::get('run_company')==1 || Session::get('run_company')==1)
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

        {{ Form::submit('Submit', ['class' => 'btn sa']) }}
    </div>
        @endif
</div>


<script>
    $(document).ready(function() {
        $(".sa").click(function(e){

            var category = new Array();
            var val;
            //$("input[name='chartofaccountSection[]']").each(function(){
            category.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of category) {

                jqueryValidationCustom();

                if(validate == 0){
                    //return false;
                }else{
                    return false;
                }
            }
        });
    });
</script>
