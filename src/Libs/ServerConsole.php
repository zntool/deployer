<?php

namespace Deployer;

class ServerConsole
{

    public static function runSudo($command, $options = [])
    {
        $sudoCmd = get('sudo_cmd', '');
        if($sudoCmd) {
            $command = $sudoCmd . ' ' . $command;
        }
        return run($command, $options);
    }
}
