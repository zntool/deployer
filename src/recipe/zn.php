<?php

namespace Deployer;

task('zn:init', function () {
    $output = runZn('init --env=Ci --overwrite=All');
    /*cd('{{release_path}}/vendor/bin');
    $output = run('{{bin/php}} zn init --env=Ci --overwrite=All');*/
    writeln($output);
})->desc('Initialization');

task('zn:run_migrations', function () {
    $output = runZn('db:migrate:up --withConfirm=0');
//    cd('{{release_path}}/vendor/bin');
//    $output = run('{{bin/php}} zn db:migrate:up --withConfirm=0');
    writeln($output);
})->desc('Run migrations');

task('zn:import_fixtures', function () {
    $output = runZn('db:fixture:import --withConfirm=0');
//    cd('{{release_path}}/vendor/bin');
//    $output = run('{{bin/php}} zn db:fixture:import --withConfirm=0');
    writeln($output);
})->desc('Import fixtures');
