<form id="submit" action="{{ route('updateColumnSettings') }}" method="post">
    <div class="mt">
        <input type="hidden" name="list_id" id="list_id" value="assets_list">
        <input type="hidden" name="ajaxLoadUrl" id="ajaxLoadUrl" value="{{ route('assets-list') }}">
        <input type="hidden" name="modalId" id="modalId" value="showModal">
        <input type="hidden" name="flag" id="flag" value="1">
        @foreach ($columns as $column)
            <div class="title-wrapper bor-b">
                <div class="custom-control custom-checkbox">
                    <input
                            type="checkbox"
                            class="custom-control-input"
                            id="customCheck{{ $loop->iteration }}"
                            name="columns[]"
                            value="{{ $column }}"
                            {{ in_array($column, json_decode($selected_columns->first() ?? '[]')) ? 'checked' : '' }}
                    >
                    <label class="custom-control-label" for="customCheck{{ $loop->iteration }}">
                        &nbsp;{{ ucfirst(str_replace('_', ' ', $column)) }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">&nbsp;</div>
    <div class="mt text-center">
        <button type="submit" class="btn btn-primary mr-1">Select</button>
    </div>
</form>
<script src="{{ URL::asset('assets/custom/js/customFunctions.js') }}"></script>