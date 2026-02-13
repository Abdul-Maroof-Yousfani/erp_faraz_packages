@extends('layouts.default')
@section('content')
<div class="well_N">
    <h1>List of Colors</h1>

    <table class="table table-bordered sf-table-list">
        <thead >
            <tr class="text-center">
                <th class="text-center">SR No</th>
                <th class="text-center">Name</th>
                <th class="text-center">Description</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($colors as $key => $color)
                <tr class="text-center">
                    <td>{{ ++$key }}</td>
                    <td>{{ $color->name }}</td>
                    <td>{{ $color->description }}</td>
                    <td><a href="{{ route('colors.edit', $color->id) }}">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
