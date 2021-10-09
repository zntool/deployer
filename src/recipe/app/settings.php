<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

task('settings:access', [
    'ssh:config',
    'settings:permissions:user',
    'settings:permissions:file',
]);

task('settings:soft', [
    'apache:install',
    'apache:config',

    'php:install',
    'php:config',
]);

task('settings:permissions:file', function () {
    ServerConsole::runSudo('chmod ugo+rwx /etc/hosts');
//    ServerConsole::runSudo('chown user:www-data /etc/hosts');
//    ServerConsole::runSudo('chmod -R ugo+rwx /var/www');
    /*ServerConsole::runSudo('chown user:www-data /var/www');
    ServerConsole::runSudo('chmod g+s /var/www');*/
});

task('settings:permissions:user', function () {
    ServerConsole::runSudo('usermod -aG www-data {{host_user}}');
    ServerConsole::runSudo('chfn -o umask=022 {{host_user}}');
});

task('settings:env_info', function () {
    writeln('');

    $output = ServerConsole::run('uname -a');
    writeln($output);

    writeln('');

    $output = ServerConsole::run('{{bin/php}} -v');
    writeln($output);

    writeln('');

    $output = ServerConsole::run('{{bin/git}} --version');
    writeln($output);

    writeln('');
});


