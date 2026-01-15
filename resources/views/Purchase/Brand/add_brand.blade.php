@extends('layouts.default')
@section('content')
<div class="well_N">
    <h2>Create Brand</h2>
    <form action="{{ route('brands.store') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="name">Brand Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Brand Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>

    </form>
</div>
@endsection
