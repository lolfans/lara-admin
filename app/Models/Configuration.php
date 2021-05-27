<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = 'configuration';
    protected $guarded = ['id'];

    public function scopeCanDelete($query)
    {
        return $query->where('val', 1);
    }
}
