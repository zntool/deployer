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
        LocalConsole::run('ssh-keygen -t rsa -b 2048 -C "my@example.com" -f '.$key.' -N ""');
        LocalConsole::run('eval $(ssh-agent)');
        LocalConsole::run("ssh-add $key");
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

    $sshConfig = new SshConfig();

    if(ServerFs::isFileExists('~/.ssh/config')) {
        $config = ServerFs::downloadContent('~/.ssh/config');
        $configData = $sshConfig->parse($config);
    }
    
//    $indexed = ArrayHelper::index($configData, 'name');
    $keyList = get('ssh_key_list');
    foreach ($keyList as $item) {
        $keyName = $item['name'];
        $domain = $item['host'];
//        $isExists = isset($indexed[$keyName]);
        if (!$sshConfig->hasByName($keyName)) {
            $sshConfig->add($keyName, $domain, "~/.ssh/$keyName");
            /*$configData[] = [
                'name' => $keyName,
                'host' => $domain,
                'path' => "~/.ssh/$keyName",
            ];*/
        }
    }

    $code = $sshConfig->generate();

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

    $output = ServerSsh::list();
    View::list($output);
    //dd($output);

//    $output = ServerConsole::run('ssh-add -l');
   // writeln($output);
});

task('ssh:config', [
    //'ssh:connect_by_root',
    //'ssh:config:runSshAgent',
    'ssh:config:authSsh',
    'ssh:config:set_sudo_password',
    'ssh:config:gitSsh',
    'ssh:config:gitSshInfo',
]);
