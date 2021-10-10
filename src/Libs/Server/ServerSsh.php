<?php

namespace Deployer;

use ZnTool\Deployer\Libs\Base\BaseSsh;

class ServerSsh extends BaseSsh
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
