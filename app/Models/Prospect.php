<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;

class Prospect extends Model
{
    protected $connection = 'mysql2';

    public function contact()
    {
        return $this->hasOne(Contact::class,'id','contact_id');
    }

    public function companyLocation()
    {
        return $this->hasOne(CompanyLocation::class,'id','company_location_id');
    }

    public function companyGroup()
    {
        return $this->hasOne(CompanyGroup::class,'id','customer_group_id');
    }
}
