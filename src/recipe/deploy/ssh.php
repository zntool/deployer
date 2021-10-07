<?php

namespace Deployer;

task('ssh:config:set_sudo_password', function () {
    $pass = askHiddenResponse('Input sudo password:');
    ServerFs::uploadContent($pass, '~/sudo-pass');
});

task('ssh:config:runSshAgent', function () {
//    run('eval $(ssh-agent)');
    ServerSsh::run();
});

task('ssh:config:authSsh', function () {
    $key = '{{host_identity_file}}';
    if(!LocalFs::isFileExists($key)) {
        runLocally('ssh-keygen -t rsa -b 2048 -C "my@example.com" -f '.$key.' -N ""');
        runLocally('eval $(ssh-agent)');
        runLocally("ssh-add $key");
    }
    //dd(realpath(get('host_identity_file')));
    $isUploaded = ServerFs::uploadIfNotExist(get('host_identity_file') . '.pub', '~/.ssh/authorized_keys');
    if ($isUploaded) {
        writeln("auth key installed!");
    }
});

task('ssh:config:gitSsh', function () {
    ServerFs::uploadIfNotExist('{{ssh_directory}}/config', '~/.ssh/config');
    ServerFs::uploadIfNotExist('{{ssh_directory}}/known_hosts', '~/.ssh/known_hosts');
    ServerSsh::uploadKey('my-github');
    ServerSsh::uploadKey('my-gitlab');
});

task('ssh:config:gitSshInfo', function () {
    /*cd('~/.ssh');
    $output = run('ls -l');
    writeln($output);*/

    $output = run('ssh-add -l');
    writeln($output);
});

task('ssh:config:up', [
    
    //'ssh:connect_by_root',
    //'ssh:config:runSshAgent',
    'ssh:config:authSsh',
    'ssh:config:gitSsh',
    'ssh:config:gitSshInfo',
]);
