<?php

namespace Deployer;

set('bin/npm', function () {
    return locateBinaryPath('npm');
});

task('npm:install:base', function () {
    ServerPackage::install('npm');
});

task('npm:build', function () {
    ServerConsole::cd( '{{release_path}}');
    ServerConsole::run( '{{bin/npm}} run build');
});



/*task('npm:install', [
    'npm:install:base',
]);*/

