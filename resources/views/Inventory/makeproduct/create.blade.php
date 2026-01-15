@extends('layouts.default')
@section('content')
    @include('select2')
    @include('modal')
    @include('number_formate')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Add Make Product</span>
                            </div>
                        </div>
                        <?php echo Form::open(['url' => 'makeProduct/addMakeProductDetail', 'id' => 'makeProduct']); ?>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">


                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Select Recipe</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="requiredField form-control select2" name="recipe_id"
                                                    id="recipe_id" onchange="getRecipeData(this.value)">
                                                    <option value="">Select Recipe</option>
                                                    @foreach ($recipe as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->subItem->sub_ic }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">quantity</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="quantity" class="requiredField form-control"
                                                    id="quantity" readonly onkeyup="qtCal()" min="0">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Electricity Expense</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="electricity_expense"
                                                    class="requiredField form-control" id="electricity_expense"
                                                    onkeyup="qtCal()" value="0" min="0">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Labour Expense</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="labour_expense"
                                                    class="requiredField form-control" id="labour_expense" onkeyup="qtCal()"
                                                    value="0" min="0">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Expense</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="expense" class="requiredField form-control"
                                                    id="expense" onkeyup="qtCal()" value="0" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="getRecipeData">

                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        {{-- <button type="button" id="Submit" class="btn btn-success">Submit</button> --}}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo Form::close(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        $('.select2').select2();
        function getRecipeData(id) {

            if (!$('#recipe_id').val()) {
                $('#quantity').val(0)
                $('#quantity').prop('readonly', true);
                $('#getRecipeData').html("");
                return;
            }
            var url = "{{ url('/') }}";
            $.ajax({
                url: url + '/makeProduct/getRecipeData/' + id,
                type: 'Get',
                data: {},
                success: function(response) {
                    $('#quantity').prop("readonly", false);
                    $('#getRecipeData').html(response);
                }
            });
        }

        function qtCal() {
            let qty = $('#quantity').val();
            let totalQuantity = $('.totalQuantity');
            let ActualQty = $('.ActualQty');
            let rate_per_qty = $('.rate_per_qty');
            let total_rate = $('.total_rate');
            let electricity_expense = $('#electricity_expense').val() ? $('#electricity_expense').val() : 0;
            let labour_expense = $('#labour_expense').val() ? $('#labour_expense').val() : 0;
            let expense = $('#expense').val() ? $('#expense').val() : 0;
            let sum = parseInt(electricity_expense) + parseInt(labour_expense) + parseInt(expense);
            for (i = 0; i < ActualQty.length; ++i) {
                totalQuantity[i].value = ActualQty[i].value * qty;
                total_rate[i].value = rate_per_qty[i].value * totalQuantity[i].value;
                sum = parseInt(sum) + parseInt(total_rate[i].value);
            }
            $('#totalrate').text(sum)
            $('#totalrateInput').val(sum)
            // alert(ActualQty.length);
        }

        $("form").submit(function(e) {
            var makeProduct = new Array();
            var val;
            makeProduct.push($(this).val());
            var _token = $("input[name='_token']").val();
            for (val of makeProduct) {

                jqueryValidationCustom();
                if (validate == 0) {
                    if (checkStock() == false) {
                        return false
                    }

                } else {
                    return false;
                }
            }
        });

        function checkStock() {
            let totalQuantity = $(".totalQuantity");
            let item = $(".items");
            for (var i = 0; i < totalQuantity.length; i++) {
                if ($(item[i]).find(':selected').data("stock") < totalQuantity[i].value) {
                    alert($(item[i]).find(':selected').text() + " quantity not minimum in your stock");
                    return false;
                }
            }
            // console.log(selectedItem)

        }
    </script>


    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
