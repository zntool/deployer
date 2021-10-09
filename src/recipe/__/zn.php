<?php

namespace Deployer;

task('zn:init', function () {
    $output = Zn::run('init --env=Ci --overwrite=All');
    /*cd('{{release_path}}/vendor/bin');
    $output = ServerConsole::run('{{bin/php}} zn init --env=Ci --overwrite=All');*/
//    writeln($output);
    View::result($output);
})->desc('Initialization');

