<?php

namespace Deployer;

use ZnTool\Deployer\Libs\Base\BaseFs;

class ServerFs extends BaseFs
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
