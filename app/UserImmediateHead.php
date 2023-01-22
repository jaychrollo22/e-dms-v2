<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserImmediateHead extends Model implements Auditable
{
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;
    

    public function user_info(){
        return $this->belongsTo('App\User','immediate_head','id')->select('id','name');
    }
    
    public function user_details_info(){
        return $this->belongsTo('App\User','user_id','id')->select('id','name');
    }

    public function pending_copy_requests(){
        return $this->hasMany('App\DocumentCopyRequest','requestor','user_id')->where('immediate_head_approval','For Approval');
    }
}
