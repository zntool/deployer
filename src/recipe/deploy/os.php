<?php

namespace Deployer;

task('os:common_name', function () {
    $output = ServerConsole::run('uname -a');
    View::info($output);
})->shallow();
