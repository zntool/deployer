<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

task('apache:restart', function () {
    ServerApache::restart();
});

task('apache:start', function () {
    ServerApache::start();
});

task('apache:status', function () {
    writeln(ServerApache::status());
});

task('apache:config:add_conf', function () {
    ServerApache::removeConf(get('domain'));
    ServerApache::addConf(get('domain'), get('deploy_public_path'));
});

task('apache:install:base', function () {
    if(ServerApt::isInstalled('apache2')) {
        View::warning('Alredy installed!');
        return;
    }
    ServerApt::install('apache2');
});

task('apache:config:remove_conf', function () {
    ServerApache::removeConf(get('domain'));
});

task('apache:config:enable_rewrite', function () {
    ServerConsole::run('sudo a2enmod rewrite');
});

/*task('apache:config:update_config', function () {
    $content = ServerFs::downloadContent('/etc/apache2/apache2.conf');
    $content .= PHP_EOL . PHP_EOL . '';
    $content = preg_replace('#short_open_tag\s*=\s*Off#i', 'short_open_tag=On', $content);
    ServerFs::uploadContent($content, '/etc/apache2/apache2.conf');
});*/

task('apache:config:set_permission', function () {
    ServerConsole::run('sudo chmod -R ugo+rwx /etc/apache2');
    ServerConsole::run('sudo chown {{host_user}}:www-data /var/www');
    ServerConsole::run('sudo chmod g+s /var/www');
});

task('apache:config:update_config', function () {
    $sourceConfigFile = realpath(__DIR__ . '/../../resources/apache2.conf');
    if(!ServerFs::isFileExists('/etc/apache2/apache2.conf.bak')) {
        ServerConsole::run('sudo mv /etc/apache2/apache2.conf /etc/apache2/apache2.conf.bak');
        ServerFs::uploadIfNotExist($sourceConfigFile, '/etc/apache2/apache2.conf');
        //ServerApache::restart();
    }
});

task('apache:config:enable_autorun', function () {
    ServerConsole::run('sudo systemctl enable apache2');
});

task('apache:config:link_sites_enabled', function () {
    if(!ServerFs::isFileExists('/etc/apache2/sites-enabled.bak')) {
        ServerConsole::run('sudo mv /etc/apache2/sites-enabled /etc/apache2/sites-enabled.bak');
        ServerConsole::run('sudo ln -s /etc/apache2/sites-available /etc/apache2/sites-enabled');
        //ServerApache::restart();
    }
});

task('apache:install', [
    'apache:install:base',
    'apache:start',
]);

task('apache:config', [
    'apache:config:enable_rewrite',
    'apache:config:set_permission',
    'apache:config:link_sites_enabled',
    'apache:config:update_config',
    'apache:config:enable_autorun',
    'apache:restart',
    'apache:status',
]);
