<?php

namespace Deployer;

use Deployer\Exception\GracefulShutdownException;

require_once __DIR__ . '/../../../../../deployer/deployer/recipe/common.php';
require_once __DIR__ . '/../../../../../zntool/deployer/src/recipe/deploy/all.php';

task('release:update_permissions', function () {
    $permissions = get('permissions', null);
    if(empty($permission)) {
        return;
    }
    foreach ($permissions as $permission) {
        ServerFs::chmod($permission['path'], 'a+w', true);
    }
});

task('deploy', [
    'deploy:info',
    'deploy:profile',
    'confirm',
    'deploy:lock',
    'benchmark:start',
    'release:create',
    'code:update',
//    'update:permissions',
//    'create:symlinks',
    'composer:install',
    'zn:init',
//    'release:update_symlinks:public',
    'release:update_symlinks:var',
    'release:update_symlinks:env_local',
    'zn:migrate_up',
    'zn:fixtures_import',
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

after('deploy:failed', 'deploy:unlock');

task('deploy:react', [
    'deploy:info',
    'deploy:profile',
    'confirm',

    'deploy:lock',
    'benchmark:start',
    'release:create',
    'code:update',
//    'update:permissions',
//    'create:symlinks',

    'npm:install',
    'npm:build',

//    'release:update_symlinks:var',
//    'release:update_symlinks:env_local',
    'release:update_permissions',

    'release:configure_domain',
    'release:update_symlinks:current',
//    'release:git:create_tag',
    'deploy:unlock',
    'release:cleanup',
    'hosts:list:lamp',
    'notify:finished',
]);

task('deploy:unlock:profile', [
    'deploy:info',
    'deploy:profile',
    'deploy:unlock',
]);

// if deployment fails, automatically unlock

//before('deploy:symlink', 'artisan:migrate');

after('rollback', 'apache:restart');
