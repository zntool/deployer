<?php

namespace ZnTool\Deployer\Command\Base;

use Deployer\ServerConsole;
use Deployer\Zn;

abstract class BaseZn extends Base
{

    public static function init(string $env) {
        return static::runCommand("init --env=\"$env\" --overwrite=All", $env);
    }

    public static function migrateUp(string $env = null) {
        return static::runCommand("db:migrate:up --withConfirm=0", $env);
    }

    public static function fixtureImport(string $env = null) {
        return static::runCommand("db:fixture:import --withConfirm=0", $env);
    }

    public static function runCommand(string $command, string $env = null) {
        $envCode = $env ? "--env=\"$env\"" : '';
        static::cd('{{release_path}}/vendor/bin');
        return static::run("{{bin/php}} zn $command $envCode");
    }
}
