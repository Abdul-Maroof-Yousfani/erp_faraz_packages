@extends('layouts.default')

@section('content')
    @include('select2')
    <style>
        .my-lab label {
            padding-top: 0px;
        }

        .well_N {
            min-height: 0px !important;
        }
    </style>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Import</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Create Hs Code</h3>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <form action="{{route('HsCode.store')}}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                                <div class=" qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <h1>Add Hs Code</h1>
                                                    </div>
                                                    <div class="col-md-12 padt pos-r">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">HS Code*</label>

                                                                    <input name="hs_code" id="hs_code" class="form-control"
                                                                        type="text" required>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Custom Duty</label>

                                                                    <input name="custom_duty" id="custom_duty"
                                                                        class="form-control" type="number" step="any"
                                                                        min="0.00" value="0" onkeyup="hsAmountCalculate()">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Regulatory Duty</label>

                                                                    <input name="regulatory_duty" id="regulatory_duty"
                                                                        class="form-control" type="number" step="any"
                                                                        min="0.00" value="0" onkeyup="hsAmountCalculate()">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Federal Excise Duty</label>

                                                                    <input name="federal_excise_duty"
                                                                        id="federal_excise_duty" class="form-control"
                                                                        type="number" step="any" min="0.00" value="0"
                                                                        onkeyup="hsAmountCalculate()">

                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="row">

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Additional Custom
                                                                        Duty</label>

                                                                    <input name="additional_custom_duty"
                                                                        id="additional_custom_duty" class="form-control"
                                                                        type="number" step="any" min="0.00" value="0"
                                                                        onkeyup="hsAmountCalculate()">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Sales Tax</label>

                                                                    <input name="sales_tax" id="sales_tax"
                                                                        class="form-control" type="number" step="any"
                                                                        min="0.00" value="0" onkeyup="hsAmountCalculate()">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Additional Sales
                                                                        Tax</label>

                                                                    <input name="additional_sales_tax"
                                                                        id="additional_sales_tax" class="form-control"
                                                                        type="number" step="any" min="0.00" value="0"
                                                                        onkeyup="hsAmountCalculate()">

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Income Tax</label>

                                                                    <input name="income_tax" id="income_tax"
                                                                        class="form-control" type="number" step="any"
                                                                        min="0.00" value="0" onkeyup="hsAmountCalculate()">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Clearing Expense</label>

                                                                    <input name="clearing_expense" id="clearing_expense"
                                                                        class="form-control" type="number" step="any"
                                                                        min="0.00" value="0" onkeyup="hsAmountCalculate()">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Total Duty Without
                                                                        Taxes</label>

                                                                    <input name="total_duty_without_taxes"
                                                                        id="total_duty_without_taxes" class="form-control"
                                                                        type="number" step="any" min="0.00" value="0"
                                                                        readonly>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Total Duty with
                                                                        Taxes</label>

                                                                    <input name="total_duty_with_taxes"
                                                                        id="total_duty_with_taxes" class="form-control"
                                                                        type="number" step="any" min="0.00" value="0"
                                                                        readonly>

                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="row">

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Description*</label>

                                                                    <textarea name="description" id="description"
                                                                        class="form-control" rows="3" required></textarea>

                                                                </div>
                                                            </div>


                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Utilise Under Benefit
                                                                        Of</label>

                                                                    <textarea name="utilise_under_benefit_of"
                                                                        id="utilise_under_benefit_of" class="form-control"
                                                                        rows="3"></textarea>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Applicable SRO
                                                                        Benefit</label>

                                                                    <textarea name="applicable_sro_benefit"
                                                                        id="applicable_sro_benefit" class="form-control"
                                                                        rows="3"></textarea>

                                                                </div>
                                                            </div>

                                                        </div>



                                                        <div class="row">




                                                        </div>
                                                    </div>


                                                    <div class="col-md-12 padtb text-right">
                                                        <div class="col-md-9"></div>
                                                        <div class="col-md-3 my-lab">
                                                            <button type="submit" class="btn btn-primary mr-1"
                                                                data-dismiss="modal">Save</button>
                                                            <a href="{{ route('HsCode.cancel') }}"
                                                                class="btnn btn-secondary">Cancel</a>
                                                        </div>
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
        </div>
    </div>

@endsection

<script>
    function hsAmountCalculate() {
        let custom_duty = + $('#custom_duty').val() ?? 0;
        let regulatory_duty = + $('#regulatory_duty').val() ?? 0;
        let federal_excise_duty = + $('#federal_excise_duty').val()
        let additional_custom_duty = + $('#additional_custom_duty').val() ?? 0;
        let clearing_expense = + $('#clearing_expense').val()

        let sales_tax = + $('#sales_tax').val() ?? 0;
        let additional_sales_tax = + $('#additional_sales_tax').val() ?? 0;
        let income_tax = + $('#income_tax').val() ?? 0;


        let totalDutyWithoutTaxes = custom_duty + regulatory_duty + federal_excise_duty + additional_custom_duty + clearing_expense;

        let totalDutyWithTaxes = sales_tax + additional_sales_tax + income_tax + totalDutyWithoutTaxes;

        $('#total_duty_without_taxes').val(totalDutyWithoutTaxes.toFixed(2))
        $('#total_duty_with_taxes').val(totalDutyWithTaxes.toFixed(2))

    }


</script>