<?php

namespace Deployer;

/*task('deploy:prepare', function () {
    ServerFs::makeDirectory('{{deploy_path}}');
//    makeDirectory('{{deploy_path}}');
    $isExists = ServerFs::isFileExists("{{deploy_path}}/.env");
    //cd('{{deploy_path}}');
    if(! $isExists) {
        writeln('git clone');
        $output = ServerConsole::run('{{bin/git}} clone {{repository}} {{deploy_path}}');
    }
    ServerFs::makeDirectory('{{deploy_path}}/.dep');
//    makeDirectory('{{deploy_path}}/.dep');
});

task('deploy:update_code', function () {
    cd('{{deploy_path}}');
    $output = ServerConsole::run('{{bin/git}} fetch origin {{branch}}');
    $output = ServerConsole::run('{{bin/git}} checkout {{branch}}');
    $output = ServerConsole::run('{{bin/git}} pull');
    writeln($output);
});*/

task('deploy:down', function () {
    $output = ServerConsole::run('rm -rf {{deploy_path}}');
    writeln($output);
});
