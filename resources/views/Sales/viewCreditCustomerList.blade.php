<?php
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
   $m = $_GET['m'];
} else {
   $m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
   ?>
@extends('layouts.default')
@section('content')
   <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.css" rel="stylesheet" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.js"></script>

   <div class="row well_N align-items-center">
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
         <ul class="cus-ul">
            <li>
               <h1>Customers</h1>
            </li>
            <li>
               <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Customer List</h3>
            </li>
         </ul>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
         <!-- <ul class="cus-ul2">
            <li>
               <a href="{{ url()->previous() }}" class="btn-a">Back</a>
            </li>
            {{-- 
            <li>
               <input type="text" class="fomn1" placeholder="Search Anything" >
            </li>
            <li>
               <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
            </li>
            <li>
               <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
            </li>
            --}}
         </ul> -->
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
         <div class="well_N">
            <div class="dp_sdw">
               <div class="panel">
                  <div class="panel-body">
                     <div class="row borderBtmMnd ">
                       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                           <div class="headquid">
                              <h2 class="subHeadingLabelClass">Customer List</h2>
                              </div>
                           </div>
                           <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right">
                                 <?php echo CommonHelper::displayPrintButtonInBlade('printList', '', '1');?>
                                 <button type="button" class="btn btn-warning"
                                    onclick="tableToExcel('TableExportToCsv', 'Chart of Account List')">Export to CSV</button>
                              </div>
                           </div>
                           <div id="printList">
                              <div class="panel">
                                 <div id="PrintPanel">
                                    <div id="ShowHide">
                                       <div class="table-responsive">

                                          <table class="userlittab table table-bordered sf-table-list"
                                             id="TableExportToCsv">
                                             <thead>
                                                <tr>
                                                   <th class="text-center">S No</th>
                                                   <th class="text-center">Customer Name</th>
                                                   <th class="wsale text-center">Contact</th>
                                                   <th style="width: 150px;" class="wsale2 text-center">Address</th>
                                                   <th class="text-center">Point of Contact</th>
                                                   <th class="text-center">POC Contact No</th>
                                                   <th class="text-center hidden-print">Action</th>
                                                </tr>
                                             </thead>
                                             <tbody id="viewCreditCustomerList">
                                               
                                             </tbody>
                                          </table>
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
   <script>
      function CreateAccount(AccId, CustomerName, CustomerId) {
         var acc_code = '';
         var headOne = '<?php echo CommonHelper::get_account_name_by_code('1');?>';
         var headTwo = '<?php echo CommonHelper::get_account_name_by_code('1');?>';


         swal({
            title: 'Select Account To Create Supplier',
            input: 'select',
            inputOptions: {
               '1-2-1': headOne,
               '1-2-2': headTwo,

            },
            inputPlaceholder: 'Select Account Head',
            showCancelButton: true,
            inputValidator: function (value) {
               return new Promise(function (resolve, reject) {
                  if (value !== '') {
                     acc_code = value;
                     resolve()

                  } else {
                     reject('You need to select account head :)')
                  }
               })
            }
         }).then(function (result) {
            $.ajax({
               url: '<?php echo url('/')?>/sdc/createCustomerAccount',
               type: "GET",
               data: { AccId: AccId, CustomerName: CustomerName, CustomerId: CustomerId, value: acc_code },
               success: function (data) {
                  if (data == 'yes') {
                     if (result == '1-2-1') { result = headOne; }
                     else if (result == '1-2-2') { result = headThree; }
                     else { result = headTwo; }
                     swal({
                        type: 'success',
                        html: '<b>' + CustomerName + '</b>' + '<br>' + ' Account Create againts this ' + '<br>' + '<b>' + result + '</b>'
                     });
                     $('#Btn' + SupplierId).prop('disabled', true);
                     $('#ShowHide' + SupplierId).html('Account Created');
                  }

               }
            });

         });


      }

      $(document).ready(function () {
         function viewCreditCustomerList() {
            $('#viewCreditCustomerList').html('<tr><td colspan="100"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
               url: '<?php echo url('/')?>/sdc/viewCreditCustomerList',
               type: "GET",
               data: { m: m },
               success: function (data) {
                  setTimeout(function () {
                     $('#viewCreditCustomerList').html(data);
                  }, 1000);
               }
            });
         }
         viewCreditCustomerList();
      });

      function CustomerDelete(id) {

         if (confirm('Are you sure you want to delete this request')) {
            $.ajax({
               url: '/sdc/customer_delete',
               type: 'Get',
               data: { id: id },

               success: function (response) {
                  $('#' + response).remove();

               }
            });
         }
         else { }
      }
   </script>
   </script>
@endsection