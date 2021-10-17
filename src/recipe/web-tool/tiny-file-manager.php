<?php

namespace Deployer;

use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

// https://github.com/prasathmani/tinyfilemanager
// Default username/password: admin/admin@123 and user/12345.

task('tiny-file-manager:install:config', function () {
    set('deploy_path', '/var/www/tool/tiny-file-manager');
    set('deploy_public_path', '{{deploy_path}}');
    set('domains', [
        [
            'domain' => 'tiny-file-manager.tool',
            'directory' => '{{deploy_public_path}}',
        ],
    ]);
});

task('tiny-file-manager:install:base', function () {
    ServerFs::removeDir('{{deploy_path}}');
    /*if(ServerFs::isDirectoryExists('{{deploy_path}}')) {
        View::warning('Test CORS already installed');
        return;
    }*/
    ServerGit::clone('git@github.com:prasathmani/tinyfilemanager.git', 'master', get('deploy_path'));

    $destFile = '.htaccess';
    $destDirectory = get('deploy_path');
    $destFilePath = $destDirectory . '/' . $destFile;
    $htaccessFile = __DIR__ . '/../../resources/.htaccess';
    $htaccessContent = FileHelper::load($htaccessFile);
    $htaccessContent = TemplateHelper::render($htaccessContent, ['endpointScript' => 'tinyfilemanager.php'], '{{', '}}');
    //dd($destFilePath);
    $htaccessContent .= PHP_EOL . 'DirectoryIndex tinyfilemanager.php';
//    dd($htaccessContent);
    ServerFs::uploadContent($htaccessContent, $destFilePath);
});

task('tiny-file-manager:install', [
    'deploy:info',
    //'deploy:lock',
    'benchmark:start',
    'tiny-file-manager:install:config',
    'tiny-file-manager:install:base',
    'release:configure_domain',
    //'deploy:unlock',
    'hosts:list:lamp',
    'notify:finished',
//    'success',
]);

task('tiny-file-manager:remove', [
    'deploy:info',
//    'deploy:profile',
    'benchmark:start',
    'tools:destroy:confirm',
    'tiny-file-manager:install:config',
    'tools:destroy:remove_project_dir',
    'apache:config:remove_conf',
    'apache:restart',
    'hosts:remove',
    'hosts:list:lamp',
    'notify:finished',
]);

after('deploy:failed', 'deploy:unlock');
