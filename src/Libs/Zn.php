<?php

namespace Deployer;

class Zn {

    public static function run(string $command) {
        cd('{{release_path}}/vendor/bin');
        return ServerConsole::run('{{bin/php}} zn ' . $command);
    }
}
