<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$m = $_GET['m'];
$paramOne = $_GET['paramOne'];
$parentCode = $_GET['parentCode'];
if(empty($paramOne)){
 //   $subDepartmentsList = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.'');
}else{
   // $subDepartmentsList = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `id` = '.$paramOne.'');
}


?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <?php
             
               
             $data= DB::Connection('mysql2')->table('quotation_data as a')
             ->join('demand_data as b','a.pr_data_id','=','b.id')
             ->join('quotation as c','a.master_id','=','c.id')
             ->join('demand as d','b.master_id','=','d.id')
             ->where('a.status',1)
             ->where('b.status',1)
             ->where('a.quotation_status',1)
             ->where('c.quotation_status',2)
             ->select('b.id','b.sub_item_id','d.demand_no','d.demand_date','a.vendor','b.qty','c.dept_id')
             ->orderBy('vendor')
            ->get()->toArray();

            
            $vendor = [];
                ?>
                <?php echo Form::open(array('url' => 'stad/createPurchaseRequestDetailForm?m='.$m.'&&parentCode='.$parentCode.'&&pageType=add#SFR','id'=>'createPurchaseRequestDetailForm_'));?>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="departmentId" value="<?php ?>">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">

                         
                                
                          
                            <table class="table table-bordered table-responsive">
                                <thead>
                                    <th class="text-center"></th>
                                <th class="text-center">S.No</th>
                               
                                <th class="text-center">Department</th>
                                <th class="text-center">PR NO.</th>
                                <th class="text-center">PR Date</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Req. Qty.</th>


                                </thead>
                                <tbody id="filterDemandVoucherList">

                                    @if(!empty($data))
                                        
                                  <?php //echo '<pre>';
                                  //  print_r($data);die;
                                  $counter = 1; ?>
                                    @foreach ($data as $row)
                                <?php
                          
                            

                                ?>

                                        @if (!in_array($row->vendor, $vendor))
                                        <?php $vendor[] = $row->vendor; ?>
                                        <tr class="text-center">
                                        <td  colspan="10" style="font-weight: bold"> 
                                            <?php   echo 'Vendor : '. CommonHelper::get_supplier_name($row->vendor) ?></td> 
                                        <?php $status =  1;
                                        $counter=1;
                                        ?>
                                        </tr>
                                        @endif
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="checkAll[]>"
                                               class="checkSingle_<?php echo $row->id?>"
                                               id="{{ $row->id.'_'.$row->vendor }}"
                                               value="<?php echo $row->id?>" 
                                               onclick="check_supp('{{ $row->id.'_'.$row->vendor }}')">
                                    </td>
                                    <td class="text-center"><?php echo $counter++;?></td>
                             
                                    <td class="text-center"><?php echo CommonHelper::get_sub_dept_name($row->dept_id)?></td>
                                    <td class="text-center"><?php echo strtoupper($row->demand_no);?></td>
                                    <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->demand_date);?></td>
                                 
                                    <td class="text-center"><?php echo CommonHelper::get_item_name($row->sub_item_id);?></td>
                            
                                    <td class="text-center">
                                        <?php echo number_format($row->qty,2);?>
                                    </td>
                                   
                                </tr>
                                <?php

                          
                                ?>
                               
                                    @endforeach
                                   
                                </tbody>
                            </table>
                        
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                    </div>
                </div>
                <?php echo Form::close();?>
                <?php ?>
                <div class="lineHeight">&nbsp;</div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $(".checkedAll_<?php echo $row->id?>").change(function(){
                            if(this.checked){
                                $(".checkSingle_<?php echo $row->id?>").each(function(){
                                    this.checked=true;
                                })
                            }else{
                                $(".checkSingle_<?php echo $row->id?>").each(function(){
                                    this.checked=false;
                                })
                            }
                        });

                        $(".checkSingle_<?php echo $row->id?>").click(function () {
                            if ($(this).is(":checked")){
                                var isAllChecked = 0;
                                $(".checkSingle_<?php echo $row->id?>").each(function(){
                                    if(!this.checked)
                                        isAllChecked = 1;
                                })
                                //if(isAllChecked == 0){ $(".checkedAll_<?php echo $row->id?>").prop("checked", true); }
                            }else {
                                $(".checkedAll_<?php echo $row->id?>").prop("checked", false);
                            }
                        });
                    });
                </script>
                @endif
                <?php ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function checkCheckedBox(id,sIdOne,sIdTwo) {
        if ($('#'+id+':checked').length <= 1){
        }else{
         //   alert("Please select at least one checkbox Same Item.");
         //   $("input[name='checkAll_"+sIdOne+"_"+sIdTwo+"']:checkbox").prop('checked', false);
        }

    }
 
 	var vendor = [];
function check_supp(id)
{
    var numberOfChecked = $('input:checkbox:checked').length;
    if (numberOfChecked==0)
    {
        vendor = [];
    }
   if ( $('#'+id).is(':checked')==true)
   {
  
      var supp= id.split('_');

      if(jQuery.inArray(supp[1], vendor) !== -1)
      {
        //    alert('exists');
      }
      else 
      {

        if (vendor.length >0)
        {
           if (supp[1]!=vendor[0])
           {
            $('#'+id).prop('checked', false);
            alert('Supplier Should Be Same');
           }
        }
        else 
        {
            vendor.push(supp[1]);
        }
        
       
        
      }
   
       
   } 

  
}

</script>