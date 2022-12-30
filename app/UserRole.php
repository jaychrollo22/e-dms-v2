<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model implements Auditable
{
    use SoftDeletes;
    
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;
}
