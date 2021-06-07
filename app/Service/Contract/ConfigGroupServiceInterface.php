<?php

namespace App\Service\Contract;

interface ConfigGroupServiceInterface
{
    public function getOne(int $id);

    public function store(array $data);

    public function getConfigs();

    public function getConfigLimit($request);

    public function getOneWithConfigurations(int $id);

    public function getManyConfigWithConfigurations();

    public function storeConfiguration(array $data);

    public function cacheConfiguration();

    public function updateConfiguration($key, $val);

    public function getCanDeleteConfiguration($type);


}