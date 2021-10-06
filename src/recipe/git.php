<?php

namespace Deployer;

use SebastianBergmann\CodeCoverage\Report\PHP;

task('git:config', function () {
    $configCode = run('{{bin/git}} config --list');
    $configLines = explode(PHP_EOL, $configCode);
    $config = [];
    foreach ($configLines as $line) {
        if(!empty($line)) {
            list($name, $value) = explode('=', $line);
            if($name) {
                $config[$name] = $value;
            }
        }
    }
    if(empty($config['user.name'])) {
        Console::writelnHead('GIT: set user.name');
        run('{{bin/git}} config --global user.name "Deployer"');
    }
    if(empty($config['user.email'])) {
        Console::writelnHead('GIT: set user.email');
        run('{{bin/git}} config --global user.email "deployer@example.com"');
    }
});

task('git:add_all', function () {
    Console::writelnHead('GIT: add all to index');
    cd('{{release_path}}');
    run('{{bin/git}} add .');
});

task('git:commit', function () {
    Console::writelnHead('GIT: commit');
    cd('{{release_path}}');
    run('{{bin/git}} commit -m upd');
});

task('git:stash', function () {
    Console::writelnHead('GIT: stash');
    cd('{{release_path}}');
    run('{{bin/git}} stash');
});

task('git:pull', function () {
    Console::writelnHead('GIT: pull');
    cd('{{release_path}}');
    run('{{bin/git}} pull');
});

task('git:push', function () {
    Console::writelnHead('GIT: push');
    cd('{{release_path}}');
    run('{{bin/git}} push');
});

task('git:checkout', function () {
    Console::writelnHead('GIT: checkout');
    cd('{{release_path}}');
    run('{{bin/git}} checkout {{branch}}');
});
