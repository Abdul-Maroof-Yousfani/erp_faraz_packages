
    <style>
        .my-lab label {
            padding-top: 0px;
        }
    </style>

                                  
                                        <div class="row">
                                        
                                                    <div class="col-md-12 padt pos-r">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Type</label>
                                                                    <input disabled class="form-control" type="text" value="{{ str_replace('_', ' ', $LcAndLg->type) }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Account Name</label>
                                                                    <input disabled class="form-control" type="text" value="{{ $LcAndLg->name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Limit</label>
                                                                    <input disabled  class="form-control" type="number"  value="{{ $LcAndLg->limit }}">
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                   
         
