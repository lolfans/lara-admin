<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service\Logic;

use App\Models\OperateLog;
use App\Service\Contract\OperaLogServiceInterface;

class OperaLogService implements OperaLogServiceInterface
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