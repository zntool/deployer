<?php

namespace Deployer;

use ZnCore\Base\Arr\Helpers\ArrayHelper;
use ZnCore\Base\DotEnv\Domain\Libs\DotEnv;
use ZnCore\Base\FileSystem\Helpers\FilePathHelper;
use ZnCore\Base\FileSystem\Helpers\FindFileHelper;
use ZnTool\Deployer\Helpers\LoaderHelper;

class App
{

    private static $startTime;
    private static $vars = [];

    public static function init()
    {
        self::$startTime = microtime(true);
        DotEnv::init();
        self::loadProfiles();
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
        if (isset($_ENV['DEPLOYER_CONFIG_FILE'])) {
            $vars = include($_ENV['DEPLOYER_CONFIG_FILE']);
        }
        foreach ($_ENV as $name => $value) {
            if (strpos($name, 'DEPLOYER_') === 0) {
                $varName = substr($name, 9);
                $varName = mb_strtolower($varName);
                $vars[$varName] = $value;
            }
        }
        self::initVarsFromArray($vars);
    }

    public static function loadProfiles()
    {
        if (empty($_ENV['DEPLOYER_PROFILE_DIRECTORIES'])) {
            return;
        }
        $directories = explode(',', $_ENV['DEPLOYER_PROFILE_DIRECTORIES']);
        $profiles = [];
        foreach ($directories as $directory) {
            $directory = rtrim($directory, '/');
            $files = FindFileHelper::scanDir($directory);
            foreach ($files as $file) {
                $path = $directory . '/' . $file;
                if (is_file($path) && FilePathHelper::fileExt($file) == 'php') {
                    $name = FilePathHelper::fileRemoveExt($file);
                    $profiles[$name] = include $path;
                }
            }
        }
        set('profiles', $profiles);
    }

    public static function initVarsFromArray(array $vars)
    {
        foreach ($vars as $varName => $value) {
            set($varName, $value);
        }
        self::$vars = ArrayHelper::merge(self::$vars, $vars);
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
