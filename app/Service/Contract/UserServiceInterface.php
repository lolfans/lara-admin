<?php

namespace App\Service\Contract;

interface UserServiceInterface
{
    public function getUserLimit($request);

    public function store($data);

    public function getOne($id);

    public function destroyManyUser($ids);
}