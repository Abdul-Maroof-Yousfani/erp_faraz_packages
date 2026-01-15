<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model{
    protected $table = 'assets';
    protected $fillable = ['premise_id','project_id','floor_id','department_id','area','room','asset_code','barcode','asset_name', 'asset_status','sub_category_id',
        'category_id','manufacturer_id','installed_date','model','serial','asset_detail_description','last_assessment_date','next_assessment_date','condition_id',
        'risk_likelihood_id','useful_life_id','depreciation_method','working_status_id','asset_status_description','purchased_date','purchase_price','residual_life',
        'depreciation','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';

    public static function getColumnsOfInterest()
    {
        return [
            'asset_code',
            'asset_name',
            'installed_date',
            'premise_id',
            'useful_life_id',
            'purchase_price',
            'depreciation',
            'status'
        ];
    }

    public function assetPreventive()
    {
        return $this->hasMany(AssetsPreventive::class,'asset_id','id');
    }
}

