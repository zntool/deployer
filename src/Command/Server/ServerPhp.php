<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BasePhp;

class ServerPhp extends BasePhp
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
