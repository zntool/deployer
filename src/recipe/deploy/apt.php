<?php

namespace Deployer;

task('apt:update', function () {
    ServerApt::update();
});

task('apt:find', function () {
    writeln(ServerApt::isInstalled('php7.2'));
});
