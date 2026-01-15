<?php

namespace App\Http\Controllers;

use App\Helpers\ReuseableCode;
use App\Models\MakeProduct;
use App\Models\MakeProductData;
use App\Models\Recipe;
use App\Models\RecipeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MakeProductController extends Controller
{
    public function createMakeProductForm()
    {
        $recipe = Recipe::where('status', 1)->get();
        // dd($recipe);
        return view('Inventory.makeproduct.create', compact('recipe'));
    }

    public function getRecipeData($id)
    {
        $recipe = Recipe::find($id); //where('status', 1)->get();
        $recipeData = $recipe->recipeDatas;
        foreach ($recipeData as $key => $data) {
            $recipeData[$key]['rate'] = ReuseableCode::average_cost_sales($data->item_id, 5, 0);
        }
        // echo "<pre>";
        // print_r($recipeData);
        return view('Inventory.makeproduct.viewRecipeData', compact('recipeData'));
    }

    public function addMakeProductDetail(Request $request)
    {
        DB::beginTransaction();
        $makeProductLastId = MakeProduct::orderBy('id', 'desc')->first();
        $mp_no = 'MP' . str_pad((($makeProductLastId) ? $makeProductLastId->id : 0)  + 1, 3, "0", STR_PAD_LEFT);
        try {
            $recipeItemDetail = Recipe::find($request->recipe_id)->subItem;
            $makeProduct['mp_no']                 = $mp_no;
            $makeProduct['recipe_id']             = $request->recipe_id;
            $makeProduct['sub_item_name']         = Recipe::find($request->recipe_id)->subItem->sub_ic;
            $makeProduct['quantity']              = $request->quantity;
            $makeProduct['electricity_expense']   = $request->electricity_expense;
            $makeProduct['labour_expense']        = $request->labour_expense;
            $makeProduct['expense']               = $request->expense;
            $makeProduct['average_cost']          = $request->totalrateInput;
            $makeProduct['created_by']            = Auth::user()->name;
            $makeProductId = MakeProduct::insertGetId($makeProduct);


            ReuseableCode::postStock($makeProductId, 0, $mp_no, date('Y-m-d'), 1, $request->totalrateInput, $recipeItemDetail->id, $recipeItemDetail->sub_ic, $request->quantity);

            foreach ($request->item_id as $key => $item_id) {

                $recipeDataItemDetail = RecipeData::find($request->recipeDataId[$key])->subItem;

                $makeProductData['make_product_id']   = $makeProductId;
                $makeProductData['recipe_data_id']    = $request->recipeDataId[$key];
                $makeProductData['sub_item_name']     = $recipeDataItemDetail->sub_ic;
                $makeProductData['uom']               = $request->uom_id[$key];
                $makeProductData['actual_qty']        = $request->actual_qty[$key];
                $makeProductData['total_qty']         = $request->total_qty[$key];
                $makeProductData['rate_per_qty']      = $request->rate_per_qty[$key];
                $makeProductData['created_by']        = Auth::user()->name;
                $makeProductDataId = MakeProductData::insertGetId($makeProductData);

                ReuseableCode::postStock($item_id, $makeProductDataId, $mp_no, date('Y-m-d'), 5, $request->total_rate[$key], $recipeDataItemDetail->id, $recipeDataItemDetail->sub_ic, $request->total_qty[$key]);
            }
            DB::commit();
            return redirect('makeProduct/productlist');

        } catch (\Exception $th) {
            DB::rollback();
            echo "error";
            dd($th);
        }
    }

    public function productlist()
    {
        $makeProduct = MakeProduct::where('status', 1)->get();
        return view('Inventory.makeproduct.productList',compact('makeProduct'));
    }
    public function viewProductList(Request $request)
    {
        $makeProduct = MakeProduct::find($request->id);
        return view('Inventory.makeproduct.viewproduct', compact('makeProduct'));
    }

    public function deleteList(Request $request)
    {
        $data['created_by'] = Auth::user()->name;
        $data['status'] = 0;
        Recipe::find($request->id)->update($data);
        echo $request->id;
    }
}
