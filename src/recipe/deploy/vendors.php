<?php

namespace Deployer;

// update external libraries (npm, composer, etc)
task('vendors:composer_install', function () {
    Console::writelnHead('Updating composer');
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

task('vendors:composer_update', function () {
    Console::writelnHead('Updating composer');
    cd('{{release_path}}');
    run('{{bin/composer}} up');
});

task('vendors:npm_install', function () {
    Console::writelnHead('Updating npm');
    cd( '{{release_path}}/html');
    run( 'npm update');
    
    /*
    cd( '{{release_path}}/html/final');
    run( 'npm update');*/
});