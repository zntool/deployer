<?php

namespace ZnTool\Deployer\Command\Base;

use Deployer\Git;
use Deployer\ServerConsole;

abstract class BaseGit extends Base
{

    protected static function runGit($command)
    {
        return static::consoleClass()::run("{{bin/git}} $command");
    }

    public static function clone(string $repository, string $branch = null, string $directory = '.')
    {
        $command = "clone ";
        if ($branch) {
            $command .= " -b $branch ";
        }
        $command .= " -q --depth 1 $repository $directory ";
        return static::runGit($command);
    }

    public static function add(string $paths = '.')
    {
        $command = "add $paths";
        return static::runGit($command);
    }

    public static function commit(string $message = 'upd')
    {
        $command = "commit -m $message";
        return static::runGit($command);
    }

    public static function pull()
    {
        $command = "pull";
        return static::runGit($command);
    }

    public static function push()
    {
        $command = "push";
        return static::runGit($command);
    }

    public static function stash()
    {
        $command = "stash";
        return static::runGit($command);
    }

    public static function isHasChanges(): bool
    {
        $output = static::status();
        return strpos($output, 'nothing to commit') !== false;
    }

    public static function status()
    {
        $command = "status";
        return static::runGit($command);
    }

    public static function checkout(string $branch)
    {
        $command = "checkout $branch";
        return static::runGit($command);
    }

    public static function config(string $key, string $value, bool $isGlobal = false)
    {
        $command = "config ";
        if ($isGlobal) {
            $command .= " --global ";
        }
        $command .= " $key \"$value\" ";
        return static::runGit($command);
    }

    public static function configList()
    {
        $configCode = static::runGit('config --list');
        $configLines = explode(PHP_EOL, $configCode);
        $config = [];
        foreach ($configLines as $line) {
            if (!empty($line)) {
                list($name, $value) = explode('=', $line);
                if ($name) {
                    $config[$name] = $value;
                }
            }
        }
        return $config;
    }
}
