<?php

namespace Deployer;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;

task('ssh:config:set_sudo_password', function () {
    $pass = askHiddenResponse('Input sudo password:');
    ServerFs::uploadContent($pass, '~/sudo-pass');
});

task('ssh:config:runSshAgent', function () {
//    ServerConsole::run('eval $(ssh-agent)');
    ServerSsh::runAgent();
});

task('ssh:config:authSsh', function () {
    $key = '{{host_identity_file}}';
    if(!LocalFs::isFileExists($key)) {
        runLocally('ssh-keygen -t rsa -b 2048 -C "my@example.com" -f '.$key.' -N ""');
        runLocally('eval $(ssh-agent)');
        runLocally("ssh-add $key");
    }
    $isUploaded = ServerFs::uploadIfNotExist(get('host_identity_file') . '.pub', '~/.ssh/authorized_keys');
    if ($isUploaded) {
        writeln("auth key installed!");
    }
});

/*task('ssh:config:gitSsh', function () {
    ServerFs::uploadIfNotExist('{{ssh_directory}}/config', '~/.ssh/config');
    ServerFs::uploadIfNotExist('{{ssh_directory}}/known_hosts', '~/.ssh/known_hosts');
    ServerSsh::uploadKey('my-github');
    ServerSsh::uploadKey('my-gitlab');
});*/

task('ssh:config:gitSsh', function () {
    $configData = [];

    if(ServerFs::isFileExists('~/.ssh/config')) {
        $config = ServerFs::downloadContent('~/.ssh/config');
        preg_match_all('/Host\s+([^\s]+)\s+IdentityFile\s+([^\s]+)/', $config, $matches);
        foreach ($matches[1] as $index => $domain) {
            $keyName = $matches[2][$index];
            $configData[] = [
                'name' => basename($keyName),
                'host' => $domain,
                'path' => $keyName,
            ];
        }
    }
    
    $indexed = ArrayHelper::index($configData, 'name');
    $keyList = get('ssh_key_list');
    foreach ($keyList as $item) {
        $keyName = $item['name'];
        $domain = $item['host'];
        $isExists = isset($indexed[$keyName]);
        if (!$isExists) {
            $configData[] = [
                'name' => $keyName,
                'host' => $domain,
                'path' => "~/.ssh/$keyName",
            ];
        }
    }

    $codeArr = [];
    foreach ($configData as $item) {
        $keyName = $item['name'];
        $domain = $item['host'];
        $codeArr[] = "Host $domain
 IdentityFile ~/.ssh/$keyName";
    }
    $code = implode(PHP_EOL, $codeArr);
    ServerFs::uploadContent($code, '~/.ssh/config');

    foreach ($configData as $item) {
        $keyName = $item['name'];
        ServerSsh::uploadKey($keyName);
    }

    ServerFs::uploadIfNotExist('{{ssh_directory}}/known_hosts', '~/.ssh/known_hosts');
});

task('ssh:config:gitSshInfo', function () {
    /*cd('~/.ssh');
    $output = ServerConsole::run('ls -l');
    writeln($output);*/

    $output = ServerConsole::run('ssh-add -l');
    writeln($output);
});

task('ssh:config', [
    //'ssh:connect_by_root',
    //'ssh:config:runSshAgent',
    'ssh:config:authSsh',
    'ssh:config:set_sudo_password',
    'ssh:config:gitSsh',
    'ssh:config:gitSshInfo',
]);
