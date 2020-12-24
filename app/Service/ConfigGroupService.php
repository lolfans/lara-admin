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
        return ConfigGroup::orderBy('sort', 'asc')->orderBy('id', 'desc')->get();
    }

    public function getConfigLimit($request)
    {
        return ConfigGroup::orderBy('sort', 'asc')->orderBy('id', 'desc')->paginate($request->get('limit', 30));
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
        return ConfigGroup::with('configurations')->orderBy('sort', 'asc')->get();
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
        return Configuration::where('key', $type)->where('val', 1)->first();
    }
}