<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;

class ColorController extends Controller
{
    public function index()
    {

        $colors = Color::where('status',1)->get();
        return view('Purchase.Color.color_list', compact('colors'));
    }

    public function create()
    {
        return view('Purchase.Color.add_color');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Color::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status'=>'1'
        ]);

        return redirect()->route('colors.create')->with('success', 'Color created successfully.');
    }

    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('Purchase.Color.edit_color', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $color = Color::findOrFail($id);
        $color->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('colors.index')->with('success', 'Color updated successfully.');
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();

        return redirect()->route('colors.index')->with('success', 'Color deleted successfully.');
    }
}
