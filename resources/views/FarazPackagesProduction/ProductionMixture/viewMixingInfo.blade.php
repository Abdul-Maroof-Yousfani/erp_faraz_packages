@php
    use App\Helpers\CommonHelper;
@endphp

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        {{ CommonHelper::displayPrintButtonInView('printRecipeDetail', '', '1') }}
    </div>
</div>
<div class="row" id="printRecipeDetail">


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3 style="text-align: center;">Raw Material Mixing</h3>
    </div>
  
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div>
            <table class="table table-bordered table-condensed tableMargin">
                <tbody>
                    <tr>
                        <td>Produced Mixture:</td>
                        <td>{{ $mixture->subItem->sub_ic ?? '' }}</td>
                    </tr>
                    
                    <tr>
                        <td>Quantity:</td>
                        <td>{{ $mixture->qty ?? '' }}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div>
            <table class="table table-bordered table-condensed tableMargin">
                <tbody>
                    <tr>
                        <td>Mixture No:</td>
                        <td>{{ $mixture->pm_no ?? '' }}</td>
                    </tr>
                   
                    <tr>
                        <td>Description:</td>
                        <td>{{ $mixture->description ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table table-bordered table-condensed">
            <thead>
                <tr><th colspan="4" style="text-align: center">Raw matterial input</th></tr>
                <tr>
                    <td class="text-center">S.No</td>
                    <!-- <td>Category</td> -->
                    <td class="text-center">Item Name</td>
                    <td class="text-center">Quantity</td>
                </tr>
            </thead>
            <tbody>
                @if(!empty($mixture->mixtureData))
                    @foreach ($mixture->mixtureData as $key => $data)
                        <tr>
                            <td class="text-center">{{ ++$key }}</td>
                            <!-- <td>{{ CommonHelper::get_sub_category_name($data->category_id) }}</td> -->
                            <td class="text-center">
                                @if(!empty($data->subItem)) {{ $data->subItem->sub_ic }} @endif
                            </td>
                            <td class="text-center">{{ number_format($data->qty,2) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
        <div class="container-fluid">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="signature_bor">Created By: </h6>
                    <b>
                        <p><?php echo strtoupper($mixture->username ?? ''); ?></p>
                    </b>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="signature_bor">Checked By:</h6>
                    <b>
                        <p><?php ?></p>
                    </b>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="signature_bor">Approved By:</h6>
                    <b>
                        <p></p>
                    </b>
                </div>
            </div>
        </div>
    </div>
</div>
