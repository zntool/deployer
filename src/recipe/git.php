<?php

namespace Deployer;

use SebastianBergmann\CodeCoverage\Report\PHP;

task('git:config', function () {
    /*$configCode = ServerConsole::run('{{bin/git}} config --list');
    $configLines = explode(PHP_EOL, $configCode);
    $config = [];
    foreach ($configLines as $line) {
        if(!empty($line)) {
            list($name, $value) = explode('=', $line);
            if($name) {
                $config[$name] = $value;
            }
        }
    }*/
    $config = ServerGit::configList();
    if(empty($config['user.name'])) {
        View::head('GIT: set user.name');
        ServerGit::config('user.name', 'Deployer', true);
//        ServerConsole::run('{{bin/git}} config --global user.name "Deployer"');
    }
    if(empty($config['user.email'])) {
        View::head('GIT: set user.email');
        ServerGit::config('user.email', 'deployer@example.com', true);
//        ServerConsole::run('{{bin/git}} config --global user.email "deployer@example.com"');
    }
});

task('git:add_all', function () {
    View::head('GIT: add all to index');
    ServerConsole::cd('{{release_path}}');
    ServerGit::add();
//    ServerConsole::run('{{bin/git}} add .');
});

task('git:commit', function () {
    ServerConsole::cd('{{release_path}}');
    /*$output = ServerGit::status();
//    $output = ServerConsole::run('{{bin/git}} status');
    if(strpos($output, 'nothing to commit') !== false) {*/
    if(ServerGit::isHasChanges()) {
        View::head('Nothing to commit');
    } else {
        View::head('GIT: commit');
        ServerGit::commit();
//        ServerConsole::run('{{bin/git}} commit -m upd');
    }
});

task('git:stash', function () {
    View::head('GIT: stash');
    ServerConsole::cd('{{release_path}}');
    ServerGit::stash();
//    ServerConsole::run('{{bin/git}} stash');
});

task('git:clone', function () {
    ServerFs::makeDirectory('{{release_path}}');
    $isExists = ServerFs::isFileExists("{{release_path}}/composer.json");
    if ($isExists) {
        View::warning('GIT repository already exists');
        return;
    }
    writeln('git clone');
    ServerGit::clone('{{repository}}', '{{branch}}', '{{release_path}}');
//    ServerConsole::run("{{bin/git}} clone -b {{branch}} -q --depth 1 {{repository}} {{release_path}}");
});

task('git:pull', function () {
    View::head('GIT: pull');
    ServerConsole::cd('{{release_path}}');
    $output = ServerGit::pull();
//    $output = ServerConsole::run('{{bin/git}} pull');
    View::result($output);
});

task('git:push', function () {
    View::head('GIT: push');
    ServerConsole::cd('{{release_path}}');
    $output = ServerGit::push();
//    $output = ServerConsole::run('{{bin/git}} push');
    View::result($output);
});

task('git:checkout', function () {
    View::head('GIT: checkout');
    ServerConsole::cd('{{release_path}}');
    ServerGit::checkout('{{branch}}');
//    ServerConsole::run('{{bin/git}} checkout {{branch}}');
});
