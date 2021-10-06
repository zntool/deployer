<?php

namespace Deployer;

task('tools:destroy', function () {
    $output = run('rm -rf {{deploy_path}}');
    writeln($output);
});

task('tools:set_root', function () {
    $output = ServerConsole::runSudo('whoami');
    writeln($output);

    $output = ServerConsole::runSudo('usermod -aG sudo {{host_user}}');
    writeln($output);

    $output = ServerConsole::runSudo('whoami');
    writeln($output);
});
