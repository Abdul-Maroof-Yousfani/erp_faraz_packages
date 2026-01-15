<?php
use App\Helpers\PurchaseHelper;
$m = $_GET['m'];
?>


@extends('layouts.default')

@section('content')
    @include('select2')
    <link rel="stylesheet" type="text/css" href="{{url('assets/js/searchable_jquery/jquery.autocomplete.css')}}">
    {{--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>--}}
    <script type="text/javascript" src="{{url('assets/js/searchable_jquery/jquery.autocomplete.js')}}"></script>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="buildyourform" class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 300px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createCategoryFormAjax')" class="">Category</a></th>
                    <th style="width: 300px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax/0')" class="">Sub Category</a></th>
                    <th class="text-center" style="">Item Name <span class="rflabelsteric"><strong>*</strong></span></th>



                    <!--
                    <th class="text-center" style="width:100px;">Action</th>
                    <!-->
                </tr>
                </thead>
                <tbody class="addMoreDemandsDetailRows_1" id="addMoreDemandsDetailRows_1">
                <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="1" />
                <tr>
                    <td>
                        <select name="category_id_1_1" id="CategoryId" onchange="get_sub_category()" class="form-control requiredField select2">
                            <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                        </select>
                    </td>
                    <td>
                        <select   name="sub_item_id_1_1" id="sub_category" class="form-control requiredField select2">
                        </select>
                    </td>

                    <td><input type="text" class="form-control ac4" id="ac3"/> </td>
                    <!--
                    <td class="text-center">---</td>
                    <!-->
                </tr>

                <tr>
                    <td>
                        <select  name="category_id_1_1" id="CategoryId" onchange="get_sub_category()" class="form-control requiredField select2">
                            <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                        </select>
                    </td>
                    <td>
                        <select   name="sub_item_id_1_1" id="sub_category" class="form-control requiredField select2">
                        </select>
                    </td>

                    <td><input onkeyup="openModal()" type="text" class="form-control ac4" id="ac4"/> </td>
                    <!--
                    <td class="text-center">---</td>
                    <!-->
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
    <script type="text/javascript">

        $('.select2').select2();

    var current_id=0;
        $(function() {
            $(".ac4").autocomplete({
                url: '{{'/pdc/search'}}',
                minChars: 1,
                useDelimiter: true,
                selectFirst: true,
                autoFill: true,
                useCache: false,

            });

        });


        function openModal()
        {
            $(".ac4").keydown(function(event) {
                if(event.which == 113) { //F2
                    alert('amir');
                    return false;
                }
                else if(event.which == 114) { //F3
                    alert('salman');
                    return false;
                }
            });
        }


       
    </script>



    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection