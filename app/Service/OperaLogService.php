<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service;

use App\Models\OperateLog;

class OperaLogService
{
    public function getOperaLog($request)
    {
        return OperateLog::getOperaLog($request);
    }

    public function destroy($ids)
    {
        return OperateLog::destroy($ids);
    }
}