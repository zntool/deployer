<?php

namespace Deployer;

use ZnCore\Base\Helpers\TemplateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

task('test-cors:install:config', function () {
    set('deploy_path', '/var/www/tool/test-cors');
    set('deploy_public_path', '{{deploy_path}}/client/static');
    set('domains', [
        [
            'domain' => 'test-cors.tool',
            'directory' => '{{deploy_public_path}}',
        ],
    ]);
});

task('test-cors:install:base', function () {
    ServerFs::removeDir('{{deploy_path}}');
    /*if(ServerFs::isDirectoryExists('{{deploy_path}}')) {
        View::warning('Test CORS already installed');
        return;
    }*/
    ServerGit::clone('git@github.com:monsur/test-cors.org.git', 'master', get('deploy_path'));
});

task('test-cors:install', [
    'deploy:info',
    //'deploy:lock',
    'benchmark:start',
    'test-cors:install:config',
    'test-cors:install:base',
    'release:configure_domain',
    //'deploy:unlock',
    'notify:finished',
//    'success',
]);

after('deploy:failed', 'deploy:unlock');
