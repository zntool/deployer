<?php

namespace Deployer;

task('php:install:add-apt-repository', function () {
    ServerApt::addRepository('ppa:ondrej/php');
//    ServerConsole::runSudo('add-apt-repository -y ppa:ondrej/php');
});

/*task('php:install:apt-update', function () {
    ServerApt::update();
//    ServerConsole::runSudo('apt-get update -y');
});*/

task('php:install:base', function () {
    if(ServerApt::isInstalled('php7.2')) {
        Console::writelnWarning('Alredy installed!');
        return;
    }
    ServerApt::install('php7.2 php7.2-cli php7.2-common');
//    ServerConsole::runSudo('apt-get install php7.2 php7.2-cli php7.2-common -y');
});

task('php:install:ext', function () {
    if(ServerApt::isInstalled('php7.2-gmp')) {
        Console::writelnWarning('Alredy installed!');
        return;
    }
    ServerApt::install('php7.2-gmp php7.2-curl php7.2-zip php7.2-gd php7.2-json php7.2-mbstring php7.2-intl php7.2-mysql php7.2-sqlite3 php7.2-xml php7.2-zip php-imagick');
//    ServerConsole::runSudo('apt-get install php7.2-gmp php7.2-curl php7.2-zip php7.2-gd php7.2-json php7.2-mbstring php7.2-intl php7.2-mysql php7.2-sqlite3 php7.2-xml php7.2-zip php-imagick -y');
});

task('php:config:set_permission', function () {
    ServerConsole::runSudo('chmod -R ugo+rwx /etc/php');
});

task('php:config:update_config', function () {
    $content = ServerFs::downloadContent('/etc/php/7.2/apache2/php.ini');
    $content = preg_replace('#short_open_tag\s*=\s*Off#i', 'short_open_tag=On', $content);
    ServerFs::uploadContent($content, '/etc/php/7.2/apache2/php.ini');
});

task('php:install', [
    'php:install:add-apt-repository',
    'apt:update',
    'php:install:base',
    'php:install:ext',
]);

task('php:config', [
    'php:config:set_permission',
    'php:config:update_config',
]);
