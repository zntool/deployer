<?php

namespace Deployer;

use ZnTool\Deployer\Libs\Base\BaseUser;

class ServerUser extends BaseUser
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
