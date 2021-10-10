<?php

namespace ZnTool\Deployer\Libs\Base;

abstract class BaseApt extends Base
{

    public static function addRepository($package, $options = [])
    {
        return static::run("sudo add-apt-repository -y $package", $options);
    }

    public static function install($package, $options = [])
    {
        return static::run("sudo apt-get install $package -y", $options);
    }

    public static function update()
    {
        return static::run('sudo apt-get update -y');
    }

    public static function find(string $package)
    {
        try {
            $result = static::run("dpkg-query --list | grep -i $package");
        } catch (\Throwable $e) {
            return [];
        }
        $result = trim($result);
        $list = explode(PHP_EOL, $result);
        return $list;
    }

    public static function isInstalled(string $package): bool
    {
        $list = static::find($package);
        return !empty($list);
    }
}
