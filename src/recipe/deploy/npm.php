<?php

namespace Deployer;

set('bin/npm', function () {
    return locateBinaryPath('npm');
});

task('npm:install:base', function () {
    ServerPackage::install('npm');
});

task('npm:install', [
    'npm:install:base',
]);
