<?php

namespace Deployer;

use ZnTool\Deployer\Libs\Base\BaseFs;

class LocalFs extends BaseFs
{

    protected static function test(string $command): bool
    {
        return LocalConsole::test($command);
    }

    protected static function run(string $command)
    {
        return LocalConsole::run($command);
    }
}
