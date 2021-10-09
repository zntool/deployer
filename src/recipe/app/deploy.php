<?php

namespace Deployer;

use Deployer\Exception\GracefulShutdownException;

require_once __DIR__ . '/../../../../../deployer/deployer/recipe/common.php';
require_once __DIR__ . '/../../../../../zntool/deployer/src/recipe/deploy/all.php';

task('release:update_permissions', function () {
    $permissions = get('permissions');
    foreach ($permissions as $permission) {
        ServerFs::chmodRecurse($permission['path']);
    }
});

task('deploy', [
    'deploy:info',
    'confirm',
    'deploy:lock',
    'benchmark:start',
    'release:create',
    'code:update',
//    'release:update_symlinks',
    'release:update_symlinks:public',
//    'release:update_symlinks:var',
//    'update:permissions',
//    'create:symlinks',
    'composer:install',
    'zn:init',
    'release:update_symlinks:var',
    'release:update_symlinks:env_local',
    'zn:migrate_up',
//    'zn:fixtures_import',
    'release:update_permissions',
    'release:configure_domain',
    'deploy:unlock',
    'release:cleanup',
    'notify:finished',
]);

// if deployment fails, automatically unlock
after('deploy:failed', 'deploy:unlock');
//before('deploy:symlink', 'artisan:migrate');
