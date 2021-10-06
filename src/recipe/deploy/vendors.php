<?php

namespace Deployer;

// update external libraries (npm, composer, etc)
task('composer:install', function () {
    Console::writelnHead('Composer install');
    cd('{{release_path}}');
    run('{{bin/composer}} {{composer_options}}');  //    run( 'composer install --no-dev');
//    cd( '{{release_path}}/html/final');
//    run( 'composer install --no-dev');

    /*writeln( '<info>  Updating npm</info>');
    cd( '{{release_path}}/html');
    run( 'npm update');
    cd( '{{release_path}}/html/final');
    run( 'npm update');*/
});

task('composer:update', function () {
    Console::writelnHead('Composer update');
    cd('{{release_path}}');
    $output = run('{{bin/composer}} up');
    Console::writelnResult($output);
});

task('npm:update', function () {
    Console::writelnHead('NPM update');
    cd( '{{release_path}}/html');
    run( 'npm update');
});

task('npm:install', function () {
    Console::writelnHead('NPM install');
    cd( '{{release_path}}/html');
    run( 'npm install');
});
