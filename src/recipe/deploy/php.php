<?php

namespace Deployer;

task('php:install:add-apt-repository', function () {
    ServerConsole::runSudo('add-apt-repository -y ppa:ondrej/php');
});

task('php:install:apt-update', function () {
    ServerConsole::runSudo('apt-get update -y');
});

task('php:install:php', function () {
    ServerConsole::runSudo('apt-get install php7.2 php7.2-cli php7.2-common -y');
});

task('php:install:ext', function () {
    ServerConsole::runSudo('apt-get install php7.2-gmp php7.2-curl php7.2-zip php7.2-gd php7.2-json php7.2-mbstring php7.2-intl php7.2-mysql php7.2-sqlite3 php7.2-xml php7.2-zip php-imagick -y');
});

task('php:config:set_permission', function () {
    ServerConsole::runSudo('chmod -R ugo+rwx /etc/php');
});

task('php:config:update-config', function () {
    $content = ServerFs::downloadContent('/etc/php/7.2/apache2/php.ini');
    $content = preg_replace('#short_open_tag\s*=\s*Off#i', 'short_open_tag=On', $content);
    ServerFs::uploadContent($content, '/etc/php/7.2/apache2/php.ini');
});

task('php:install', [
    'php:install:add-apt-repository',
    'php:install:apt-update',
    'php:install:php',
    'php:install:ext',
]);

task('php:config', [
    'php:config:set_permission',
    'php:config:update-config',
]);