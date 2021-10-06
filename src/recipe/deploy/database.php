<?php

namespace Deployer;

task('database:migrate_up', function () {
    $output = Zn::run('db:migrate:up --withConfirm=0');
//    cd('{{release_path}}/vendor/bin');
//    $output = run('{{bin/php}} zn db:migrate:up --withConfirm=0');
//    writeln($output);
    Console::writelnResult($output);
})->desc('Run migrations');

task('database:fixtures_import', function () {
    $output = Zn::run('db:fixture:import --withConfirm=0');
//    cd('{{release_path}}/vendor/bin');
//    $output = run('{{bin/php}} zn db:fixture:import --withConfirm=0');
//    writeln($output);
    Console::writelnResult($output);
})->desc('Import fixtures');
