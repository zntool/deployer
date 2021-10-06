<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

task('settings:env_info', function () {
    writeln('');

    $output = run('uname -a');
    writeln($output);

    writeln('');

    $output = run('{{bin/php}} -v');
    writeln($output);

    writeln('');

    $output = run('{{bin/git}} --version');
    writeln($output);

    writeln('');
});

task('settings:runSshAgent', function () {
//    run('eval $(ssh-agent)');
    ServerSsh::run();
});

task('settings:authSsh', function () {
    $key = '{{ssh_directory}}/ubuntu_server';
    if(!LocalFs::isFileExists($key)) {
        runLocally('ssh-keygen -t rsa -b 2048 -C "my@example.com" -f '.$key.' -N ""');
        runLocally('eval $(ssh-agent)');
        runLocally("ssh-add $key");
    }
    $isUploaded = ServerFs::uploadIfNotExist($key . '.pub', '~/.ssh/authorized_keys');
    if ($isUploaded) {
        writeln("auth key installed!");
    }
});

task('settings:gitSsh', function () {
    ServerFs::uploadIfNotExist('{{ssh_directory}}/config', '~/.ssh/config');
    ServerFs::uploadIfNotExist('{{ssh_directory}}/known_hosts', '~/.ssh/known_hosts');
    ServerSsh::uploadKey('my-github');
    ServerSsh::uploadKey('my-gitlab');
});

task('settings:gitSshInfo', function () {
    /*cd('~/.ssh');
    $output = run('ls -l');
    writeln($output);*/

    $output = run('ssh-add -l');
    writeln($output);
});

task('settings:up', [
    //'ssh:connect_by_root',
    //'settings:runSshAgent',
//    'settings:authSsh',
    'settings:gitSsh',
    'settings:gitSshInfo',
])->desc('Settings up');
