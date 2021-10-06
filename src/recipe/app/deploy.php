<?php

namespace Deployer;

require_once __DIR__ . '/../../../../../deployer/deployer/recipe/common.php';
require_once __DIR__ . '/../../../../../zntool/deployer/src/recipe/deploy/all.php';

task('deploy', [
    'deploy:info',
    'confirm',
    'deploy:lock',
    'benchmark:start',
    'release:create',
    'code:update',
//    'update:permissions',
//    'create:symlinks',
    'composer:install',
    'zn:init',
    'zn:migrate_up',
    'zn:fixtures_import',
    'release:update_symlinks',
    'apache:add_conf',
    'deploy:unlock',
    'release:cleanup',
//    'notify:finished',
]);

// if deployment fails, automatically unlock
after('deploy:failed', 'deploy:unlock');
