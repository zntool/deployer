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
        return self::_run($command, $options);
    }

    public static function run($command, $options = [])
    {
        $command = trim($command);
        if(strpos($command, 'sudo ') === 0) {
            $command = substr($command, strlen('sudo '));
            return self::runSudo($command, $options);
        }
        return self::_run($command, $options);
    }

    protected function _run($command, $options = []) {
        return run($command, $options);
    }

    /*public static function aptInstall($package, $options = [])
    {
        return ServerConsole::runSudo("add-apt-repository -y $package", $options);
    }*/
}
