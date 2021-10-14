<?php

namespace Deployer;

set('php_version', '7.2');
set('phpv', 'php{{php_version}}');
set('php_base_packages', [
    '{{phpv}}',
    '{{phpv}}-cli',
    '{{phpv}}-common',
]);
set('php_ext_packages', [
    '{{phpv}}-gmp',
    '{{phpv}}-curl',
    '{{phpv}}-zip',
    '{{phpv}}-gd',
    '{{phpv}}-json',
    '{{phpv}}-mbstring',
    '{{phpv}}-intl',
    '{{phpv}}-mysql',
    '{{phpv}}-sqlite3',
    '{{phpv}}-xml',
    '{{phpv}}-zip',
//    'php-imagick',
    '{{phpv}}-imagick',
]);

task('php:install:add-apt-repository', function () {
    ServerPackage::addRepository('ppa:ondrej/php');
//    ServerConsole::run('sudo add-apt-repository -y ppa:ondrej/php');
});

/*task('php:install:apt-update', function () {
    ServerApt::update();
//    ServerConsole::run('sudo apt-get update -y');
});*/

task('php:install:base', function () {
    /*$packages = [
        'php7.2',
        'php7.2-cli',
        'php7.2-common',
    ];*/
    ServerPackage::installBatch(get('php_base_packages'));

    /*if(ServerApt::isInstalled('php7.2')) {
        View::warning('Alredy installed!');
        return;
    }*/
//    ServerApt::install('  ');
//    ServerConsole::run('sudo apt-get install php7.2 php7.2-cli php7.2-common -y');
});

task('php:install:ext', function () {
    ServerPackage::installBatch(get('php_ext_packages'));

//    ServerConsole::run('sudo apt-get install php7.2-gmp php7.2-curl php7.2-zip php7.2-gd php7.2-json php7.2-mbstring php7.2-intl php7.2-mysql php7.2-sqlite3 php7.2-xml php7.2-zip php-imagick -y');
});

task('php:config:set_permission', function () {
    ServerFs::chmod('/etc/php', 'ugo+rwx', true);
//    ServerConsole::run('sudo chmod -R ugo+rwx /etc/php');
});

task('php:config:update_config', function () {
    ServerPhp::setConfig('/etc/php/7.2/apache2/php.ini', [
        'short_open_tag' => 'On',
    ]);
    ServerPhp::setConfig('/etc/php/7.2/cli/php.ini', [
        'short_open_tag' => 'On',
        'memory_limit' => '512M',
        'max_input_time' => '600',
        'max_execution_time' => '120',
    ]);
});

task('php:install', [
    'php:install:add-apt-repository',
    'linux:package:update',
    'php:install:base',
    'php:install:ext',
]);

task('php:config', [
    'php:config:set_permission',
    'php:config:update_config',
]);
