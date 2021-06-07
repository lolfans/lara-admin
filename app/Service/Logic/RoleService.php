<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service\Logic;

use App\Models\Role;
use App\Service\Contract\RoleServiceInterface;

class RoleService implements RoleServiceInterface
{
    public function getRole($request)
    {
        return Role::paginate($request->get('limit', 30));
    }

    public function store($request)
    {
        $data = $request->only(['name', 'display_name']);
        return Role::create($data);
    }

    public function getOne($id)
    {
        return Role::findOrFail($id);
    }

    public function destroy($ids)
    {
        return Role::destroy($ids);
    }
}