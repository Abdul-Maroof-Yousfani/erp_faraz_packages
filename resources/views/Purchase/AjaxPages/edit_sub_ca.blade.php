<?php

use App\Helpers\CommonHelper;
?>

<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Edit Sub Category</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <?php echo Form::open(array('url' => 'pad/edit_sub?id='.$id,'id'=>'addCategoryForm'));?>


                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>Category</label>
                                                <input type="text" name=""  id="" value="{{CommonHelper::get_category_name($data->category_id)}}" readonly class="form-control" onkeyup="" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>Sub Category Name</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="SubCategoryName"  id="SubCategoryName" value="{{$data->sub_category_name}}" class="form-control requiredField" onkeyup="" />
                                                <span id="DuplicateMsg"></span>
                                            </div>
                                            <div>&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <button type="submit" class="btn btn-success" id="BtnSub">Submit</button>
                                                <button  type="reset" id="reset" class="btn btn-primary">Clear Form</button>

                                                <?php
                                                //echo Form::submit('Click Me!');
                                                ?>
                                            </div>
                                            <?php
                                            echo Form::close();
                                            ?>
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