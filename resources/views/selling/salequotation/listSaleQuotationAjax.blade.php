<?php
use App\Helpers\CommonHelper;
?>
@foreach ($sale_quotations as $sale_quotation)
    <tr>
        <td class="text-center">{{ $sale_quotation->quotation_no }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($sale_quotation->quotation_date) }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($sale_quotation->q_valid_up_to) }}</td>
        <td class="text-center">{{ $sale_quotation->revision_no }}</td>
        <!-- <td class="text-center">
            @if($sale_quotation->customer_type == 'customer')
            {{ (!empty(CommonHelper::byers_name($sale_quotation->customer_id))) ? strtoupper(CommonHelper::byers_name($sale_quotation->customer_id)->name) : '' }}
            @endif
            @if($sale_quotation->customer_type == 'prospect')
            {{ (!empty(CommonHelper::get_prospect($sale_quotation->prospect_id))) ?  strtoupper(CommonHelper::get_prospect($sale_quotation->prospect_id)->company_name) : ''}}

          @endif
            
        </td> -->
        <td>
            @if($sale_quotation->so_status == 0)
                Pending
            @elseif($sale_quotation->so_status ==  2)
                Draft
            @else
              Sale Order Created	
            @endif

        </td>
        <td style="width: 198px;">
       
           @if($sale_quotation->approved_status == '0')
           <div class="pending">
               Pending
            </div>
           @elseif($sale_quotation->approved_status == '1')
           <div class="approved">
                Approved
            </div>
           @elseif($sale_quotation->approved_status == '3')
            <div class="rejected">
                Rejected
            </div>
           @endif
    
        </td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="" onclick="showDetailModelOneParamerter('saleQuotation/viewSaleQuotation/{{$sale_quotation->id}}',{{$sale_quotation->id}},'View Sale Quotation')"><i class="fa-regular fa-eye"></i> View</a>
                        <a class="" onclick="showDetailModelOneParamerter('saleQuotation/viewSaleQuotationPrint/{{$sale_quotation->id}}',{{$sale_quotation->id}},'View Sale Quotation ')"><i class="fa-solid fa-print"></i> Print</a>
                        @if($sale_quotation->approved_status == '0')
                            <a href="{{route('editSaleQuotation',$sale_quotation->id)}}" class=""><i class="fa-solid fa-pencil"></i> Edit</a>
                            <a href="#" class=""><i class="fa-solid fa-trash"></i> Delete</a>
                        @endif
                        @if($sale_quotation->so_status == 0)
                            @if($sale_quotation->approved_status == '0')
                                <a href="{{route('approveSaleQuoatation',$sale_quotation->id)}}" class=""><i class="fa-solid fa-check"></i> Approve</a>
                                <a href="{{route('rejectSaleQuoatation',$sale_quotation->id)}}" class=""><i class="fa-solid fa-xmark"></i> Reject </a>
                            @endif
                        @else
                            <a onclick="removeDraft('{{$sale_quotation->id}}')" class=""><i class="fa-solid fa-circle-exclamation"></i> Remove Draft </a>
                        @endif
                    </li>
                </ul>
            </div>
        </td>
    </tr>

@endforeach