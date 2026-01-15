<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model{
    protected $table = 'complaint';
    protected $fillable = ['client_name','branch_name','branch_code','date','contanct_person','designation',
        'phone','address','monthly','Quaterly','Semi_Annually','Annually','On_Call',
        'board_cleaning','led_stip','led_wiring','led_rope','power_supply','sign_note',
        'auto_manual','contractor','breaker','sun_switch','volt_led','stabilizer','note',
        'timer_connection','breaker_replaced','wiring_additional','rft','comments','status','created_date','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
