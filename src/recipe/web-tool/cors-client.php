<?php

namespace Deployer;

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

    $htaccessGenerator = new HtaccessGenerator();
    $htaccessGenerator->setDirectoryIndex(['corsclient.html']);
    $htaccessGenerator->setRewriteRule('.', 'corsclient.html');
    $htaccessContent = $htaccessGenerator->getCode();

    $destFilePath = get('deploy_path') . '/.htaccess';
    ServerFs::uploadContent($htaccessContent, $destFilePath);
});

task('test-cors:install', [
    'deploy:info',
    //'deploy:lock',
    'benchmark:start',
    'test-cors:install:config',
    'test-cors:install:base',
    'release:configure_domain',
    //'deploy:unlock',
    'hosts:list:lamp',
    'notify:finished',
//    'success',
]);

task('test-cors:remove', [
    'deploy:info',
//    'deploy:profile',
    'benchmark:start',
    'tools:destroy:confirm',
    'test-cors:install:config',
    'tools:destroy:remove_project_dir',
    'apache:config:remove_conf',
    'apache:restart',
    'hosts:remove',
    'hosts:list:lamp',
    'notify:finished',
]);

after('deploy:failed', 'deploy:unlock');
