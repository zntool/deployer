<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BasePackage;

class ServerPackage extends BasePackage
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
