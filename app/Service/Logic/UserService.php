<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service\Logic;

use App\Models\User;
use App\Service\Contract\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function getUserLimit($request)
    {
        return User::paginate($request->get('limit', 30));
    }

    public function store($data)
    {
        return User::create($data);
    }

    public function getOne($id)
    {
        return User::findOrFail($id);
    }

    public function destroyManyUser($ids)
    {
        return User::destroy($ids);
    }
}