<?php

namespace Deployer;

use ZnTool\Deployer\Helpers\LoaderHelper;

class App {

    public static function init() {
        self::initVars();
        self::initSshConnect();
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
        /*if(isset($_ENV['DEPLOYER_HOST_IDENTITY_FILE'])) {
            $host->identityFile($_ENV['DEPLOYER_HOST_IDENTITY_FILE']);
        }*/
    }
}
