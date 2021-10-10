<?php

namespace Deployer;

use ZnTool\Deployer\Command\Base\BaseConsole;

class ServerConsole extends BaseConsole
{

    protected static function getSudoCommandTemplate()
    {
        return get('sudo_cmd', '');
    }

    public static function test(string $command)
    {
        return test($command);
    }

    public static function cd(string $path)
    {
        return cd($path);
    }

    protected static function _run(string $command, $options = [])
    {
        return run($command, $options);
    }
}
