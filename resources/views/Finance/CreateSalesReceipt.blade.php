<?php
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

?>

@extends('layouts.default')
@section('content')
    @include('select2')
    @include('number_formate')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Bank Receipt Voucher</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                            </div>
                        </div>
                        <hr style="border-color: #ccc">
                        {{ Form::open(['url' => 'fad/addSalesReceipt?m=' . $_GET['m'] . '', 'id' => 'createSalesOrder', 'class' => 'stop']) }}

                        <?php
                        $WhereIn = implode(',', $val);
                        $Colll = DB::Connection('mysql2')->select('select gi_no,buyers_id from sales_tax_invoice where id in(' . $WhereIn . ') group by buyers_id');
                        ?>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="email">Voucher Date</label>
                                <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="v_date" name="v_date">
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="pwd">Payment Mode</label>
                                <select id="pay_mode" name="pay_mode" onchange="hide_unhide()" class="form-control">
                                    <option value="1,1">Cheque</option>
                                    <option value="2,2">Cash </option>
                                    {{-- <option value="3,1">Online Transfer </option> --}}
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                                <label for="pwd"> Banks </label>
                                @php
                                    // $bank=DB::Connection('mysql2')->table('bank_detail')->get();
                                    $bank = CommonHelper::get_accounts_by_parent_code('1-2-1');
                                @endphp
                                <select name="bank" id="bank_id" class="form-control select2">
                                    <option value="">Select Bank</option>
                                    @foreach ($bank as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
        
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                                <label for="pwd">Cheque No:</label>
                                <input type="text" class="form-control" id="cheque" name="cheque">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                                <label for="pwd">Cheque Date:</label>
                                <input value="{{ date('Y-m-d') }}" class="form-control" name="cheque_date" type="date">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 for_cash" style="display: none;">
                                <label for="pwd">Dr Account</label>
                                <select name="acc_id" id="acc_id" class="form-control select2">
                                    <option value="">Select</option>
                                    {{-- @foreach (CommonHelper::get_all_account() as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach --}}
                                    @foreach (CommonHelper::get_accounts_by_parent_code('1-2-9') as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
        
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="comment">Remarks:</label>
                                <textarea name="desc" class="form-control" rows="3" id="comment"><?php foreach ($Colll as $cc):
                                    echo CommonHelper::byers_name($cc->buyers_id)->name;
                                endforeach; ?></textarea>

                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="row">
                            <input type="hidden" name="ref_bill_no" value="" />
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SI No</th>
                                            <th class="text-center">SO No</th>
                                            <th class="text-center">ST No</th>
                                            <th class="text-center">Invoice Amount</th>
                                            <th class="text-center">Return Amount</th>
                                            <th class="text-center">Previous Received Amount</th>
                                            <th class="text-center">Received Amount</th>
                                            <th class="text-center hide">Tax%</th>
                                            <th class="text-center">Cartage Amount</th>
                                            <th class="text-center">Tax Amount</th>
                                            <th class="text-center">Discount Amount</th>
                                            <th class="text-center">Net Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $counter = 1;
                                        $gi_no = []; ?>
                                        @foreach ($val as $row)
                                            <?php
                                            
                                            $invoice_detail = SalesHelper::get_sales_detail_for_receipt($row);
                                            $get_freight = SalesHelper::get_freight($row);
                                            $received_amount = SalesHelper::get_received_payment($row);
                                            
                                            if ($received_amount == null):
                                                $received_amount = 0;
                                            endif;
                                            
                                            $return_amount = SalesHelper::get_sales_return_from_sales_tax_invoice($row);
                                            
                                            if ($return_amount == null):
                                                $return_amount = 0;
                                            endif;
                                            
                                            if ($invoice_detail->so_type == 1):
                                                $invoice_amount = $invoice_detail->old_amount;
                                            else:
                                                $invoice_amount = $invoice_detail->invoice_amount + $get_freight + $invoice_detail->advance_tax_amount + $invoice_detail->cartage_amount;
                                            endif;
                                            
                                            $sales_tax_further = $invoice_detail->sales_tax_further ?? 0;
                                            $advance_tax_amount = $invoice_detail->advance_tax_amount ?? 0;
                                            
                                            $taxamount = $invoice_detail->tax_amount ?? 0;
                                            $taxamount = $taxamount + $sales_tax_further + $advance_tax_amount;
                                            
                                            $gi_no[] = $invoice_detail->gi_no;
                                            ?>
                                            <input type="hidden" name="si_id[]" value="{{ $row }}" />
                                            <input type="hidden" name="so_id[]" value="{{ $invoice_detail->so_id }}" />

                                            <tr title="{{ 'sales_invoice_id=' . $row }}">
                                                <td class="text-center">{{ strtoupper($invoice_detail->gi_no) }}</td>
                                                <td class="text-center">
                                                    @if ($invoice_detail->so_type == 1)
                                                        {{ $invoice_detail->description }}
                                                    @else
                                                        {{ strtoupper($invoice_detail->so_no) }}
                                                    @endif
                                                </td>
                                                <td>{{ $invoice_detail->sc_no }}</td>
                                                <td class="text-center">{{ number_format($invoice_amount, 2) }}</td>

                                                <td class="text-center">{{ number_format($return_amount, 2) }}</td>
                                                <td class="text-center">{{ number_format($received_amount, 2) }}</td>


                                                <td><input class="form-control receive_amount"
                                                        onkeyup="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                                        onblur="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                                        type="text" name="receive_amount[]" id="receive_amount{{ $counter }}"
                                                        value="{{ $invoice_amount - $received_amount - $return_amount }}"></td>

                                                <td class="hide"><select
                                                        onchange="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',0)"
                                                        id="percent{{ $counter }}" class="form-control tex_p" name="percent[]">

                                                        @foreach (CommonHelper::get_table_data('gst') as $item)
                                                            @if ($item->id == $invoice_detail->sales_tax_group)
                                                                <option @if ($item->id == $invoice_detail->sales_tax_group) selected @endif
                                                                    value="{{ $item->rate }}" id="tax{{ $item->id }}">{{ $item->rate }} %
                                                                    </ option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <input type="hidden" value="{{ $invoice_detail->sales_tax_group }}" name="tax_rate_id[]">

                                                <td><input
                                                        class="form-control" type="text" value="{{ $invoice_detail->cartage_amount }}" name="cartage_amount[]"
                                                        id="cartage_amount{{ $counter }}" readonly /></td>

                                                <td><input
                                                        onkeyup="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                                        onblur="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                                        class="form-control tax" type="text" value="{{ $taxamount }}" name="tax_amount[]"
                                                        id="tax_amount{{ $counter }}" readonly /></td>

                                                <td><input
                                                        onkeyup="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                                        onblur="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                                        class="form-control discount" type="text" value="" name="discount[]"
                                                        id="discount_amount{{ $counter }}"></td>

                                                <td><input class="form-control net_amount comma_seprated" type="text" readonly
                                                        value="{{ $invoice_amount - $received_amount - $return_amount }}" name="net_amount[]"
                                                        id="net_amount{{ $counter }}"></td>

                                            </tr>

                                            <input type="hidden" id="inv_amount{{ $counter }}" value="{{ $invoice_amount }}" />
                                            <input type="hidden" id="rec_amount{{ $counter }}" value="{{ $received_amount }}" />
                                            <input type="hidden" id="ret_amount{{ $counter }}" value="{{ $return_amount }}" />


                                            <?php $counter++;
                                            $gi = implode(',', $gi_no); ?>
                                        @endforeach
                                        <input type="hidden" name="count" id="count" value="{{ $counter - 1 }}" />
                                        <input type="hidden" name="ref_bill_no" value="{{ $gi }}" />
                                        <input type="hidden" name="buyers_id" value="{{ $invoice_detail->buyers_id }}" />
                                        <tr class="heading" style="background-color: darkgrey">
                                            <td class="text-center" colspan="8">Total</td>
                                            <td><input readonly type="text" id="tax_total" class="form-control comma_seprated" /></td>

                                            <td><input readonly type="text" id="discount_total" class="form-control comma_seprated" /></td>
                                            <td id=""><input readonly type="text" id="net_total"
                                                    class="form-control comma_seprated" /> </td>
                                        </tr>

                                    </tbody>
                                </table>
                                <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success" onclick="SetValue(0)">Submit</button>
                                    <button type="submit" id="BtnSaveAndPrint" class="btn btn-info BtnSaveAndPrint" onclick="SetValue(1)">Save
                                        & Print</button>
                                </div>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
        

    <script>
        function SetValue(v) {
            $('#SavePrintVal').val(v);
        }

        function calc(invoice_amount, previous_amount, counter, return_amount, type) {

            //  alert(invoice_amount+' '+previous_amount+' '+counter+' '+return_amount);
            var invoice_amount = parseFloat(invoice_amount);
            var previous_amount = parseFloat(previous_amount);
            var return_amount = parseFloat(return_amount);

            if (isNaN(return_amount)) {
                return_amount = 0;
            }

            if (isNaN(previous_amount)) {
                previous_amount = 0;
            }
            var actual_amount = invoice_amount - previous_amount - return_amount;


            var receive_amount = parseFloat($('#receive_amount' + counter).val());

            if (isNaN(receive_amount)) {
                receive_amount = 0;
            }

            if (receive_amount > actual_amount) {
                alert('Amount Can not greater them ' + actual_amount);
                $('#receive_amount' + counter).val(0);
                return false;
            }

            if (type == 0) {
                var tax_percent = parseFloat($('#percent' + counter).val());
                var tax_amount = ((receive_amount / 100) * tax_percent).toFixed(2);
                // $('#tax_amount' + counter).val(tax_amount);
            } else {
                var tax_amount = parseFloat($('#tax_amount' + counter).val());
                var tax_percent = parseFloat($('#percent' + counter).val());
                var new_tax_amount = parseFloat(receive_amount - (receive_amount / (1 + (tax_percent / 100)))).toFixed(2);

                // console.log(tax_amount , tax_percent , new_tax_amount);
                // $('#tax_amount' + counter).val(new_tax_amount);

                if (isNaN(tax_amount)) {
                    tax_amount = 0;
                }
            }
         
            // if (receive_amount < tax_amount) {
            //     alert('tax amount can not be grater than recived amount')
            //     $('#tax_amount' + counter).val(0);
            // }


            var discount_amount = parseFloat($('#discount_amount' + counter).val());
            if (isNaN(discount_amount)) {
                discount_amount = 0;
            }

            var net_amount = receive_amount - discount_amount;
            $('#net_amount' + counter).val(net_amount);


            var amount = 0;

            $('.net_amount').each(function(i, obj) {

                amount += +$('#' + obj.id).val();
            });
            amount = parseFloat(amount);
            $('#net_total').val(amount);


            var tax = 0;

            $('.tax').each(function(i, obj) {

                tax += +$('#' + obj.id).val();
            });
            tax = parseFloat(tax);
            $('#tax_total').val(tax);



            var discount = 0;

            $('.discount').each(function(i, obj) {

                discount += +$('#' + obj.id).val();
            });
            discount = parseFloat(discount);
            $('#discount_total').val(discount);

        }

        $(document).ready(function() {
            $('.select2').select2();
            $('.comma_seprated').number(true, 2);
        });




        $("form").submit(function(event) {
            var validate = validatee();

            if (validate == true) {

            } else {
                return false;
            }

        });

        function validatee() {
            var validate = true;
            $(".receive_amount").each(function() {
                var id = this.id;



                var amount = $('#' + id).val();

                if (amount <= 0 || amount == '') {
                    $('#' + id).css('border', '3px solid red');

                    validate = false;
                } else {
                    $('#' + id).css('border', '');

                    if ($('#cheque').val() == '') {
                        $('#cheque').css('border', '3px solid red');

                        validate = false;
                    }

                    if ($('#acc_id').val() == '' && $('#bank_id').val() == '') {
                        alert('pls select Debit Account');
                        validate = false;
                        return false;


                    }
                }

            });
            return validate;
        }

        $("#percent1").change(function() {
            //          var  percent=$('#'+this.id).val();
            //           var count=$('#count').val();
            //            $('.tex_p').val(percent);
            //            for (i=2; i<=count; i++)
            //            {
            //
            //                var inv_amount=$('#inv_amount'+i).val();
            //                var rec_amount=$('#rec_amount'+i).val();
            //                var ret_amount=$('#ret_amount'+i).val();
            //                calc(inv_amount,rec_amount,i,ret_amount);
            //            }


        });

        function hide_unhide() {
            var pay_mode = $('#pay_mode').val();
            if (pay_mode == '2,2') {
                $(".hidee").css("display", "none");
                $('#cheque').val('-');
                $(".for_cash").css("display", "block");
                $('#acc_id').select2();

            } else {
                $(".hidee").css("display", "block");
                $(".for_cash").css("display", "none");
            }
        }

        $('.tax').trigger('keyup')
    </script>
@endsection
