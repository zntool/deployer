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
    $config = Git::configList();
    if(empty($config['user.name'])) {
        Console::writelnHead('GIT: set user.name');
        Git::config('user.name', 'Deployer', true);
//        ServerConsole::run('{{bin/git}} config --global user.name "Deployer"');
    }
    if(empty($config['user.email'])) {
        Console::writelnHead('GIT: set user.email');
        Git::config('user.email', 'deployer@example.com', true);
//        ServerConsole::run('{{bin/git}} config --global user.email "deployer@example.com"');
    }
});

task('git:add_all', function () {
    Console::writelnHead('GIT: add all to index');
    cd('{{release_path}}');
    Git::add();
//    ServerConsole::run('{{bin/git}} add .');
});

task('git:commit', function () {
    cd('{{release_path}}');
    /*$output = Git::status();
//    $output = ServerConsole::run('{{bin/git}} status');
    if(strpos($output, 'nothing to commit') !== false) {*/
    if(Git::isHasChanges()) {
        Console::writelnHead('Nothing to commit');
    } else {
        Console::writelnHead('GIT: commit');
        Git::commit();
//        ServerConsole::run('{{bin/git}} commit -m upd');
    }
});

task('git:stash', function () {
    Console::writelnHead('GIT: stash');
    cd('{{release_path}}');
    Git::stash();
//    ServerConsole::run('{{bin/git}} stash');
});

task('git:clone', function () {
    ServerFs::makeDirectory('{{release_path}}');
    $isExists = ServerFs::isFileExists("{{release_path}}/composer.json");
    if ($isExists) {
        Console::writelnWarning('GIT repository already exists');
        return;
    }
    writeln('git clone');
    Git::clone('{{repository}}', '{{branch}}', '{{release_path}}');
//    ServerConsole::run("{{bin/git}} clone -b {{branch}} -q --depth 1 {{repository}} {{release_path}}");
});

task('git:pull', function () {
    Console::writelnHead('GIT: pull');
    cd('{{release_path}}');
    $output = Git::pull();
//    $output = ServerConsole::run('{{bin/git}} pull');
    Console::writelnResult($output);
});

task('git:push', function () {
    Console::writelnHead('GIT: push');
    cd('{{release_path}}');
    $output = Git::push();
//    $output = ServerConsole::run('{{bin/git}} push');
    Console::writelnResult($output);
});

task('git:checkout', function () {
    Console::writelnHead('GIT: checkout');
    cd('{{release_path}}');
    Git::checkout('{{branch}}');
//    ServerConsole::run('{{bin/git}} checkout {{branch}}');
});
