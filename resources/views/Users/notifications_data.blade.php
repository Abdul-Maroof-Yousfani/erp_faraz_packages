<style>
    .width{
        width: 30%
    }
</style>
                                    <table class="table table-bordered sf-table-list" id="notify">
                                    
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @if(count($notifications_data)>0)
                                        @foreach($notifications_data as  $row)
                                            <tr>
                                                <td>Email 1</td>
                                                <td class="text-center"><input class="form-control" type="email" value="{{ $row->email_1 }}" name="email_1" id="email_1"/></td>
                                                <td class="text-center"><textarea class="form-control"  name="body_1" id=""  cols="5" rows="3">{{ $row->body_1 }}</textarea></td>
                                            </tr>

                                            <tr>
                                                <td>Email 2</td>
                                                <td class="text-center"><input class="form-control"  type="email" value="{{ $row->email_2 }}" name="email_2" id="email_1"/></td>
                                                <td class="text-center"><textarea class="form-control"  name="body_2" id="" cols="5" rows="3">{{ $row->body_2 }}</textarea></td>
                                            </tr>

                                            <tr>
                                                <td>Email 3</td>
                                                <td class="text-center"><input class="form-control"  type="email" value="{{ $row->email_3 }}" name="email_3" id="email_1"/></td>
                                                <td class="text-center"><textarea class="form-control"  name="body_3" id="" cols="5" rows="3">{{ $row->body_3 }}</textarea></td>
                                            </tr>
                                            <input type="hidden" name="id" value="{{ $row->id }}"/>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td>Email 1</td>
                                            <td class="text-center width"><input required class="form-control" type="email" value="" name="email_1" id="email_1"/></td>
                                            <td class="text-center"><textarea required class="form-control"  name="body_1" id="" cols="5" rows="3"></textarea></td>
                                        </tr>   

                                        <tr>
                                            <td>Email 2</td>
                                            <td  class="text-center"><input  class="form-control"  type="email" value="" name="email_2" id="email_1"/></td>
                                            <td class="text-center"><textarea  class="form-control"  name="body_2" id="" cols="5" rows="3"></textarea></td>
                                        </tr>

                                        <tr>
                                            <td>Email 3</td>
                                            <td class="text-center"><input  class="form-control"  type="email" value="" name="email_3" id="email_1"/></td>
                                            <td class="text-center"><textarea  class="form-control"  name="body_3" id="" cols="5" rows="3"></textarea></td>
                                        </tr>
                                        <input type="hidden" name="id" value="{{ 0 }}"/>
                                        @endif
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="step_id" value="{{ Request::get('steps') }}"/>
                                    <input type="hidden" name="behavior" value="{{ Request::get('behavior') }}"/>
                                    <input type="hidden" name="dept" value="{{ Request::get('dept') }}"/>
                                    <input style="float: right" type="submit" class="text-right btn btn-success" value="submit"/>


              

