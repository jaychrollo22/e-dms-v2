<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserCompany extends Model implements Auditable
{
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;
    
    public function company_info(){
        return $this->belongsTo('App\Company','company_id','id')->select('id','company_name','company_code');
    }
}
