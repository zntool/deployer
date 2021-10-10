<?php

namespace Deployer;

use ZnTool\Deployer\Libs\Base\BaseApt;

class ServerApt extends BaseApt
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
