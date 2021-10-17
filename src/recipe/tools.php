<?php

namespace Deployer;

task('tools:destroy:remove_project_dir', function () {
    ServerFs::removeDir('{{deploy_path}}');
});

task('tools:destroy:confirm', function () {
    View::head('Domains');
    $domains = get('domains');
    foreach ($domains as $item) {
        View::listItem($item['domain']);
    }
    if (!askConfirmation('Are you sure you want to remove from server?')) {
        writeln('Ok, quitting.');
        die;
    }
});

task('tools:destroy', [
    'deploy:info',
    'deploy:profile',
    'benchmark:start',
    'tools:destroy:confirm',
    'tools:destroy:remove_project_dir',
    'apache:config:remove_conf',
    'apache:restart',
    'hosts:remove',
    'hosts:list:lamp',
    'notify:finished',
]);

/*task('tools:set_root', function () {
    $output = ServerConsole::run('sudo whoami');
    writeln($output);

    $output = ServerConsole::run('sudo usermod -aG sudo {{host_user}}');
    writeln($output);

    $output = ServerConsole::run('sudo whoami');
    writeln($output);
});*/
