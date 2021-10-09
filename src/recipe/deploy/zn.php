<?php

namespace Deployer;

task('zn:init', function () {

    if(ServerFs::isDirectoryExists('{{deploy_var_path}}') || ServerFs::isFileExists('{{deploy_path}}/.env.local')) {
        set('is_new', false);
        Console::writelnWarning('skip');
        return;
    } else {
        set('is_new', true);
    }

    $output = Zn::run('init --env=Ci --overwrite=All');
    /*cd('{{release_path}}/vendor/bin');
    $output = run('{{bin/php}} zn init --env=Ci --overwrite=All');*/
//    writeln($output);
    Console::writelnResult($output);
})->desc('Initialization');

task('zn:migrate_up', function () {
    $output = Zn::run('db:migrate:up --withConfirm=0');
//    cd('{{release_path}}/vendor/bin');
//    $output = run('{{bin/php}} zn db:migrate:up --withConfirm=0');
//    writeln($output);
    Console::writelnResult($output);
})->desc('Run migrations');

task('zn:fixtures_import', function () {
    if(!get('is_new')) {
        Console::writelnWarning('skip');
        return;
    }

    $output = Zn::run('db:fixture:import --withConfirm=0');
//    cd('{{release_path}}/vendor/bin');
//    $output = run('{{bin/php}} zn db:fixture:import --withConfirm=0');
//    writeln($output);
    Console::writelnResult($output);
})->desc('Import fixtures');
