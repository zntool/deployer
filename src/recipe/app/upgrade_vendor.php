<?php

namespace Deployer;

require_once __DIR__ . '/../git.php';
require_once __DIR__ . '/../../../../../deployer/deployer/recipe/common.php';
//require_once __DIR__ . '/../deploy/all.php';

// default for deployment is staging
set('default_stage', 'staging');

// if we need to run commands on the target server(s) as sudo, change the next line to: set( 'sudo_cmd', 'sudo');
set('sudo_cmd', '');

// Project name
/*set('application', 'mysite');

// keep the most recent 10 releases
set('keep_releases', 3);

// allocate tty for git clone - this is if you need to enter a passphrase or whatever to
// authenticate with github. for public repos you probably won't need this, for private
// repos you will almost definitely need this. if you aren't sure, it doesn't hurt to keep
// it turned on.
set('git_tty', true);*/

// allow Deployer tool to collect anonymous statistics about usage
set('allow_anonymous_stats', true);

task('tools:set_upgrade_mode', function () {
    set('release_path', '{{deploy_path}}/upgrade');
});

/*task('code:init', function () {
    ServerFs::makeDirectory('{{release_path}}');
    $isExists = ServerFs::isFileExists("{{release_path}}/.env");
    if (!$isExists) {
        writeln('git clone');
        run("{{sudo_cmd}} {{bin/git}} clone -b {{branch}} -q --depth 1 {{repository}} {{release_path}}");
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
