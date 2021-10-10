<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BaseHosts;

class ServerHosts extends BaseHosts
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
