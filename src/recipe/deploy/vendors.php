<?php

namespace Deployer;

// update external libraries (npm, composer, etc)
task('composer:install', function () {
    View::head('Composer install');
    ServerConsole::cd('{{release_path}}');
    ServerConsole::run('{{bin/composer}} {{composer_options}}');  //    ServerConsole::run( 'composer install --no-dev');
//    ServerConsole::cd( '{{release_path}}/html/final');
//    ServerConsole::run( 'composer install --no-dev');

    /*writeln( '<info>  Updating npm</info>');
    ServerConsole::cd( '{{release_path}}/html');
    ServerConsole::run( 'npm update');
    ServerConsole::cd( '{{release_path}}/html/final');
    ServerConsole::run( 'npm update');*/
});

task('composer:update', function () {
    View::head('Composer update');
    ServerConsole::cd('{{release_path}}');
    $output = ServerConsole::run('{{bin/composer}} up');
    View::result($output);
});

/*task('npm:update', function () {
    View::head('NPM update');
    ServerConsole::cd( '{{release_path}}/html');
    ServerConsole::run( '{{bin/npm}} update');
});

task('npm:install', function () {
    View::head('NPM install');
    ServerConsole::cd( '{{release_path}}/html');
    ServerConsole::run( '{{bin/npm}} install');
});*/
