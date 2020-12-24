<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $guarded = ['id'];

    //子分类
    public function childs()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }

    //所有子类
    public function allChilds()
    {
        return $this->childs()->with('allChilds');
    }

    //分类下所有的文章
    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }

    public static function getCategory($rank = 'asc')
    {
        return self::with('allChilds')->where('parent_id', 0)->orderBy('sort', $rank)->get();
    }

}
