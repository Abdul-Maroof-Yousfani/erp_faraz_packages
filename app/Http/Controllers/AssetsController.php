<?php
namespace App\Http\Controllers;

use App\Helpers\AssetsHelper;
use App\Models\AssetsUom;
use App\Models\Department;
use App\Models\UserColumnPreference;
use App\Models\UsersImport;
use Illuminate\Routing\Controller;

use App\Models\Assets;
use App\Models\AssetsCategory;
use App\Models\AssetsCondition;
use App\Models\AssetsDesignedLife;
use App\Models\AssetsFrequency;
use App\Models\AssetsManufacturer;
use App\Models\AssetsProject;
use App\Models\AssetsProjectPremises;
use App\Models\AssetsRiskLikelihood;
use App\Models\AssetsSubCategory;
use App\Models\Floors;
use App\Models\WorkingStatus;
use App\Models\AssetsPreventive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Input;
use Auth;
use DB;
use Config;
use \Cache;

use App\Helpers\CommonHelper;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class AssetsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createAssetsForm()
    {
        $departments = Department::where('status', 1)->get();
        $premises = AssetsProjectPremises::where('status', 1)->get();
        $categories = AssetsCategory::where('status', 1)->get();
        $floors = Floors::where('status', 1)->get();
        $frequencies = AssetsFrequency::where('status', 1)->get();
        $manufacturers = AssetsManufacturer::where('status', 1)->get();
        $sub_categories = AssetsSubCategory::where('status', 1)->get();
        $life = AssetsDesignedLife::where('status', 1)->get();
        return view('assets.createAssetsForm', compact('categories','premises','floors','frequencies','manufacturers','life','departments'));
    }

    public function editAssetsForm(Request $request)
    {
        $departments = Department::where('status', 1)->get();
        $categories = AssetsCategory::where('status', 1)->get();
        $sub_categories = AssetsSubCategory::where('status', 1)->get();
        $premises = AssetsProjectPremises::where('status', 1)->get();
        $floors = Floors::where('status', 1)->get();
        $frequencies = AssetsFrequency::where('status', 1)->get();
        $manufacturers = AssetsManufacturer::where('status', 1)->get();
        $life = AssetsDesignedLife::where('status', 1)->get();
        $assets = Assets::with('assetPreventive')->where('id', $request->id)->first();
        return view('assets.editAssetsForm', compact('assets','categories','sub_categories','premises','floors','frequencies','manufacturers','life','departments'));
    }

    public function viewAssetsList(Request $request)
    {
        if ($request->ajax()):
            // dd('asds');
            $acc_type = Auth::user()->acc_type;
            // $user_columns = UserColumnPreference::where([
            //     ['user_id', '=', Auth::user()->id],
            //     ['list_id', '=', 'assets_list']
            // ])->pluck('column_names');
            // $selected_columns = json_decode($user_columns->first());
            // if(!$selected_columns) {
            //     $columnsOfInterest = \App\Models\Assets::getColumnsOfInterest();
            //     $selected_columns = array_intersect($columnsOfInterest, \Schema::getColumnListing((new \App\Models\Assets)->getTable()));
            //     echo "<pre>"; print_r($columnsOfInterest); die;
            // }
          
            $categories = AssetsCategory::where('status', 1)->get();
            $category_array = [];
            foreach($categories as $key => $val):
                $category_array[$val->id] = $val;
            endforeach;

            $sub_categories = AssetsSubCategory::where('status', 1)->get();
            $sub_category_array = [];
            foreach($sub_categories as $key => $val):
                $sub_category_array[$val->id] = $val;
            endforeach;

            $premises = AssetsProjectPremises::where('status', 1)->get();
            $premises_array = [];
            foreach($premises as $key => $val):
                $premises_array[$val->id] = $val;
            endforeach;

            $life = AssetsDesignedLife::where('status', 1)->get();
            $life_array = [];
            foreach($life as $key => $val):
                $life_array[$val->id] = $val;
            endforeach;

            $depreciation_method = ['1' => 'Straight Line', '2' => 'Reducing Balance Method'];

            CommonHelper::companyDatabaseConnection($request->m);
            (!empty($request->premise_id_search)) ? $query_string_second_part[] = " AND premise_id = '$request->premise_id_search' " : '';
            (!empty($request->sub_category_id_search)) ? $query_string_second_part[] = " AND sub_category_id = '$request->sub_category_id_search' " : '';
            (!empty($request->category_id_search)) ? $query_string_second_part[] = " AND category_id = '$request->category_id_search' " : '';
            $query_string_second_part[] = " AND status = 1 ";
            $query_string_First_Part = "SELECT * FROM assets WHERE ";
            $query_string_third_part = " ORDER BY id DESC";
            $query_string_second_part = implode(" ", $query_string_second_part);
            $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
            $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
            $assets = DB::select(DB::raw($query_string));
            CommonHelper::reconnectMasterDatabase();
            return view('assets.viewAssetsListDetail',compact('assets','premises_array','category_array','sub_category_array','life_array','depreciation_method'));
        endif;
        return view('assets.viewAssetsList');
    }

    public function assetsFilter()
    {
        $premises = AssetsProjectPremises::where('status', 1)->get();
        $categories = AssetsCategory::where('status', 1)->get();
        $sub_categories = AssetsSubCategory::where('status', 1)->get();
        return view('assets.assetsFilter', compact('premises','categories','sub_categories'));
    }

    public function assetsEditColumnsList()
    {
        $selected_columns = UserColumnPreference::where([['user_id','=', Auth::user()->id],['list_id','=', 'assets_list']])->pluck('column_names');
        $columnsOfInterest = \App\Models\Assets::getColumnsOfInterest();
        $columns = array_intersect($columnsOfInterest, \Schema::getColumnListing((new \App\Models\Assets)->getTable()));
        return view('assets.assetsEditColumnsList',compact('columns','selected_columns'));
    }

    public function createCategoryForm()
    {
        return view('assets.createCategoryForm');
    }

    public function createSubCategoryForm()
    {
        $categories = Cache::get('assets_category');
        return view('assets.createSubCategoryForm', compact('categories'));
    }

    public function addCategory(Request $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $categories = AssetsCategory::create($request);
            DB::commit();

            Cache::forget('assets_category');
            Cache::rememberForever('assets_category', function () {
                $values = AssetsCategory::where([['status','=', 1]])->orderBy('id')->get();
                $data_array = [];
                foreach($values as $key => $val):
                    $data_array[$val->id] = $val;
                endforeach;
                return $data_array;
            });
            return $categories;
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addSubCategory(Request $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $sub_categories = AssetsSubCategory::create($request);
            DB::commit();

            Cache::forget('assets_sub_category');
            Cache::rememberForever('assets_sub_category', function () {
                $values = AssetsSubCategory::where([['status','=', 1]])->orderBy('id')->get();
                $data_array = [];
                foreach($values as $key => $val):
                    $data_array[$val->id] = $val;
                endforeach;
                return $data_array;
            });
            return $sub_categories;
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function addAssets(Request $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $assets = Assets::create($request);

            if (isset($request['last_pm_date'])) {
                $pmData = $request['last_pm_date'];
                foreach ($pmData as $key => $value) {
                    if($request['next_pm_date'][$key]) {
                        AssetsPreventive::create(['asset_id' => $assets->id, 'last_pm_date' => $value, 'pm_frequency_id' => $request['pm_frequency_id'][$key], 
                        'next_pm_date' => $request['next_pm_date'][$key], 'username' => $request['username']]);
                    }
                }
            }
            DB::commit();
            return redirect('create-assets')->with('success','Your changes have been saved');
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function editAssets(Request $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $exists = Assets::find($request['id']);
            if ($exists) {
                $exists->update($request);
            }

            if (isset($request['last_pm_date'])) {
                AssetsPreventive::where('asset_id', $request['id'])->delete();
                $pmData = $request['last_pm_date'];
                foreach ($pmData as $key => $value) {
                    AssetsPreventive::create(['asset_id' => $request['id'], 'last_pm_date' => $value, 'pm_frequency_id' => $request['pm_frequency_id'][$key], 
                        'next_pm_date' => $request['next_pm_date'][$key], 'username' => $request['username']]);
                }
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Your changes have been saved']);
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getPremiseData(Request $request)
    {
        $premises = AssetsProjectPremises::where([['status','=', 1],['project_id','=', $request->project_id]])->orderBy('id','desc')->get();
        if(!empty($premises)) { ?>
            <option value="">Select Option</option>
            <?php
            foreach($premises as $value){ ?>
                <option value="<?php echo $value['id']?>"><?php echo $value['premises_name'];?></option>
                <?php
            }
        }
    }

    public function getSubCategoryData(Request $request)
    {
        $sub_category = AssetsSubCategory::where([['status','=', 1],['category_id','=', $request->category_id]])->orderBy('id','desc')->get();
        if(!empty($sub_category)) { ?>
            <option value="">Select Option</option>
            <?php
            foreach($sub_category as $value) { ?>
                <option value="<?php echo $value['id']?>"><?php echo $value['sub_category_name'];?></option>
                <?php
            }
        }
    }

    public function uploadAssetsForm(Request $request)
    {
        return view('assets.uploadAssetsForm');
    }

    public function uploadAssetsFile(Request $request)
    {
        DB::beginTransaction();
        try {
            $request = $request->all();
            $request['username'] = Auth::user()->name;
            $fileMimes = array(
                // 'text/x-comma-separated-values',
                // 'text/comma-separated-values',
                // 'application/octet-stream',
                // 'application/vnd.ms-excel',
                'application/x-csv',
                'text/x-csv',
                'text/csv',
                'application/csv',
                // 'application/excel',
                // 'application/vnd.msexcel',
                // 'text/plain'
            );
    
            // Validate whether selected file is a CSV file
            if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {
    
                $row = 0;
                $skip_row_number = array("1");
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                fgetcsv($csvFile);
               
                while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) {
                    if (in_array($row, $skip_row_number)) {
                        continue;
                    } else {
                        if ($getData[0]) {

                            $category_id = '';
                            if ($getData[2] != '') {
                                $category = AssetsCategory::select('id')->where([['status', '=', 1], ['category_name', '=', $getData[2]]]);
                                if ($category->count() > 0) {
                                    $category_id = $category->value('id');
                                } else {
                                    $category_id = AssetsCategory::insertGetId(
                                        [
                                            'category_name' => trim($getData[2]),
                                            'status' => 1,
                                            'username' => $request['username'],
                                        ]
                                    );
                                }
                            }

                            $project_id = '';
                            if ($getData[4] != '') {
                                $projects = AssetsProject::select('id')->where([['status', '=', 1], ['project_name', '=', $getData[4]]]);
                                if ($projects->count() > 0) {
                                    $project_id = $projects->value('id');
                                } else {
                                    $project_id = AssetsProject::insertGetId(
                                        [
                                            'project_name' => trim($getData[4]),
                                            'status' => 1,
                                            'username' => $request['username'],
                                        ]
                                    );
                                }
                            }

                            $premise_id = '';
                            if ($getData[5] != '') {
                                $premises = AssetsProjectPremises::select('id')->where([['status', '=', 1], ['premises_name', '=', $getData[5]]]);
                                if ($premises->count() > 0) {
                                    $premise_id = $premises->value('id');
                                } else {
                                    $premise_id = AssetsProjectPremises::insertGetId(
                                        [
                                            'premises_name' => trim($getData[5]),
                                            'status' => 1,
                                            'username' => $request['username'],
                                        ]
                                    );
                                }
                            }

                            $floor_id = '';
                            if ($getData[6] != '') {
                                $floors = Floors::select('id')->where([['status', '=', 1], ['floor', '=', $getData[6]]]);
                                if ($floors->count() > 0) {
                                    $floor_id = $floors->value('id');
                                } else {
                                    $floor_id = Floors::insertGetId(
                                        [
                                            'floor' => trim($getData[6]),
                                            'status' => 1,
                                            'username' => $request['username'],
                                        ]
                                    );
                                }
                            }
                            Assets::updateOrCreate([
                                'asset_code'   => $getData[0],
                            ],[
                                'asset_code' => $getData[0],
                                'asset_name' => $getData[1],
                                'category_id' => $category_id,
                                'project_id' => $project_id,
                                'premise_id' => $premise_id,
                                'floor_id' => $floor_id,
                                'area' => $getData[7],
                                'room' => $getData[8],
                                'model' => $getData[10],
                                'serial' => $getData[11],
                                'barcode' => $getData[12]

                            ]);
                        }
                    }
                }
                AssetsHelper::refreshCache();
                DB::commit();
                return redirect('upload-assets')->with('success','Assets file uploaded');
            }
            DB::commit();
            return redirect('upload-assets')->with('success','No data found');
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function viewAssetsDetail(Request $request)
    {
        $categories = AssetsCategory::where('status', 1)->get();
        $category_array = [];
        foreach($categories as $key => $val):
            $category_array[$val->id] = $val;
        endforeach;

        $sub_categories = AssetsSubCategory::where('status', 1)->get();
        $sub_category_array = [];
        foreach($sub_categories as $key => $val):
            $sub_category_array[$val->id] = $val;
        endforeach;

        $premises = AssetsProjectPremises::where('status', 1)->get();
        $premises_array = [];
        foreach($premises as $key => $val):
            $premises_array[$val->id] = $val;
        endforeach;

        $life = AssetsDesignedLife::where('status', 1)->get();
        $life_array = [];
        foreach($life as $key => $val):
            $life_array[$val->id] = $val;
        endforeach;

        $departments = Department::where('status', 1)->get();
        $departments_array = [];
        foreach($departments as $key => $val):
            $departments_array[$val->id] = $val;
        endforeach;

        $floors = Floors::where('status', 1)->get();
        $floors_array = [];
        foreach($floors as $key => $val):
            $floors_array[$val->id] = $val;
        endforeach;

        $manufacturer = AssetsManufacturer::where('status', 1)->get();
        $manufacturer_array = [];
        foreach($manufacturer as $key => $val):
            $manufacturer_array[$val->id] = $val;
        endforeach;

        $frequency = AssetsFrequency::where('status', 1)->get();
        $frequency_array = [];
        foreach($frequency as $key => $val):
            $frequency_array[$val->id] = $val;
        endforeach;

        $depreciation_method = ['1' => 'Straight Line', '2' => 'Reducing Balance Method'];

        $assets = Assets::with('assetPreventive')->find($request->id);
        return view('assets.viewAssetsDetail',compact('assets','category_array','sub_category_array','premises_array','life_array','departments_array',
        'floors_array','manufacturer_array','frequency_array','depreciation_method'));
    }


    public function viewAssetsReport(Request $request)
    {
        $categories = AssetsCategory::where('status', 1)->get();
        $category_array = [];
        foreach($categories as $key => $val):
            $category_array[$val->id] = $val;
        endforeach;

        $sub_categories = AssetsSubCategory::where('status', 1)->get();
        $sub_category_array = [];
        foreach($sub_categories as $key => $val):
            $sub_category_array[$val->id] = $val;
        endforeach;

        $premises = AssetsProjectPremises::where('status', 1)->get();
        $premises_array = [];
        foreach($premises as $key => $val):
            $premises_array[$val->id] = $val;
        endforeach;

        $life = AssetsDesignedLife::where('status', 1)->get();
        $life_array = [];
        foreach($life as $key => $val):
            $life_array[$val->id] = $val;
        endforeach;

        $departments = Department::where('status', 1)->get();
        $departments_array = [];
        foreach($departments as $key => $val):
            $departments_array[$val->id] = $val;
        endforeach;

        $floors = Floors::where('status', 1)->get();
        $floors_array = [];
        foreach($floors as $key => $val):
            $floors_array[$val->id] = $val;
        endforeach;

        $manufacturer = AssetsManufacturer::where('status', 1)->get();
        $manufacturer_array = [];
        foreach($manufacturer as $key => $val):
            $manufacturer_array[$val->id] = $val;
        endforeach;

        $frequency = AssetsFrequency::where('status', 1)->get();
        $frequency_array = [];
        foreach($frequency as $key => $val):
            $frequency_array[$val->id] = $val;
        endforeach;

        $depreciation_method = ['1' => 'Straight Line', '2' => 'Reducing Balance Method'];

        $assets = Assets::where('status', 1)->orderBy('id','desc')->get();
        return view('assets.viewAssetsReport',compact('assets','category_array','sub_category_array','premises_array','life_array','departments_array',
        'floors_array','manufacturer_array','frequency_array','depreciation_method'));
    }
    

}