<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AccessRequest extends Model implements Auditable
{
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;

    public function immediate_head_info(){
        return $this->belongsTo('App\User','immediate_head','id')->select('id','name');
    }

    public function company_info(){
        return $this->belongsTo('App\Company','company','id')->select('id','company_name');
    }

    public function department_info(){
        return $this->belongsTo('App\Department','department','id')->select('id','department');
    }

}
