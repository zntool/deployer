<?php

namespace Deployer;

task('apt:update', function () {
    ServerApt::update();
});

task('apt:find', function () {
    ServerApt::find('php');
});