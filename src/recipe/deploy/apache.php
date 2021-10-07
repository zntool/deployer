<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

task('apache:restart', function () {
    ServerApache::restart();
});

task('hosts:update', function () {
    ServerApache::addHost(get('domain'));
    /*$content = ServerFs::downloadContent('/etc/hosts');
    if(strpos($content, get('domain')) === false) {
        $content .= PHP_EOL . '127.0.0.1 ' . get('domain');
    }
//    ServerConsole::runSudo('su - user');
//    ServerFs::makeDirectory('~/tmp');
    ServerFs::uploadContent($content, '~/tmp/hosts');
    ServerConsole::runSudo('mv -f ~/tmp/hosts /etc/hosts');
//    ServerFs::uploadContent($content, '/etc/hosts');*/
});

task('apache:add_conf', function () {

    $template = '<VirtualHost *:80>
ServerName {{domain}}
DocumentRoot {{deploy_path}}/{{public_directory}}
</VirtualHost>';

    $code = TemplateHelper::render($template, [
        'domain' => get('domain'),
        'deploy_path' => get('deploy_path'),
        'public_directory' => get('public_directory'),
    ], '{{', '}}');
    $file = get('domain') . '.conf';
    ServerFs::uploadContentIfNotExist($code, '/etc/apache2/sites-available/' . $file);

//    $dir = TempHelper::getTmpDirectory('apache_conf');
//    $fileName = $dir . '/' . $file;
//    FileHelper::save($fileName, $code);
//    ServerFs::uploadIfNotExist($fileName, '/etc/apache2/sites-available/' . $file);
});
