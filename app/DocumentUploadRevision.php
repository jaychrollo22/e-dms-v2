<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

use DateTimeInterface;

class DocumentUploadRevision extends Model implements Auditable
{
    protected $guarded = [];
    use \OwenIt\Auditing\Auditable;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
