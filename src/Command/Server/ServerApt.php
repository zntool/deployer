<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BaseApt;

class _____ServerApt extends BaseApt
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }
}
