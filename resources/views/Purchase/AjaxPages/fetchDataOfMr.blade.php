<?php
     use App\Helpers\PurchaseHelper;
     use App\Helpers\CommonHelper;
     use App\Helpers\NotificationHelper;

   $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`demand_no`,3,length(substr(`demand_no`,3))-4),signed integer)) reg from `demand` where substr(`demand_no`,-4,2) = " . date('m') . " and substr(`demand_no`,-2,2) = " . date('y') . "")->reg;
   $demand_no = 'pr' . ($str + 1) . date('my');
   ?>
<div class="panel">
                        <div class="panel-body">
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br/>
                                 <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                       <label class="sf-label">PR NO. <span class="rflabelsteric"><strong>*</strong></span></label>
                                       <input readonly type="text" class="form-control requiredField" placeholder="" name="pr_no" id="pr_no" value="{{strtoupper($demand_no)}}" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                       <label class="sf-label">PR Date.</label>
                                       <span class="rflabelsteric"><strong>*</strong></span>
                                       <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="demand_date_1" id="demand_date_1" value="<?php echo date('Y-m-d') ?>" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                       <label class="sf-label">Ref No. <span class="rflabelsteric"></label>
                                       <input autofocus type="text" class="form-control" placeholder="Ref  No" name="slip_no_1" id="slip_no_1" value="{{ $material_requests->material_request_no }}" />
                                        <input type="hidden" name="material_request_no" value="{{ $material_requests->material_request_no }}" />
                                        <input type="hidden" name="material_request_id" value="{{ $material_requests->id }}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                       <label class="sf-label">Department / Sub Department</label>
                                       <span class="rflabelsteric"><strong>*</strong></span>
                                       <select class="form-control requiredField select2"  name="sub_department_id_1" id="sub_department_id_1">
                                          <option value="">Select Department</option>
                                          @foreach($departments as $key => $y)
                                          <option @if($material_requests->sub_department_id==$y->id) selected @endif value="{{ $y->id}}">
                                             {{ $y->department_name}}
                                          </option>
                                          @endforeach
                                       </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                       <label class="sf-label">Type</label>
                                       <span class="rflabelsteric"><strong>*</strong></span>
                                       <select class="form-control requiredField select2" name="v_type" id="v_type">
                                          <option value="">Select Type</option>
                                          @foreach(NotificationHelper::get_all_type() as $row)
                                          <option value="{{ $row->id}}">{{ $row->name}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                                 <input type="hidden" name="demand_type" id="demand_type">
                                 <div class="row">
                                 </div>
                              </div>
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                       <label class="sf-label">Description</label>
                                       <span class="rflabelsteric"><strong>*</strong></span>
                                       <textarea name="description_1" id="description_1" rows="4" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
                                    </div>
                                 </div>
                              </div>
                           </div>
                          
                           <div class="headquid">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div >
                                       <h2 class="subHeadingLabelClass">Item Details</h2>
                                    </div>
                                 </div>
                                
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <div class="table-responsive" id="">
                                    <table class="userlittab table table-bordered sf-table-list">
                                       <thead>
                                          <tr>
                                             <th style="width: 250px" class="text-center">Category</th>
                                             <th style="width: 250px"  class="text-center">Item Code</th>
                                             <th style="width: 250px" class="text-center">Item Name</th>
                                             <th style="width: 100px" class="text-center" >UOM<span class="rflabelsteric"><strong>*</strong></span></th>
                                             <th style="width: 130px" class="text-center" >QTY<span class="rflabelsteric"><strong>*</strong></span></th>
                                             <th style="width: 130px" class="text-center hide">Closing Stock<span class="rflabelsteric"><strong>*</strong></span></th>
                                             <th style="width: 130px" class="text-center hide">Last Order QTY</th>
                                             <th style="width: 130px" class="text-center hide">Last Received QTY</th>
                                             <th style="width: 100px" class="text-center">History</th>
                                             <th style="width: 100px" class="text-center">Action</th>
                                          </tr>
                                       </thead>
                                       <tbody id="AppnedHtml">
                                        @php $counter=0; @endphp
                                        @foreach($material_request_data as $mr_data)
                                            @php $category_id=DB::connection('mysql2')->table('subitem')->select('main_ic_id')->where('status',1)->where('id',$mr_data->sub_item_id)->value('main_ic_id');
                                            $counter++;
                                            $uom_name='';
                                            $sub_item_name='';
                                            @endphp
                                          <tr id="" class="AutoNo">
                                             <td>
                                                <select onchange="get_sub_item('category_id{{$counter}}')" name="category" id="category_id{{$counter}}"  class="form-control category select2 normal_width">
                                                   <option value="">Select</option>
                                                   @foreach (CommonHelper::get_all_category() as $category):
                                                   <option @if($category_id == $category->id) selected @endif value="{{ $category->id }}"> {{ $category->main_ic }} </option>
                                                   @endforeach
                                                </select>
                                             </td>
                                             <td>
                                                <select onchange="get_item_name({{$counter}})" name="item_id[]" id="item_id{{$counter}}" class="form-control select2">
                                                <option>Select</option>
                                                @foreach (CommonHelper::get_item_by_category($category_id) as $item)
                                                    <?php
                                                    $data=DB::Connection('mysql2')->table('subitem as a')
                                                    ->join(env('DB_DATABASE').'.uom as b','a.uom','=','b.id')
                                                    ->where('a.main_ic_id',$item->main_ic_id)
                                                    ->where('a.id',$mr_data->sub_item_id)
                                                    ->where('a.status',1)
                                                    ->select('a.id','a.sub_ic','a.item_code','b.uom_name','a.sub_ic')
                                                    ->first();
                                                     $uom_name=$data->uom_name;
                                                     $sub_item_name=$data->sub_ic;
                                                    ?>
                                                    <option value="{{ $item->id . '@' . $data->uom_name . '@' . $data->sub_ic }}" 
                                                        {{ ($item->id == $mr_data->sub_item_id) ? 'selected' : '' }}>
                                                        {{ $data->sub_ic }}
                                                    </option>
                                                @endforeach    
                                                </select>
                                             </td>
                                             <td>
                                                <input readonly type="text" value="{{ $sub_item_name }}" class="form-control" name="item_code[]" id="item_code{{$counter}}">
                                             </td>
                                             <td>
                                                <input readonly type="text" value="{{ $uom_name }}" class="form-control" name="uom_id[]" id="uom_id{{$counter}}">
                                             </td>
                                             <td>
                                                <input type="text" class="form-control requiredField qty" value="{{ $mr_data->qty }}" name="quantity[]" id="quantity{{$counter}}">
                                             </td>
                                             <td class="hide">
                                                <input readonly type="text" class="form-control" name="closing_stock[]" id="closing_stock{{$counter}}">
                                             </td>
                                             <td class="hide">
                                                <input readonly type="text" class="form-control" name="last_ordered_qty[]" id="last_ordered_qty{{$counter}}">
                                             </td>
                                             <td class="hide">
                                                <input readonly type="text" class="form-control" name="last_received_qty[]" id="last_received_qty{{$counter}}">
                                             </td>
                                            
                                             <td  class="text-center" ><input onclick="view_history({{$counter}})" type="checkbox" id="view_history{{$counter}}"></td>
                                             @if($counter==1)
                                             <td> <a href="#"  class="btn btn-sm btn-primary" onclick="AddMoreDetails({{$counter}})"><span class="glyphicon glyphicon-plus-sign"></span> </a></td>
                                             @endif       
                                        </tr>
                                        @endforeach  
                                       </tbody>
                                    </table>
                                    <input type="hidden" name="counter" value="{{ $counter }}" id="counter"/> 
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="mp-20 text-right">
                {{ Form::submit('Submit', ['class' => 'btnn btn-success']) }}
             </div>