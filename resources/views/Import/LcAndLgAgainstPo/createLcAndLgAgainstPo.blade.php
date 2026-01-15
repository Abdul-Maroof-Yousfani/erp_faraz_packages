@extends('layouts.default')
@section('content')
@include('select2')

<style>
 .my-lab label{padding-top:0px;}
</style>
    <div class="row well_N align-items-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Import</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Create Lc and Lg Against PO</h3>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <div class="alert-message">
                            </div>
                        </div>
                    </div>            
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     
                            <form action="{{route('LcAndLgAgainstPo.store')}}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class=" qout-h">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Purchase Request* </label>
                                                    <select onchange="nextForm()" name="po_id" class="form-control select2" id="po_id">
                                                        <option value=""> Select Purchase Request</option>
                                                        @foreach($PurchaseRequest as $key => $value)
                                                        <option value="{{ $value->id }}" > {{ $value->purchase_request_no }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 padt pos-r" id="nextForm">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function nextForm() {
            let po_id = $('#po_id').val();
            $('#nextForm').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            if(po_id) {
                $.ajax({
                    url: '{{ url('/import/LcAndLgAgainstPo/create') }}',
                    type: 'Get',
                    data: {
                        po_id:po_id
                    },
                    success: function (response) {
                        console.log('typeof',typeof(response));
                        $('#nextForm').html(response);
                        $('.qty').trigger('keyup');
                    }
                });

            } else {
                $('#nextForm').empty();
            }
        }

        function calculation_po(number) {
            var  qty=$('#qty'+number).val();
            var  rate=$('#rate'+number).val();

            var total=parseFloat(qty*rate).toFixed(2);

            $('#total_amount'+number).val(total);

            hs_code_tax(number);

        }

        function hs_code_tax(number) {

            let index = number - 1
            let custom_duty = + hsCodeData[index]?.custom_duty || 0 ;
            let regulatory_duty = + hsCodeData[index]?.regulatory_duty || 0 ;
            let federal_excise_duty = + hsCodeData[index]?.federal_excise_duty || 0 ;
            let additional_custom_duty = + hsCodeData[index]?.additional_custom_duty || 0 ;
            let sales_tax = + hsCodeData[index]?.sales_tax || 0 ;
            let additional_sales_tax = + hsCodeData[index]?.additional_sales_tax || 0 ;
            let income_tax = + hsCodeData[index]?.income_tax || 0 ;
            let clearing_expense = + hsCodeData[index]?.clearing_expense || 0 ;
            let total_duty_without_taxes = + hsCodeData[index]?.total_duty_without_taxes || 0 ;
            let total_duty_with_taxes = + hsCodeData[index]?.total_duty_with_taxes || 0 ;
            let total_tax = custom_duty + regulatory_duty + federal_excise_duty + additional_custom_duty + sales_tax + additional_sales_tax + income_tax + clearing_expense +    total_duty_without_taxes + total_duty_with_taxes;

            let amount = + $('#total_amount'+number).val();

            let hs_code_amount = amount * total_tax / 100;

            $('#hs_code_amount'+number).val(hs_code_amount.toFixed(2));


            var hsCodeAmountElements = document.querySelectorAll('.hs_code_amount');
            var total_amount = document.querySelectorAll('.total_amount');

                // Initialize a variable to store the sum
                var sum = 0;
                var total_amount_sum = 0;

                // Iterate through the elements and sum their values
                hsCodeAmountElements.forEach(function (element) {
                    // Convert the text content to a number and add it to the sum
                    sum += parseFloat(element.value);
                });
                total_amount.forEach(function (element) {
                    // Convert the text content to a number and add it to the sum
                    total_amount_sum += parseFloat(element.value);
                });

            $('#hs_code').val(sum.toFixed(2));
            $('#d_t_amount_1').val(total_amount_sum.toFixed(2));
            toWords(1);

        }

        $(document).ready(function(){
            $('#po_id').select2();
        });

    </script>
@endsection
