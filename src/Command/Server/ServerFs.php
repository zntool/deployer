<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BaseFs;

class ServerFs extends BaseFs
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
