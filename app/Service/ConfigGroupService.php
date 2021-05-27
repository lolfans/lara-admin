<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service;

use App\Models\ConfigGroup;
use App\Models\Configuration;

class ConfigGroupService
{

    public function getConfigs()
    {
        return ConfigGroup::OrderSortAsc()->OrderIdDesc()->get();
    }

    public function getConfigLimit($request)
    {
        return ConfigGroup::OrderSortAsc()->OrderIdDesc()->paginate($request->get('limit', 30));
    }

    public function store($data)
    {
        return ConfigGroup::create($data);

    }

    public function getOne($id)
    {
        return ConfigGroup::findOrFail($id);
    }

    public function getOneWithConfigurations($id)
    {
        return ConfigGroup::with('configurations')->find($id);
    }

    public function getManyConfigWithConfigurations()
    {
        return ConfigGroup::with('configurations')->OrderSortAsc()->get();
    }

    public function storeConfiguration($data)
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