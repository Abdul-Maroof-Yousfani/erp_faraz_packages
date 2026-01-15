<div class="dropdown theme-btn">    
    <button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action  <span class="caret"></span></button>
    <ul class="dropdown-menu">                
        <li><a onclick="showDetailModelOneParamerter('fdc/viewBankListDetail','{{ $data->id}}','View Bank Detail')"><span class="glyphicon glyphicon-edit"></span> View</a></li>
        <li><a href="{{ route('bank.edit', ['bank' => $data->id, 'pageType' => request()->get('pageType'), 'parentCode' => request()->get('parentCode'), 'm' => request()->get('m')]) }}#Rototec"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>

        <li><a><span class="glyphicon glyphicon-edit"></span> Delete</a></li>        
    </ul>
</div>