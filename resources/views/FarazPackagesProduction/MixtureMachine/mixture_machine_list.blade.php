@extends('layouts.default')
@section('content')
<div class="well_N">
    <h1>List of Mixture Machines</h1>

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
            @foreach($mixture_machines as $key => $mixture_machine)
                <tr class="text-center">
                    <td>{{ ++$key }}</td>
                    <td>{{ $mixture_machine->name }}</td>
                    <td>{{ $mixture_machine->description }}</td>
                    <td><a href="{{ route('mixture_machines.edit', $mixture_machine->id) }}">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
