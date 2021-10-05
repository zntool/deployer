<?php

namespace Deployer;

use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnTool\Deployer\Helpers\LoaderHelper;

class App {

    private static $startTime;
    
    public static function init() {
        
// keep track of the start time so we can compute total time
        self::$startTime = microtime( true );
        
        require_once __DIR__ . '/../Libs/App.php';
        require_once __DIR__ . '/../Libs/LocalFs.php';
        require_once __DIR__ . '/../Libs/ServerFs.php';
        require_once __DIR__ . '/../Libs/ServerSsh.php';
        require_once __DIR__ . '/../Libs/Zn.php';
        require_once __DIR__ . '/../Libs/Console.php';
        
        DotEnv::init();
        self::initVars();
        self::initSshConnect();
    }

    public static function getTotalTime(): int {
        return intval( microtime( true ) - self::$startTime );
    }

    public static function loadTasks(string $taskDir): void {
        LoaderHelper::loadTasks($taskDir);
    }

    public static function initVars() {
        foreach ($_ENV as $name => $value) {
            if(strpos($name, 'DEPLOYER_') === 0) {
                $varName = substr($name, 9);
                $varName = mb_strtolower($varName);
                set($varName, $value);
            }
        }
    }

    public static function initSshConnect(string $userName = null) {
        $userName = $userName ?: get('host_user');
        $host = host(get('host_ip'));
        $host->user($userName);
        $host->port(get('host_port'));
        $host->stage( 'staging' );
        if(get('host_identity_file')) {
            $host->identityFile(get('host_identity_file'));
        }
        $host->set( 'deploy_path', get('deploy_path'));
    }
}
