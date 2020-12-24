<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service;

use App\Models\LoginLog;

class LoginLogService
{
    public function getLoginLog($request)
    {
        return LoginLog::getLoginLog($request);
    }

    public function destroy($ids)
    {
        return LoginLog::destroy($ids);
    }
}