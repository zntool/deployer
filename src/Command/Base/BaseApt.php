<?php

namespace ZnTool\Deployer\Command\Base;

use Deployer\ServerApt;
use Deployer\View;

abstract class BaseApt extends Base
{

    public static function addRepository($package, $options = [])
    {
        return static::run("sudo add-apt-repository -y $package", $options);
    }

    public static function install($package, $options = [])
    {
        if (ServerApt::isInstalled($package)) {
            View::warning("$package alredy exist");
            return false;
        } else {
            $result = static::run("sudo apt-get install $package -y", $options);
            View::success("$package installed");
            return $result;
        }
    }

    public static function installBatch(array $packages)
    {
        $exists = $new = [];
        foreach ($packages as $package) {
            if (ServerApt::isInstalled($package)) {
                $exists[] = $package;
            } else {
                $new[] = $package;
            }
        }
        foreach ($exists as $package) {
            View::warning("$package alredy exist");
        }
        $packagesString = implode(' ', $new);
        if(trim($packagesString) != '') {
            static::install($packagesString);
            foreach ($new as $package) {
                View::success("$package installed");
            }
        }
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
