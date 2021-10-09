<?php

namespace Deployer;

task('zn:init', function () {

    if(ServerFs::isDirectoryExists('{{deploy_var_path}}') || ServerFs::isFileExists('{{deploy_path}}/.env.local')) {
        set('is_new', false);
//        View::warning('skip');
//        return;
    } else {
        set('is_new', true);
    }

    $output = Zn::run('init --env=Ci --overwrite=All');
    /*cd('{{release_path}}/vendor/bin');
    $output = ServerConsole::run('{{bin/php}} zn init --env=Ci --overwrite=All');*/
//    writeln($output);
    View::result($output);
})->desc('Initialization');

task('zn:migrate_up', function () {
    $output = Zn::run('db:migrate:up --withConfirm=0');
//    ServerConsole::cd('{{release_path}}/vendor/bin');
//    $output = ServerConsole::run('{{bin/php}} zn db:migrate:up --withConfirm=0');
//    writeln($output);
    View::result($output);
})->desc('Run migrations');

task('zn:fixtures_import', function () {
    if(!get('is_new')) {
        View::warning('skip');
        return;
    }

    $output = Zn::run('db:fixture:import --withConfirm=0');
//    ServerConsole::cd('{{release_path}}/vendor/bin');
//    $output = ServerConsole::run('{{bin/php}} zn db:fixture:import --withConfirm=0');
//    writeln($output);
    View::result($output);
})->desc('Import fixtures');
