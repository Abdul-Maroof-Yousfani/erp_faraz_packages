<div id="pabel2" class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="buildyourform" class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>

                            </th>
                            <th class="text-center" style="width:150px;">Debit <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center" style="width:150px;">Credit <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center" style="width:150px;">Action</th>
                        </tr>
                        </thead>
                        <tbody class="addMoreJvsDetailRows_1" id="addMoreJvsDetailRows_1">
                        <?php for($j = 1 ; $j <= 2 ; $j++){?>
                        <input type="hidden" name="jvsDataSection_1[]" class="form-control" id="jvsDataSection_1" value="<?php echo $j?>" />
                        <tr>
                            <td>
                                <select class="form-control requiredField select2" name="account_id_1_<?php echo $j?>" id="account_id_1_<?php echo $j?>">
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $key => $y)
                                        <option value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit" class="form-control d_amount_1 requiredField" maxlength="15" min="0" type="any" name="d_amount_1_<?php echo $j ?>" id="d_amount_1_<?php echo $j ?>" onkeyup="sum('1')" value="" required="required"/>
                            </td>
                            <td>
                                <input onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control c_amount_1 requiredField" maxlength="15" min="0" type="any" name="c_amount_1_<?php echo $j ?>" id="c_amount_1_<?php echo $j ?>" onkeyup="sum('1')" value="" required="required"/>
                            </td>
                            <td class="text-center">---</td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td></td>
                            <td style="width:150px;">
                                <input
                                        type="number"
                                        readonly="readonly"
                                        id="d_t_amount_1"
                                        maxlength="15"
                                        min="0"
                                        name="d_t_amount_1"
                                        class="form-control requiredField text-right"
                                        value=""/>
                            </td>
                            <td style="width:150px;">
                                <input
                                        type="number"
                                        readonly="readonly"
                                        id="c_t_amount_1"
                                        maxlength="15"
                                        min="0"
                                        name="c_t_amount_1"
                                        class="form-control requiredField text-right"
                                        value=""/>
                            </td>
                            <td class="diff" style="width:150px;font-size: 20px;"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>