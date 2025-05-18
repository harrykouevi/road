<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReportType extends Model
{

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i:s');
    }
    
}
