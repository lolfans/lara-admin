<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service\Logic;

use App\Models\ConfigGroup;
use App\Models\Configuration;
use App\Service\Contract\ConfigGroupServiceInterface;

class ConfigGroupService implements ConfigGroupServiceInterface
{
    public function getConfigs()
    {
        return ConfigGroup::OrderSortAsc()->OrderIdDesc()->get();
    }

    public function getConfigLimit($request)
    {
        return ConfigGroup::OrderSortAsc()->OrderIdDesc()->paginate($request->get('limit', 30));
    }

    public function store(array $data)
    {
        return ConfigGroup::create($data);

    }

    public function getOne(int $id)
    {
        return ConfigGroup::findOrFail($id);
    }

    public function getOneWithConfigurations(int $id)
    {
        return ConfigGroup::with('configurations')->find($id);
    }

    public function getManyConfigWithConfigurations()
    {
        return ConfigGroup::with('configurations')->OrderSortAsc()->get();
    }

    public function storeConfiguration(array $data)
    {
        return Configuration::create($data);
    }

    public function cacheConfiguration()
    {
        return Configuration::pluck('val', 'key');
    }

    public function updateConfiguration($k, $v)
    {
        return Configuration::where('key', $k)->update(['val' => $v]);
    }

    public function getCanDeleteConfiguration($type)
    {
        return Configuration::where('key', $type)->CanDelete()->first();
    }
}