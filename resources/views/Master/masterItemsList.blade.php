<form id="submit" action="{{ route('getMasterItemData') }}" method="post">
    <div class="mt">
        <input type="hidden" name="ajaxLoadUrl" id="ajaxLoadUrl" value="{{ route('master-items-list') }}">
        <input type="hidden" name="modalId" id="modalId" value="showModal">
        <input type="hidden" name="flag" id="flag" value="1">
        <div class="table-responsive wrapper">
            <h5 style="text-align: center" id="h3"></h5>
            <table class="userlittab table table-bordered table-hover tableFixHead" id="exportList">
                <tr>
                    <td>
                        <div class="title-wrapper bor-b">
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="custom-control-input" id="customCheck4" name="models" value="AssetsProjectPremises" />
                                <label class="custom-control-label" for="customCheck4">
                                    &nbsp; Premises
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="title-wrapper bor-b">
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="custom-control-input" id="customCheck1" name="models" value="AssetsCategory" />
                                <label class="custom-control-label" for="customCheck1">
                                    &nbsp; Category
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="title-wrapper bor-b">
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="custom-control-input" id="customCheck2" name="models" value="AssetsSubCategory" />
                                <label class="custom-control-label" for="customCheck2">
                                    &nbsp; Sub Category
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="title-wrapper bor-b">
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="custom-control-input" id="customCheck5" name="models" value="Floors" />
                                <label class="custom-control-label" for="customCheck5">
                                    &nbsp; Floors
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="title-wrapper bor-b">
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="custom-control-input" id="customCheck7" name="models" value="AssetsManufacturer" />
                                <label class="custom-control-label" for="customCheck7">
                                    &nbsp; Manufacturer
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="title-wrapper bor-b">
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="custom-control-input" id="customCheck10" name="models" value="AssetsDesignedLife" />
                                <label class="custom-control-label" for="customCheck10">
                                    &nbsp; UseFul Life
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="title-wrapper bor-b">
                            <div class="custom-control custom-checkbox">
                                <input type="radio" class="custom-control-input" id="customCheck13" name="models" value="AssetsFrequency" />
                                <label class="custom-control-label" for="customCheck13">
                                    &nbsp; Frequency
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
       
      
       
      
    </div>
    <div class="row">&nbsp;</div>
    <div class="mt text-center">
        <button type="submit" class="btn btn-primary mr-1">Select</button>
    </div>
</form>
<script src="{{ URL::asset('assets/custom/js/customFunctions.js') }}"></script>