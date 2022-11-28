<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Department extends Model implements Auditable
{
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;

}
