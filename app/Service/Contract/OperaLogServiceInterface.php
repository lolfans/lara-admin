<?php

namespace App\Service\Contract;

interface OperaLogServiceInterface
{
    public function getOperaLog($request);

    public function destroy($ids);
}