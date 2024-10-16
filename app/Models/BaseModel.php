<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class BaseModel extends Model implements Auditable
{
    use AuditableTrait;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
           
          
        });

        static::updated(function ($model) {
           
        });

        static::deleted(function ($model) {
        
        });
    }

    // You can add common methods or properties here if needed
}

