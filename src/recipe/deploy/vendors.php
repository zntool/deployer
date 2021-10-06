<?php

namespace Deployer;

// update external libraries (npm, composer, etc)
task('vendors:composer_install', function () {
    Console::writelnHead('<info>  Updating composer</info>');
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

task('vendors:npm_install', function () {
    Console::writelnHead('<info>  Updating npm</info>');
    cd( '{{release_path}}/html');
    run( 'npm update');
    
    /*
    cd( '{{release_path}}/html/final');
    run( 'npm update');*/
});