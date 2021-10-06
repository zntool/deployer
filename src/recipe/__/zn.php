<?php

namespace Deployer;

task('project:init', function () {
    $output = Zn::run('init --env=Ci --overwrite=All');
    /*cd('{{release_path}}/vendor/bin');
    $output = run('{{bin/php}} zn init --env=Ci --overwrite=All');*/
//    writeln($output);
    Console::writelnResult($output);
})->desc('Initialization');

