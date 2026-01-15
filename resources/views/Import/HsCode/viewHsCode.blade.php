<style>
    .my-lab label {
        padding-top: 0px;
    }
</style>


<div class="row">

    <div class="col-md-12 padt pos-r">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">HS Code*</label>
                    <input disabled name="hs_code" id="hs_code" class="form-control" type="text"
                        value="{{ $HsCode->hs_code }}" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Custom Duty</label>
                    <input disabled name="custom_duty" id="custom_duty" class="form-control" type="number" step="0.01"
                        value="{{ $HsCode->custom_duty }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Regulatory Duty</label>
                    <input disabled name="regulatory_duty" id="regulatory_duty" class="form-control" type="number"
                        step="0.01" value="{{ $HsCode->regulatory_duty }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Federal Excise Duty</label>
                    <input disabled name="federal_excise_duty" id="federal_excise_duty" class="form-control"
                        type="number" step="0.01" value="{{ $HsCode->federal_excise_duty }}">
                </div>
            </div>
        </div>
        <!-- Repeat similar blocks for other decimal fields -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Additional Custom Duty</label>

                    <input disabled name="additional_custom_duty" id="additional_custom_duty" class="form-control"
                        type="number" step="0.01" value="{{ $HsCode->additional_custom_duty }}">

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Sales Tax Duty</label>

                    <input disabled name="sales_tax" id="sales_tax" class="form-control" type="number" step="0.01"
                        value="{{ $HsCode->sales_tax }}">

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Additional Sales Tax</label>

                    <input disabled name="additional_sales_tax" id="additional_sales_tax" class="form-control"
                        type="number" step="0.01" value="{{ $HsCode->additional_sales_tax }}">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Income Tax</label>

                    <input disabled name="income_tax" id="income_tax" class="form-control" type="number" step="0.01"
                        value="{{ $HsCode->income_tax }}">

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Clearing Expense</label>

                    <input disabled name="clearing_expense" id="clearing_expense" class="form-control" type="number"
                        step="0.01" value="{{ $HsCode->clearing_expense }}">

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Total Duty Without Taxes</label>

                    <input disabled name="total_duty_without_taxes" id="total_duty_without_taxes" class="form-control"
                        type="number" step="0.01" value="{{ $HsCode->total_duty_without_taxes }}">

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Total Duty With Taxes</label>

                    <input disabled name="total_duty_with_taxes" id="total_duty_with_taxes" class="form-control"
                        type="number" step="0.01" value="{{ $HsCode->total_duty_with_taxes }}">

                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">description</label>

                    <textarea disabled name="description" id="description" class="form-control"
                        rows="3">{{ $HsCode->description }}</textarea>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Utilise Under Benefit Of</label>

                    <textarea disabled name="utilise_under_benefit_of" id="utilise_under_benefit_of"
                        class="form-control" rows="3">{{ $HsCode->utilise_under_benefit_of }}</textarea>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Applicable SRO Benefit</label>

                    <textarea disabled name="applicable_sro_benefit" id="applicable_sro_benefit" class="form-control"
                        rows="3">{{ $HsCode->applicable_sro_benefit }}</textarea>

                </div>
            </div>
        </div>

    </div>