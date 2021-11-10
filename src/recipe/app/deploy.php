<?php

namespace Deployer;

use Deployer\Exception\GracefulShutdownException;

require_once __DIR__ . '/../../../../../deployer/deployer/recipe/common.php';
require_once __DIR__ . '/../../../../../zntool/deployer/src/recipe/deploy/all.php';

set('permissions', []);

task('release:update_permissions', function () {
    $permissions = get('permissions', null);
    if(empty($permission)) {
        return;
    }
    foreach ($permissions as $permission) {
        ServerFs::chmod($permission['path'], 'a+w', true);
    }
});


task('deploy:start', [
    'deploy:info',
    'deploy:profile',
    'confirm',
    'deploy:lock',
    'benchmark:start',
    'release:create',
    'code:update',
//    'update:permissions',
//    'create:symlinks',
]);

task('deploy:end', [
    'release:update_permissions',
    'release:configure_domain',
    'release:update_symlinks:current',
//    'release:git:create_tag',
    'deploy:unlock',
    'release:cleanup',
    'hosts:list:lamp',
    'notify:finished',
//    'success',
]);


task('deploy', [
    'deploy:start',

    'composer:install',
    'zn:init',
//    'release:update_symlinks:public',
    'release:update_symlinks:var',
    'release:update_symlinks:env_local',
    'zn:migrate_up',
    'zn:fixtures_import',

    'deploy:end',
]);

after('deploy:failed', 'deploy:unlock');

task('deploy:npm:client', [
    'deploy:start',

    'npm:install',
    'npm:build',

    'deploy:end',
]);

task('deploy:npm:client:failed', function () {
})->setPrivate();
fail('deploy:npm:client', 'deploy:npm:client:failed');
after('deploy:npm:client:failed', 'deploy:unlock');

task('deploy:unlock:profile', [
    'deploy:info',
    'deploy:profile',
    'deploy:unlock',
]);

// if deployment fails, automatically unlock

//before('deploy:symlink', 'artisan:migrate');

after('rollback', 'apache:restart');
