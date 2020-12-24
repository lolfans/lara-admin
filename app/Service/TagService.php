<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service;

use App\Models\Tag;

class TagService
{
    public function getTag()
    {
        return Tag::get();
    }

    public function getTagLimit($request)
    {
        return Tag::orderBy('sort', 'asc')->orderBy('id', 'desc')->paginate($request->get('limit', 30));
    }

    public function store($data)
    {
        return Tag::create($data);
    }

    public function getOne($id)
    {
        return Tag::findOrFail($id);
    }

    public function destroyManyTags($ids)
    {
        return  Tag::whereIn('id', $ids)->delete();
    }
}