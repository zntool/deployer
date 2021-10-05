<?php

namespace Deployer;

task('settings:runSshAgent', function () {
//    run('eval $(ssh-agent)');
    runSshAgent();
});

task('settings:authSsh', function () {
    $key = '{{ssh_directory}}/ubuntu_server';
    if(!isFileExistsLocally($key)) {
        runLocally('ssh-keygen -t rsa -b 2048 -C "my@example.com" -f '.$key.' -N ""');
        runLocally('eval $(ssh-agent)');
        runLocally("ssh-add $key");
    }
    $isUploaded = uploadIfNotExist($key . '.pub', '~/.ssh/authorized_keys');
    if ($isUploaded) {
        writeln("auth key installed!");
    }
});

task('settings:gitSsh', function () {
    uploadIfNotExist('{{ssh_directory}}/config', '~/.ssh/config');
    uploadIfNotExist('{{ssh_directory}}/known_hosts', '~/.ssh/known_hosts');
    uploadKey('my-github');
    uploadKey('my-gitlab');
});

task('settings:gitSshInfo', function () {
    /*cd('~/.ssh');
    $output = run('ls -l');
    writeln($output);*/

    $output = run('ssh-add -l');
    writeln($output);
});
