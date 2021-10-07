<?php

namespace Deployer;

class ServerApt
{

    public static function install($package, $options = [])
    {
        return ServerConsole::runSudo("add-apt-repository -y $package", $options);
    }

    public static function update()
    {
        return ServerConsole::runSudo('apt-get update -y');
    }

    public static function find(string $package)
    {
        $result = ServerConsole::run("dpkg-query --list | grep -i $package");
        dd($result);
    }
}
