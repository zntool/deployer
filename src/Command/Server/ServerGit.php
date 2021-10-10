<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BaseGit;

class ServerGit extends BaseGit
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
