<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DocumentUploadUser extends Model implements Auditable
{
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;

    public function user_info(){
        return $this->belongsTo('App\User','user_id','id')->select('id','name');
    }
}