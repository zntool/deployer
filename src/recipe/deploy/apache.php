<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

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
    $dir = TempHelper::getTmpDirectory('apache_conf');
    $file = get('domain') . '.conf';
    $fileName = $dir . '/' . $file;
    FileHelper::save($fileName, $code);
    ServerFs::uploadIfNotExist($fileName, '/etc/apache2/sites-available/' . $file);
});
