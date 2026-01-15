<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$m =  Session::get('run_company');
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$id = $_GET['id'];

$goodsReceiptNoteDetail = DB::connection('mysql2')->table('goods_receipt_note')->where('grn_no','=',$id)->first();
$grnDataDetail = DB::connection('mysql2')->table('grn_data')->where('grn_no','=',$id)->first();

$productDetail = CommonHelper::get_item_by_id($grnDataDetail->sub_item_id);


?>

  <div class="dp_sdw">
    <div class="panel">
      <div class="panel-body">
        <div class="headquid">
          <div class="row">
            <div class="col-md-6">
                <div class="Quotation_head">
                  <h2 class="subHeadingLabelClass">INCOMING INSPECTION</h2>
                </div>
              </div>
              <div class="col-md-6 text-right">
                <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList','','1');?>
                <?php echo CommonHelper::displayExportButton('demandVoucherList','','1')?>
              </div>
          </div>
        </div>
        <hr style="border:1px solid #ddd">
        <div class="row">
       
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
              <div class="row align-items-center ">
                
              </div>
              <div class="lineHeight">&nbsp;</div>
              <div>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                      <div class="panel-body">
                        <div class="headquid">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                        </div>

                        <div class="row">
                    
                </div>
                     
                        <div class="row"  id="printDemandVoucherList">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="subHeadingLabelClass">INCOMING</h2>

                             <div class="row align-items-center">

                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <table class="userlittab table table-bordered sf-table-list">
                                            <thead>
                                                <tr>
                                                    <th style="background: transparent;">DATE</th>
                                                    <td><?php echo CommonHelper::changeDateFormat($goodsReceiptNoteDetail->grn_date);?></td>
                                                </tr>
                                                <tr>
                                                    <th style="background: transparent;">PARTY</th>
                                                    <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$goodsReceiptNoteDetail->supplier_id);?></td>

                                                </tr>
                                                <tr>
                                                    <th style="background: transparent;">ITEM</th>
                                                    <td>{{ $productDetail->sub_ic }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="background: transparent;">QTY.</th>
                                                    <td> {{ $grnDataDetail->purchase_approved_qty }} {{ $productDetail->uom_name }} </td>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <div class="radio">
                                            <div class="form-check form-check-inline">
                                                <div class="rio">
                                                    <ul>
                                                        <li>
                                                            <input type="checkbox" id="Approved" checked name="Approved" value="Approved">
                                                            <label class="form-check-label" for="inlineRadio1">Ok</label>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" id="Approved"  name="Approved" value="Approved">
                                                            <label class="form-check-label" for="inlineRadio2">Reject</label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
                                        <textarea style="width: 100%;height: 200px;" name="" id="" placeholder="" >REMARKS : {{ $productDetail->remark }}</textarea>

                                            <div class="bo">
                                                <div class="rio">
                                                    <ul>
                                                        <li>
                                                            <input type="checkbox" id="Approved" checked name="Approved" value="Approved">
                                                            <label class="form-check-label" for="inlineRadio1">Approved</label>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" id="Approved" name="Approved" value="Approved">
                                                            <label class="form-check-label" for="inlineRadio2">Not Approved</label>
                                                        </li>
                                                    </ul>
                                                </div>
    
                                                <br>
                                                <br>
                                          
                                                

                                            </div>

                                    </div>


                                    <div class="row">
                                        
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                            <div class="signature">
                                                <p>____________________________</p>
                                                <div class="snaem">
                                                    <p>STORE MANAGER</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>




                                    

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>