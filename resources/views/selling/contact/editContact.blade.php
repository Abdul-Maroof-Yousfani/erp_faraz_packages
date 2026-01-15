@extends('layouts.default')

@section('content')
@include('select2')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Inventory Master</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Contact Edit</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
         
        </div>
    </div>
    <?php 

    use App\Helpers\CommonHelper;
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                            <div class="headquid">
                           <h2 class="subHeadingLabelClass">Create Sale Tax Group</h2>
                        </div>
                               
                        <form  action="{{route('contactUpdate',$contact->id)}}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="row qout-h">
                                            
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-12">
                                                
                                                        <div class="alert hide alert-success">
                                                    
                                                        </div>
                                                        
                                                        <div class="alert alert-danger hide  print-error-msg">
                                                            <ul></ul>
                                                        </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div>
                                                                <label for="">First Name</label>
                                                                <input type="text" name="first_name" value="{{$contact->first_name}}" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <label for="">Last Name</label>
                                                                <input type="text" name="last_name" value="{{$contact->last_name}}" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <label for="">Gender</label>
                                                                <select name="gender" id="" class="form-control">
                                                                    <option @if($contact->gender ==  1) selected @endif value="1">Male</option>
                                                                    <option @if($contact->gender ==  2) selected @endif value="2">Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <label for="">Personal Title</label>
                                                                <input type="text" value="{{$contact->personal_title}}" name="title" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <label for="">Cell</label>
                                                                <input type="text" value="{{$contact->cell}}" name="cell" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <label for="">Phone</label>
                                                                <input type="text" value="{{$contact->phone}}" name="phone" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <label for="">Email</label>
                                                                <input type="text" value="{{$contact->email}}" name="email" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <label for="">Website</label>
                                                                <input type="text" value="{{$contact->website}}" name="website" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <label for="">Job Title</label>
                                                                <div class="pos-rel">
                                                                <select name="job_title" id="job_title" class="form-control">        
                                                                    @foreach(CommonHelper::get_table_data('job_titles') as $item)
                                                                    <option @if($contact->job_title ==  $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <a href="#" class="btn-add" onclick="createJobtitle('createJobtitle','','Add Job Title','')">+</a> --}}
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  
                                                
                                              
                                            </div>


                        
                                            <div class="col-md-12  text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                    <button type="button" class="btnn btn-secondary " data-dismiss="modal">Cancel</button>
                                                    
                                                </div>    
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
    
            $('#account_id').select2();
        });
    </script>

@endsection
