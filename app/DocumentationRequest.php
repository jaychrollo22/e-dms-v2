<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DocumentationRequest extends Model implements Auditable
{
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;

    public function requestor_info(){
        return $this->belongsTo('App\User','requestor','id')->select('id','name');
    }
    public function company_info(){
        return $this->belongsTo('App\Company','company','id')->select('id','company_name');
    }
    public function department_info(){
        return $this->belongsTo('App\Department','department','id')->select('id','department');
    }
}
