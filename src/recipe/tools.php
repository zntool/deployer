<?php

namespace Deployer;

task('tools:destroy:remove_project_dir', function () {
    ServerFs::removeDir('{{deploy_path}}');
});

task('tools:destroy', [
    'tools:destroy:remove_project_dir',
    'apache:config:remove_conf',
    'hosts:remove',
]);

/*task('tools:set_root', function () {
    $output = ServerConsole::run('sudo whoami');
    writeln($output);

    $output = ServerConsole::run('sudo usermod -aG sudo {{host_user}}');
    writeln($output);

    $output = ServerConsole::run('sudo whoami');
    writeln($output);
});*/
