<?php

namespace Deployer;

task('zn:init', function () {
    $output = Zn::run('init --env=Ci --overwrite=All');
    /*cd('{{release_path}}/vendor/bin');
    $output = run('{{bin/php}} zn init --env=Ci --overwrite=All');*/
//    writeln($output);
    Console::writelnResult($output);
})->desc('Initialization');

task('zn:run_migrations', function () {
    $output = Zn::run('db:migrate:up --withConfirm=0');
//    cd('{{release_path}}/vendor/bin');
//    $output = run('{{bin/php}} zn db:migrate:up --withConfirm=0');
//    writeln($output);
    Console::writelnResult($output);
})->desc('Run migrations');

task('zn:import_fixtures', function () {
    $output = Zn::run('db:fixture:import --withConfirm=0');
//    cd('{{release_path}}/vendor/bin');
//    $output = run('{{bin/php}} zn db:fixture:import --withConfirm=0');
//    writeln($output);
    Console::writelnResult($output);
})->desc('Import fixtures');
