<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

require_once __DIR__ . '/../../../../../zntool/deployer/src/recipe/web-tool/all.php';

task('settings:access', [
    'ssh:config',
    'settings:permissions:user',
    'settings:permissions:file',
]);


task('lamp:install', [
    'apache:install',
    'apache:config',

    'php:install',
    'php:config',

    'composer:install:base',

    'npm:install',
]);


task('web-tool:install', [
    'adminer:install',
    'test-cors:install',
    'tiny-file-manager:install',
]);


task('settings:permissions:file', function () {
    ServerFs::chmod('/etc/hosts', 'ugo+rwx');
//    ServerConsole::run('sudo chmod ugo+rwx /etc/hosts');
//    ServerConsole::run('sudo chown user:www-data /etc/hosts');
//    ServerConsole::run('sudo chmod -R ugo+rwx /var/www');
    /*ServerConsole::run('sudo chown user:www-data /var/www');
    ServerConsole::run('sudo chmod g+s /var/www');*/
});

task('settings:permissions:user', function () {
    ServerConsole::run('sudo usermod -aG www-data {{host_user}}');
    ServerConsole::run('sudo chfn -o umask=022 {{host_user}}');
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
