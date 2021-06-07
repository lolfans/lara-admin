<?php

namespace App\Service\Contract;

interface PermissionServiceInterface
{
    public function getPermission();

    public function getPermissionWithChild();

    public function store($data);

    public function getOne($id);

    public function getOneWithChildPermission($id);
}