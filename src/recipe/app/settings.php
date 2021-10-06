<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

task('settings:php', [
    'settings:permissions',
    'settings:user_permissions',
    'settings:link_sites_enabled',
    'settings:copy_apache2_conf',
]);




task('settings:permissions', function () {
    ServerConsole::runSudo('chmod -R ugo+rwx /etc/apache2');
    ServerConsole::runSudo('chmod ugo+rwx /etc/hosts');
//    ServerConsole::runSudo('chmod -R ugo+rwx /var/www');

    ServerConsole::runSudo('chown user:www-data /var/www');
    ServerConsole::runSudo('chmod g+s /var/www');
});

task('settings:user_permissions', function () {
    ServerConsole::runSudo('usermod -aG www-data user');
    ServerConsole::runSudo('chfn -o umask=022 user');
});

task('settings:link_sites_enabled', function () {
    if(!ServerFs::isFileExists('/etc/apache2/sites-enabled.bak')) {
        ServerConsole::runSudo('mv /etc/apache2/sites-enabled /etc/apache2/sites-enabled.bak');
        ServerConsole::runSudo('ln -s /etc/apache2/sites-available /etc/apache2/sites-enabled');
    }
});

task('settings:copy_apache2_conf', function () {
    if(!ServerFs::isFileExists('/etc/apache2/apache2.conf.bak')) {
        ServerConsole::runSudo('mv /etc/apache2/apache2.conf /etc/apache2/apache2.conf.bak');
        ServerFs::uploadIfNotExist('/home/common/var/www/znexample/deployer/docs/apache2.conf', '/etc/apache2/apache2.conf');
    }
});



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
