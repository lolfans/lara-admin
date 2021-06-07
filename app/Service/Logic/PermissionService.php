<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service\Logic;

use App\Models\Permission;
use App\Service\Contract\PermissionServiceInterface;

class PermissionService implements PermissionServiceInterface
{
    public function getPermission()
    {
        return Permission::get();
    }

    public function getPermissionWithChild()
    {
        return Permission::with('allChilds')->where('parent_id', 0)->get();
    }

    public function store($data)
    {
        return Permission::create($data);
    }

    public function getOne($id)
    {
        return Permission::findOrFail($id);
    }

    public function getOneWithChildPermission($id)
    {
        return Permission::with('childs')->find($id);
    }
}