<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Tag extends Model implements Auditable
{
    protected $guarded = [];

    use \OwenIt\Auditing\Auditable;
}
