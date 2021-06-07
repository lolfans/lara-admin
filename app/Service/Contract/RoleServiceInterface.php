<?php

namespace App\Service\Contract;

interface RoleServiceInterface
{
    public function getRole($request);

    public function store($request);

    public function getOne($id);

    public function destroy($ids);
}