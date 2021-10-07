<?php

namespace Deployer;

class ServerConsole
{

    public static function runSudo($command, $options = [])
    {
        $sudoCmd = get('sudo_cmd', '');
        if($sudoCmd) {
            $command = str_replace('{command}', $command, $sudoCmd);
        }
        return self::run($command, $options);
    }

    public static function run($command, $options = [])
    {
        return run($command, $options);
    }

    /*public static function aptInstall($package, $options = [])
    {
        return ServerConsole::runSudo("add-apt-repository -y $package", $options);
    }*/
}
