<?php

namespace Deployer;

require_once __DIR__ . '/../git.php';
require_once __DIR__ . '/../../../../../deployer/deployer/recipe/common.php';




/*task('code:init', function () {
    ServerFs::makeDirectory('{{release_path}}');
    $isExists = ServerFs::isFileExists("{{release_path}}/.env");
    if (!$isExists) {
        writeln('git clone');
        ServerConsole::run("{{sudo_cmd}} {{bin/git}} clone -b {{branch}} -q --depth 1 {{repository}} {{release_path}}");
    }
    ServerFs::makeDirectory('{{release_path}}/.dep');
});*/

// this task runs all the subtasks defined above
task('upgrade_vendor', [
    'os:common_name',
    'deploy:info',
    'confirm',
    'benchmark:start',
    'tools:set_upgrade_mode',
    'git:clone',
    'git:stash',
    'git:checkout',
    'git:pull',
    'composer:update',
    'git:config',
    'git:add_all',
    'git:commit',
    'git:push',
    'notify:finished',
]);

// if deployment fails, automatically unlock
after('deploy:failed', 'deploy:unlock');
