<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BaseUser;

class ServerUserShell extends BaseUser
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
