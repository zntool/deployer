<?php

namespace Deployer;

task('os:common_name', function () {
    $output = ServerConsole::run('uname -a');
    Console::writelnInfo($output);
})->shallow();
