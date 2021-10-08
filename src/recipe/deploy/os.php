<?php

namespace Deployer;

task('os:common_name', function () {
    $output = run('uname -a');
    Console::writelnInfo($output);
})->shallow();
