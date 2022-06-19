<?php

namespace Deployer;

use ZnCore\Base\Libs\Text\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnCore\Base\Libs\FileSystem\Helpers\FileStorageHelper;

task('adminer:install:config', function () {
    set('deploy_path', '/var/www/tool/adminer');
    set('deploy_public_path', '{{deploy_path}}');
    set('domains', [
        [
            'domain' => 'adminer.tool',
            'directory' => '{{deploy_public_path}}',
        ],
    ]);
});

task('adminer:install:base', function () {
    $url = 'https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php';
    $hash = 'c86e050053807d5b76c74f80f1fe0f94f64feb93ed78cdbc10547420b5ca2cdb9b77642dff555daa33eadeeb45c6dae9';
    $destFile = 'adminer-4.8.1.php';
    $destDirectory = get('deploy_path');
    $destFilePath = $destDirectory . '/' . $destFile;
    if(ServerFs::isFileExists($destDirectory)) {
        View::warning('Adminer already installed');
        return;
    }
    ServerFs::makeDirectory($destDirectory);
    ServerConsole::cd($destDirectory);
    ServerConsole::run("{{bin/php}} -r \"copy('$url', '$destFile');\"");
    ServerFs::checkFileHash($destFilePath, $hash);
    
    $indexFile = __DIR__ . '/../../resources/adminer/index.php';
    $indexContent = FileStorageHelper::load($indexFile);
    $indexContent = TemplateHelper::render($indexContent, ['adminerPhpModule' => $destFile], '{{', '}}');
    ServerFs::uploadContent($indexContent, $destDirectory . '/index.php');
});

task('adminer:install', [
    'deploy:info',
    //'deploy:lock',
    'benchmark:start',
    'adminer:install:config',
    'adminer:install:base',
    'release:configure_domain',
    //'deploy:unlock',
    'hosts:list:lamp',
    'notify:finished',
//    'success',
]);

task('adminer:remove', [
    'deploy:info',
//    'deploy:profile',
    'benchmark:start',
    'tools:destroy:confirm',
    'adminer:install:config',
    'tools:destroy:remove_project_dir',
    'apache:config:remove_conf',
    'apache:restart',
    'hosts:remove',
    'hosts:list:lamp',
    'notify:finished',
]);

after('deploy:failed', 'deploy:unlock');
