@extends('layouts.default')
@section('content')
<div class="well_N">
    <h2>Edit Color</h2>
    <form action="{{ route('colors.update',$color->id) }}" method="POST">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">
        <div class="form-group">
            <label for="name">Color Name</label>
            <input type="text" name="name" id="name" value="{{ $color->name }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Color Description</label>
            <textarea name="description" id="description"  class="form-control">{{ $color->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>

    </form>
</div>
@endsection
