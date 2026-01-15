@extends('layouts.default')
@section('content')
<div class="well_N">
    <h1>List of Brands</h1>

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
            @foreach($brands as $key => $brand)
                <tr class="text-center">
                    <td>{{ ++$key }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>{{ $brand->description }}</td>
                    <td><a href="{{ route('brands.edit', $brand->id) }}">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
