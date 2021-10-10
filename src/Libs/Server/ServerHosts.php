<?php

namespace Deployer;

use ZnTool\Deployer\Libs\Base\BaseHosts;

class ServerHosts extends BaseHosts
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
