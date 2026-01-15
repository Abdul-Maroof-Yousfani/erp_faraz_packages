<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {

        $brands = Brand::where('status',1)->get();
        return view('Purchase.Brand.brand_list', compact('brands'));
    }

    public function create()
    {
        return view('Purchase.Brand.add_brand');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Brand::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status'=>'1'
        ]);

        return redirect()->route('brands.create')->with('success', 'Brand created successfully.');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('Purchase.Brand.edit_brand', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }
}
