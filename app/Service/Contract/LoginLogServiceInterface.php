<?php

namespace App\Service\Contract;

interface LoginLogServiceInterface
{
    public function getLoginLog($request);

    public function destroy($ids);
}