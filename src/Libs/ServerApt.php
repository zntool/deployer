<?php

namespace Deployer;

class ServerApt
{

    public static function addRepository($package, $options = [])
    {
        return ServerConsole::runSudo("add-apt-repository -y $package", $options);
    }

    public static function install($package, $options = [])
    {
        return ServerConsole::runSudo("apt-get install $package -y", $options);
    }

    public static function update()
    {
        return ServerConsole::runSudo('apt-get update -y');
    }

    public static function find(string $package)
    {
        try {
            $result = ServerConsole::run("dpkg-query --list | grep -i $package");
        } catch (\Throwable $e) {
            return [];
        }
        $result = trim($result);
        $list = explode(PHP_EOL, $result);
        return $list;
    }

    public static function isInstalled(string $package): bool
    {
        $list = ServerApt::find($package);
        return !empty($list);
    }
}
