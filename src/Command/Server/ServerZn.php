<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BaseZn;

class ServerZn extends BaseZn
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
