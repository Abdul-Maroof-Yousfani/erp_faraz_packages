<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Requests;
use App\Models\AssetsCategory;
use App\Models\AssetsDesignedLife;
use App\Models\AssetsFrequency;
use App\Models\AssetsManufacturer;
use App\Models\AssetsProjectPremises;
use App\Models\AssetsSubCategory;
use App\Models\Floors;
use Illuminate\Http\Request;
use DB;
use Auth;

class MasterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.home');
    }
    public function category()
    {
        return view('Master.category');
    }

    public function createMasterForm()
    {
        $categories = AssetsCategory::where([['status','=', 1]])->orderBy('id')->get();
        return view('Master.createMasterForm', compact('categories'));
    }

    public function masterItemsList()
    {
        return view('Master.masterItemsList');
    }

    public function getMasterItemData(Request $request)
    {
        $model = $request->models;
        $data = null;

        if ($model == 'AssetsCategory') {
            $data = AssetsCategory::where([['status', '=', 1]])->get();
            return view('Master.category.viewCategoryList', compact('data'));

        } elseif ($model == 'AssetsSubCategory') {
            $data = AssetsSubCategory::where([['status', '=', 1]])->get();
            $categories = AssetsCategory::where([['status', '=', 1]])->get();
            $category_array = [];
            foreach($categories as $key => $value):
                $category_array[$value->id] = $value;
            endforeach;
            return view('Master.subCategory.viewSubCategoryList', compact('data','category_array'));

        } elseif ($model == 'AssetsProjectPremises') {
            $data = AssetsProjectPremises::where([['status', '=', 1]])->get();
            return view('Master.premises.viewPremisesList', compact('data'));

        } elseif ($model == 'Floors') {
            $data = Floors::where([['status', '=', 1]])->get();
            return view('Master.floor.viewFloorsList', compact('data'));

        } elseif ($model == 'AssetsManufacturer') {
            $data = AssetsManufacturer::where([['status', '=', 1]])->get();
            return view('Master.manufacturer.viewManufacturerList', compact('data'));

        } elseif ($model == 'AssetsDesignedLife') {
            $data = AssetsDesignedLife::where([['status', '=', 1]])->get();
            return view('Master.designedLife.viewDesignedLifeList', compact('data'));

        } elseif ($model == 'AssetsFrequency') {
            $data = AssetsFrequency::where([['status', '=', 1]])->get();
            return view('Master.frequency.viewFrequencyList', compact('data'));

        }
    }

    public function viewMasterList(Request $request)
    {
        if ($request->ajax()) {
            return $this->getMasterItemData($request);
        }
        return view('Master.viewMasterList');
    }

    public function addPremises(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            AssetsProjectPremises::create($request);
            DB::commit();
            return redirect('create-master-items')->with('success','Your changes have been saved');
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function editPremises(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $exists = AssetsProjectPremises::find($request['id']);
            if ($exists) {
                $exists->update($request);
            }
            DB::commit();
            return "true";
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addCategory(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            AssetsCategory::create($request);
            DB::commit();
            return redirect('create-master-items')->with('success','Your changes have been saved');
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function editCategory(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $exists = AssetsCategory::find($request['id']);
            if ($exists) {
                $exists->update($request);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Your changes have been saved']);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addSubCategory(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            AssetsSubCategory::create($request);
            DB::commit();
            return redirect('create-master-items')->with('success','Your changes have been saved');
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function editSubCategory(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $exists = AssetsSubCategory::find($request['id']);
            if ($exists) {
                $exists->update($request);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Your changes have been saved']);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addFloor(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            Floors::create($request);
            DB::commit();
            return redirect('create-master-items')->with('success','Your changes have been saved');
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function editFloor(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $exists = Floors::find($request['id']);
            if ($exists) {
                $exists->update($request);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Your changes have been saved']);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addManufacturer(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            AssetsManufacturer::create($request);
            DB::commit();
            return redirect('create-master-items')->with('success','Your changes have been saved');
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function editManufacturer(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $exists = AssetsManufacturer::find($request['id']);
            if ($exists) {
                $exists->update($request);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Your changes have been saved']);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addUseFulLife(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            AssetsDesignedLife::create($request);
            DB::commit();
            return redirect('create-master-items')->with('success','Your changes have been saved');
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function editUseFulLife(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $exists = AssetsDesignedLife::find($request['id']);
            if ($exists) {
                $exists->update($request);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Your changes have been saved']);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addFrequency(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            AssetsFrequency::create($request);
            DB::commit();
            return redirect('create-master-items')->with('success','Your changes have been saved');
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function editFrequency(Request  $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $exists = AssetsFrequency::find($request['id']);
            if ($exists) {
                $exists->update($request);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Your changes have been saved']);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function editCategoryForm(Request $request)
    {
        $data = AssetsCategory::where([['id', '=', $request->id]])->first();
        return view('Master.category.editCategoryForm', compact('data'));
    }

    public function editSubCategoryForm(Request $request)
    {
        $categories = AssetsCategory::where([['status', '=', 1]])->get();
        $data = AssetsSubCategory::where([['id', '=', $request->id]])->first();
        return view('Master.subCategory.editSubCategoryForm', compact('data','categories'));
    }

    public function editFloorsForm(Request $request)
    {
        $data = Floors::where([['id', '=', $request->id]])->first();
        return view('Master.floor.editFloorsForm', compact('data'));
    }

    public function editPremisesForm(Request $request)
    {
        $data = AssetsProjectPremises::where([['id', '=', $request->id]])->first();
        return view('Master.premises.editPremisesForm', compact('data'));
    }

    public function editManufacturerForm(Request $request)
    {
        $data = AssetsManufacturer::where([['id', '=', $request->id]])->first();
        return view('Master.manufacturer.editManufacturerForm', compact('data'));
    }

    public function editFrequencyForm(Request $request)
    {
        $data = AssetsFrequency::where([['id', '=', $request->id]])->first();
        return view('Master.frequency.editFrequencyForm', compact('data'));
    }

    public function editUseFulLifeForm(Request $request)
    {
        $data = AssetsDesignedLife::where([['id', '=', $request->id]])->first();
        return view('Master.designedLife.editUseFulLifeForm', compact('data'));
    }

    public function deleteTableRecord(Request $request)
    {
        CommonHelper::companyDatabaseConnection($request->m);
        DB::table($request->table_name)->where('id', $request->id)->update(['status' => 2]);
        CommonHelper::reconnectMasterDatabase();
        return response()->json(['success' => true, 'message' => 'Your changes have been saved']);
    }
}
