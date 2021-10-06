<?php

namespace Deployer;

use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnTool\Deployer\Helpers\LoaderHelper;

class App
{

    private static $startTime;

    public static function init()
    {
        self::$startTime = microtime(true);
        DotEnv::init();
        self::initVars();
        self::initSshConnect();
    }

    public static function getTotalTime(): int
    {
        return intval(microtime(true) - self::$startTime);
    }

    /*public static function loadTasks(string $taskDir): void
    {
        //LoaderHelper::loadTasks($taskDir);
        requireLibs($taskDir);
    }*/

    public static function initVars()
    {
        foreach ($_ENV as $name => $value) {
            if (strpos($name, 'DEPLOYER_') === 0) {
                $varName = substr($name, 9);
                $varName = mb_strtolower($varName);
                set($varName, $value);
            }
        }
    }

    public static function initSshConnect(string $userName = null)
    {
        $userName = $userName ?: get('host_user');
        $host = host(get('host_ip'));
        $host->user($userName);
        $host->port(get('host_port'));
        $host->stage('staging');
        if (get('host_identity_file')) {
            $host->identityFile(get('host_identity_file'));
        }
        $host->set('deploy_path', get('deploy_path'));
    }
}
