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
    cd('{{release_path}}');
    $output = run('{{bin/git}} status');
    if(strpos($output, 'nothing to commit') !== false) {
        Console::writelnHead('Nothing to commit');
    } else {
        Console::writelnHead('GIT: commit');
        run('{{bin/git}} commit -m upd');
    }
});

task('git:stash', function () {
    Console::writelnHead('GIT: stash');
    cd('{{release_path}}');
    run('{{bin/git}} stash');
});

task('git:clone', function () {
    ServerFs::makeDirectory('{{release_path}}');
    $isExists = ServerFs::isFileExists("{{release_path}}/composer.json");
    if (!$isExists) {
        writeln('git clone');
        ServerConsole::runSudo("{{bin/git}} clone -b {{branch}} -q --depth 1 {{repository}} {{release_path}}");
    }
//    ServerFs::makeDirectory('{{release_path}}/.dep');
});

task('git:pull', function () {
    Console::writelnHead('GIT: pull');
    cd('{{release_path}}');
    $output = run('{{bin/git}} pull');
    Console::writelnResult($output);
});

task('git:push', function () {
    Console::writelnHead('GIT: push');
    cd('{{release_path}}');
    $output = run('{{bin/git}} push');
    Console::writelnResult($output);
});

task('git:checkout', function () {
    Console::writelnHead('GIT: checkout');
    cd('{{release_path}}');
    run('{{bin/git}} checkout {{branch}}');
});
