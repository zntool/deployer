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
    //systemctl status apache2
});

task('apache:config:add_conf', function () {

    ServerApache::addConf(get('domain'), get('deploy_public_path'));
    
    /*$template = '<VirtualHost *:80>
ServerName {{domain}}
DocumentRoot {{deploy_path}}/{{public_directory}}
</VirtualHost>';
    $code = TemplateHelper::render($template, [
        'domain' => get('domain'),
        'deploy_path' => get('deploy_path'),
        'public_directory' => get('public_directory'),
    ], '{{', '}}');
    $file = get('domain') . '.conf';
    ServerFs::uploadContentIfNotExist($code, '/etc/apache2/sites-available/' . $file);*/

//    $dir = TempHelper::getTmpDirectory('apache_conf');
//    $fileName = $dir . '/' . $file;
//    FileHelper::save($fileName, $code);
//    ServerFs::uploadIfNotExist($fileName, '/etc/apache2/sites-available/' . $file);
});



task('apache:install:base', function () {
    if(ServerApt::isInstalled('apache2')) {
        Console::writelnWarning('Alredy installed!');
        return;
    }
    ServerApt::install('apache2');
//    ServerConsole::runSudo('apt-get install apache2 -y');
});

task('apache:config:remove_conf', function () {
    ServerApache::removeConf(get('domain'));
//    $file = get('domain') . '.conf';
//    ServerFs::removeFile('/etc/apache2/sites-available/' . $file);
});

task('apache:config:enable_rewrite', function () {
    ServerConsole::runSudo('a2enmod rewrite');
});

/*task('apache:config:update_config', function () {
    $content = ServerFs::downloadContent('/etc/apache2/apache2.conf');
    $content .= PHP_EOL . PHP_EOL . '';
    $content = preg_replace('#short_open_tag\s*=\s*Off#i', 'short_open_tag=On', $content);
    ServerFs::uploadContent($content, '/etc/apache2/apache2.conf');
});*/

task('apache:config:set_permission', function () {
    ServerConsole::runSudo('chmod -R ugo+rwx /etc/apache2');
    ServerConsole::runSudo('chown {{host_user}}:www-data /var/www');
    ServerConsole::runSudo('chmod g+s /var/www');
});

task('apache:config:update_config', function () {
    $sourceConfigFile = realpath(__DIR__ . '/../../resources/apache2.conf');
    if(!ServerFs::isFileExists('/etc/apache2/apache2.conf.bak')) {
        ServerConsole::runSudo('mv /etc/apache2/apache2.conf /etc/apache2/apache2.conf.bak');
        ServerFs::uploadIfNotExist($sourceConfigFile, '/etc/apache2/apache2.conf');
        //ServerApache::restart();
    }
});

task('apache:config:enable_autorun', function () {
    ServerConsole::runSudo('systemctl enable apache2');
});

task('apache:config:link_sites_enabled', function () {
    if(!ServerFs::isFileExists('/etc/apache2/sites-enabled.bak')) {
        ServerConsole::runSudo('mv /etc/apache2/sites-enabled /etc/apache2/sites-enabled.bak');
        ServerConsole::runSudo('ln -s /etc/apache2/sites-available /etc/apache2/sites-enabled');
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
