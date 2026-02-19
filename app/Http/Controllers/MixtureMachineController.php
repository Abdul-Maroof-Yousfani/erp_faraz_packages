<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MixtureMachine;

class MixtureMachineController extends Controller
{
    public function index()
    {

        $mixture_machines = MixtureMachine::where('status',1)->get();
        return view('FarazPackagesProduction.MixtureMachine.mixture_machine_list', compact('mixture_machines'));
    }

    public function create()
    {
        return view('FarazPackagesProduction.MixtureMachine.add_mixture_machine');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        MixtureMachine::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status'=>'1'
        ]);

        return redirect()->route('mixture_machines.create')->with('success', 'MixtureMachine created successfully.');
    }

    public function edit($id)
    {
        $mixture_machine = MixtureMachine::findOrFail($id);
        return view('FarazPackagesProduction.MixtureMachine.edit_mixture_machine', compact('mixture_machine'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $mixture_machine = MixtureMachine::findOrFail($id);
        $mixture_machine->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('mixture_machines.index')->with('success', 'MixtureMachine updated successfully.');
    }

    public function destroy($id)
    {
        $mixture_machine = MixtureMachine::findOrFail($id);
        $mixture_machine->delete();

        return redirect()->route('mixture_machines.index')->with('success', 'MixtureMachine deleted successfully.');
    }
}
