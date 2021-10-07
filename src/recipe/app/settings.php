<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;


task('settings', [
    //'ssh:config:set_sudo_password',
    'settings:user_permissions',
    'ssh:config:up',
    
    'settings:permissions',

    'apache:install',
    'apache:config',

    'php:install',
    'php:config',

    //'settings:set_sudo_password',
//    'settings:link_sites_enabled',
//    'settings:copy_apache2_conf',
]);



task('settings:permissions', function () {
    
    ServerConsole::runSudo('chmod ugo+rwx /etc/hosts');
//    ServerConsole::runSudo('chown user:www-data /etc/hosts');
//    ServerConsole::runSudo('chmod -R ugo+rwx /var/www');
    
    /*ServerConsole::runSudo('chown user:www-data /var/www');
    ServerConsole::runSudo('chmod g+s /var/www');*/
});

task('settings:user_permissions', function () {
    ServerConsole::runSudo('usermod -aG www-data user');
    ServerConsole::runSudo('chfn -o umask=022 user');
});

/*task('settings:link_sites_enabled', function () {
    if(!ServerFs::isFileExists('/etc/apache2/sites-enabled.bak')) {
        ServerConsole::runSudo('mv /etc/apache2/sites-enabled /etc/apache2/sites-enabled.bak');
        ServerConsole::runSudo('ln -s /etc/apache2/sites-available /etc/apache2/sites-enabled');
        ServerApache::restart();
    }
});*/

/*task('settings:copy_apache2_conf', function () {
    $sourceConfigFile = realpath(__DIR__ . '/../../resources/apache2.conf');
    if(!ServerFs::isFileExists('/etc/apache2/apache2.conf.bak')) {
        ServerConsole::runSudo('mv /etc/apache2/apache2.conf /etc/apache2/apache2.conf.bak');
        ServerFs::uploadIfNotExist($sourceConfigFile, '/etc/apache2/apache2.conf');
        ServerApache::restart();
    }
});*/



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


