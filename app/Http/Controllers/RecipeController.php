<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\RecipeData;
use App\Models\ProductionBom;
use App\Models\ProductionBomData;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public $m;
    public function __construct()
    {
        $this->m = Session::get('run_company');
    }
    public function addRecipe(Request $request)
    {
        $categories_id = explode(',',Auth::user()->categories_id);
        $sub_item = DB::Connection('mysql2')->table('category as c')
        ->leftJoin('sub_category as sc', 'c.id', '=', 'sc.category_id')
        ->join('subitem as s', 'c.id', '=', 's.main_ic_id')
        ->join(env('DB_DATABASE').'.uom as u', 's.uom', '=', 'u.id')
        ->where('sc.status', '=', 1)
        ->where('c.status', '=', 1)
        ->where('s.status', '=', 1)
        ->where('u.status', '=', 1)
        ->where('s.main_ic_id', '=', 8)
        ->select('s.id', 's.sub_ic','s.uom','s.item_code','u.uom_name','s.hs_code_id')
        // ->whereIn('c.id', $categories_id)
        ->groupBy('s.item_code')
        ->orderBy('s.id')
        ->get();

        $raw_material = DB::Connection('mysql2')->table('subitem')
        ->select('id', 'sub_ic','uom','item_code')
        ->where('status', '=', 1)->where('main_ic_id', '=', 7)->get();

        $color = DB::Connection('mysql2')->table('subitem')->where('status', '=', 1)
        ->select('color')
        ->groupBy('color')
        ->get();

        $formulation_no = CommonHelper::generateFormulationNumber();
        
        // Check if copying from existing recipe
        $copied_recipe_data = null;
        if($request->has('copy_id') && !empty($request->copy_id)) {
            $copied_recipe_data = ProductionBomData::where('main_id', $request->copy_id)
                ->where('status', 1)
                ->with(['subItem' => function($query) {
                    $query->select('id', 'sub_ic', 'item_code', 'uom');
                }, 'subItem.uomData:id,uom_name'])
                ->get();
        }
        
        return view('Inventory.recipe.addRecipe',compact('sub_item','raw_material','color','formulation_no','copied_recipe_data'));
    }

    public function insertRecipe(Request $request)
    {
        
        DB::connection('mysql2')->beginTransaction();

            try {
                $recipe = new ProductionBom();
                $recipe->setConnection('mysql2');

                $validator = Validator::make($request->all(), [
                    'finish_goods' => 'required',
                    'description' => 'required',
                    'receipe_name' => 'required',
                    'qty' => 'required',
                    'formulation_no' => 'required',
                    'color' => 'required',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $recipe->finish_goods = $request->finish_goods;
                $recipe->description = $request->description;
                $recipe->receipe_name = $request->receipe_name;
                $recipe->formulation_no = CommonHelper::generateFormulationNumber();
                $recipe->color = $request->color;
                $recipe->qty = $request->qty;
                $recipe->username = Auth::user()->name;
                $recipe->date = date("Y-m-d");
                $recipe->status = 1;
                $recipe->save();

                $recipeId = $recipe->id;

                $data = $request->item_id;
                
                if (empty($data[0])) {
                    DB::connection('mysql2')->rollBack();
                    return redirect()->back()->withErrors('category quantity are required')->withInput();
                }
                foreach ($data as $key => $row) {

                    $validator = Validator::make(
                        [
                            'item' => $request->item_id[$key] ,
                            'required_qty' => $request->required_qty[$key] ,
                        ]
                        , [
                        'required_qty' => 'required',
                    ]);
            
                    if ($validator->fails()) {
                        DB::connection('mysql2')->rollBack();
                        return redirect()->back()->withErrors($validator)->withInput();
                    }

                    $RecipeData = new ProductionBomData();
                    $RecipeData->main_id = $recipeId;
                    $RecipeData->item_id = $row;
                    $RecipeData->category_total_qty = $request->required_qty[$key] ?? 0;
                    $RecipeData->category_id = 0;
                    $RecipeData->status = 1;
                    $RecipeData->type = 1;
                    $RecipeData->username = Auth::user()->name;
                    $RecipeData->save();
                }

                DB::connection('mysql2')->commit();
            } catch (\Exception $ex) {
                DB::connection('mysql2')->rollBack();
                return self::addRecipe($request)->withErrors($ex->getMessage());
            }
    
        return redirect('recipe/recipeList');
    }

    public function recipeList()
    {
        $recipeList = ProductionBom::where('status', '!=',0)->get();
        $m = $this->m;
        // $expenseList = Expense::with(['expenseItem:id,name', 'expenseCategory:id,name'])->where('status', 1)->get();
      return view('Inventory.recipe.viewRecipe', compact('recipeList', 'm'));
    }
    public function recipeDelete(Request $request)
    {
        // $data['created_by'] = Auth::user()->name;
        $data['status'] = 0;
        // Recipe::find($request->id)->update($data);
        $recipe = ProductionBom::find($request->id)->update($data);
        $recipeData = ProductionBomData::where('main_id',$request->id)->update($data);
        echo $request->id;

    }


    public function viewRecipeInfo(Request $request)
    {
        $recipe = ProductionBom::where('id', $request->id)->where('status', 1)->orWhere('finish_goods', $request->id)->first();
        return view('Inventory.recipe.viewRecipeInfo',compact('recipe'));
    }

    public function recipeDataItemDelete(Request $request)
    {
        $data['created_by'] = Auth::user()->name;
        $data['status'] = 0;
        $recipe = ProductionBom::find($request->id)->update($data);
        $recipeData = ProductionBomData::where('main_id',$request->id)->update($data);
        // RecipeData::find($request->id)->update($data);
         return $request->id;
    }
    public function recipeEdit(Request $request)
    {
        $m = $request->m;
        $recipe = ProductionBom::where('status', 1)->where('id',$request->id)->first();
        $recipeData = ProductionBomData::where('status', 1)->where('main_id',$recipe->id)->get();
        
        $categories_id = explode(',',Auth::user()->categories_id);
        $sub_item = DB::Connection('mysql2')->table('category as c')
        ->join('sub_category as sc', 'c.id', '=', 'sc.category_id')
        ->join('subitem as s', 'sc.id', '=', 's.sub_category_id')
        ->join(env('DB_DATABASE').'.uom as u', 's.uom', '=', 'u.id')
        ->where('sc.status', '=', 1)
        ->where('c.status', '=', 1)
        ->where('s.status', '=', 1)
        ->where('u.status', '=', 1)
        ->where('s.main_ic_id', '=', 8)
        ->select('s.id', 's.sub_ic','s.uom','s.item_code','u.uom_name','s.hs_code_id')
        // ->whereIn('c.id', $categories_id)
        ->groupBy('s.item_code')
        ->orderBy('s.id')
        ->get();

        $raw_material = DB::Connection('mysql2')->table('subitem')
        ->select('id', 'sub_ic','uom','item_code')
        ->where('status', '=', 1)->where('main_ic_id', '=', 7)->get();

        $color = DB::Connection('mysql2')->table('subitem')->where('status', '=', 1)
        ->select('color')
        ->groupBy('color')
        ->get();
        return view('Inventory.recipe.editRecipe', compact('recipe', 'recipeData','sub_item','raw_material','color', 'm'));
    }

    public function UpdateRecipe(Request $request)
    {
        $m = $this->m;
        $request['created_by'] = Auth::user()->name;
        $request['status'] = 1;

        $recipe = ProductionBom::where('id', $request->recordId)->update([

            'finish_goods' => $request->finish_goods,
            'description' => $request->description,
            'receipe_name' => $request->receipe_name,
            'formulation_no' => $request->formulation_no,
            'color' => $request->color,
            'qty' => $request->qty,
            'username' => Auth::user()->name,
            'date' => date("Y-m-d"),
            'status' => 1,
        ]);

        ProductionBomData::where('main_id', $request->recordId)->delete();
        $data = $request->item_id;
        foreach ($data as $key => $row):
            $RecipeData = new ProductionBomData();
            $RecipeData->main_id = $request->recordId;
            $RecipeData->category_total_qty = $request->required_qty[$key];
            $RecipeData->item_id = $row;
            $RecipeData->category_id = 0;
            $RecipeData->status = 1;
            $RecipeData->type = 1;
            $RecipeData->username = Auth::user()->name;
            $RecipeData->save();
        endforeach;

        Session::flash('dataEdit', 'Successfully Updated');
        return redirect('recipe/recipeList');
    }

    public function changeFormulationStatus(Request $request)
    {
        $data = explode('--', $request->id);
        $id = $data[0];
        $status = $data[1];
        $m = $request->m;
        return view('Inventory.recipe.changeFormulationStatus',compact('id','status','m'));
    }

    public function addChangeFormulationStatusDetail(Request $request)
    {
        if($request->status == 1) {
            ProductionBom::where('id', $request->id)
                ->update(['status' => $request->status]);
        } else if($request->status == 2) {
            ProductionBom::where('id', $request->id)
                ->update(['status' => $request->status, 'disable_date' => $request->disable_date, 'disable_remarks' => $request->disable_remarks]);
        }
        return redirect('recipe/recipeList');
    }
    
}
