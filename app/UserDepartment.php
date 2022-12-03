<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserDepartment extends Model implements Auditable
{
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;
    
    public function department_info(){
        return $this->belongsTo('App\Department','department_id','id')->select('id','department');
    }
}
