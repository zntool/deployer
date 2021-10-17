<?php

namespace Deployer;

use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

// https://github.com/alexantr/filemanager
// Default username/password: fm_admin/fm_admin

task('file-manager:install:config', function () {
    set('deploy_path', '/var/www/tool/file-manager');
    set('deploy_public_path', '{{deploy_path}}');
    set('domains', [
        [
            'domain' => 'file-manager.tool',
            'directory' => '{{deploy_public_path}}',
        ],
    ]);
});

task('file-manager:install:base', function () {
    ServerFs::removeDir('{{deploy_path}}');
    /*if(ServerFs::isDirectoryExists('{{deploy_path}}')) {
        View::warning('Test CORS already installed');
        return;
    }*/
    ServerGit::clone('git@github.com:alexantr/filemanager.git', 'master', get('deploy_path'));

    $destFile = '.htaccess';
    $destDirectory = get('deploy_path');
    $destFilePath = $destDirectory . '/' . $destFile;
    
    $htaccessFile = __DIR__ . '/../../resources/.htaccess';
    $htaccessContent = FileHelper::load($htaccessFile);
    $htaccessContent = TemplateHelper::render($htaccessContent, ['endpointScript' => 'filemanager.php'], '{{', '}}');
    $htaccessContent .= PHP_EOL . 'DirectoryIndex filemanager.php';
    ServerFs::uploadContent($htaccessContent, $destFilePath);
});

task('file-manager:install', [
    'deploy:info',
    //'deploy:lock',
    'benchmark:start',
    'file-manager:install:config',
    'file-manager:install:base',
    'release:configure_domain',
    //'deploy:unlock',
    'hosts:list:lamp',
    'notify:finished',
//    'success',
]);

after('deploy:failed', 'deploy:unlock');
