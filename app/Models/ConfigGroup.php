<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigGroup extends Model
{
    protected $table = 'config_group';
    protected $fillable = ['name','sort'];

    //配置项
    public function configurations()
    {
        return $this->hasMany('App\Models\Configuration','group_id','id');
    }

    public function scopeOrderSortDesc($query)
    {
        return $query->orderBy('sort', 'desc');
    }

    public function scopeOrderSortAsc($query)
    {
        return $query->orderBy('sort', 'asc');
    }

    public function scopeOrderIdAsc($query)
    {
        return $query->orderBy('id', 'asc');
    }

    public function scopeOrderIdDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }

}
