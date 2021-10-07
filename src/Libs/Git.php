<?php

namespace Deployer;

class Git
{

    public static function clone(string $repository, string $branch = null, string $directory = '.')
    {
        $command = "{{bin/git}} clone ";
        if($branch) {
            $command .= " -b $branch ";
        }
        $command .= " -q --depth 1 $repository $directory ";
        return ServerConsole::run($command);
    }

    public static function add(string $paths = '.')
    {
        $command = "{{bin/git}} add $paths";
        return ServerConsole::run($command);
    }

    public static function commit(string $message = 'upd')
    {
        $command = "{{bin/git}} commit -m $message";
        return ServerConsole::run($command);
    }

    public static function pull()
    {
        $command = "{{bin/git}} pull";
        return ServerConsole::run($command);
    }

    public static function push()
    {
        $command = "{{bin/git}} push";
        return ServerConsole::run($command);
    }

    public static function stash()
    {
        $command = "{{bin/git}} stash";
        return ServerConsole::run($command);
    }

    public static function status()
    {
        $command = "{{bin/git}} status";
        return ServerConsole::run($command);
    }

    public static function checkout(string $branch)
    {
        $command = "{{bin/git}} checkout $branch";
        return ServerConsole::run($command);
    }

    public static function config(string $key, string $value, bool $isGlobal = false)
    {
        $command = "{{bin/git}} config ";
        if($isGlobal) {
            $command .= " --global ";
        }
        $command .= " $key \"$value\" ";
        return ServerConsole::run($command);
    }

    public static function configList()
    {
        $configCode = run('{{bin/git}} config --list');
        $configLines = explode(PHP_EOL, $configCode);
        $config = [];
        foreach ($configLines as $line) {
            if(!empty($line)) {
                list($name, $value) = explode('=', $line);
                if($name) {
                    $config[$name] = $value;
                }
            }
        }
        return $config;
    }
}
