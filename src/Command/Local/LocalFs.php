<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BaseFs;

class LocalFs extends BaseFs
{

    protected static function side(): string
    {
        return static::SIDE_LOCAL;
    }

    /*protected static function consoleClassName(): string
    {
        return LocalConsole::class;
    }*/
}
