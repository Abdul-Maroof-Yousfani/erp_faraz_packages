@extends('layouts.default')
@section('content')
<div class="well_N">
    <h2>Edit Mixture Machine</h2>
    <form action="{{ route('mixture_machines.update',$mixture_machine->id) }}" method="POST">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">
        <div class="form-group">
            <label for="name">Mixture Machine Name</label>
            <input type="text" name="name" id="name" value="{{ $mixture_machine->name }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Mixture Machine Description</label>
            <textarea name="description" id="description"  class="form-control">{{ $mixture_machine->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>

    </form>
</div>
@endsection
