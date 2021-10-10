<?php

namespace Deployer;

use ZnTool\Deployer\Libs\Base\BaseFs;

class ServerFs extends BaseFs
{

    protected static function test(string $command): bool
    {
        return ServerConsole::test($command);
    }

    protected static function run(string $command)
    {
        return ServerConsole::run($command);
    }
}
