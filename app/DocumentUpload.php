<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DocumentUpload extends Model implements Auditable
{
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;

    public function users(){
        return $this->hasMany('App\DocumentUploadUser');
    }
    public function revisions(){
        return $this->hasMany('App\DocumentUploadRevision')
                    ->select('id','document_upload_id','attachment','file_type','created_at')
                    ->orderBy('created_at','DESC');
    }

    public function document_category_info(){
        return $this->belongsTo('App\DocumentCategory','document_category','id')->select('id','tag','category_description');
    }
    public function company_info(){
        return $this->belongsTo('App\Company','company','id')->select('id','company_name');
    }
    public function department_info(){
        return $this->belongsTo('App\Department','department','id')->select('id','department');
    }
    public function process_owner_info(){
        return $this->belongsTo('App\User','process_owner','id')->select('id','name');
    }
}
