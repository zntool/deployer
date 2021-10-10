<?php

namespace Deployer;

task('linux:package:update', function () {
    ServerPackage::update();
});

task('linux:package:find', function () {
    writeln(ServerPackage::isInstalled('php7.2'));
});
