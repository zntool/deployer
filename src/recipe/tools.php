<?php

namespace Deployer;

task('tools:destroy:remove_project_dir', function () {
    ServerFs::removeDir('{{deploy_path}}');
});

task('tools:destroy:remove_apache_conf', function () {
    $file = get('domain') . '.conf';
    ServerFs::removeFile('/etc/apache2/sites-available/' . $file);
});

task('tools:destroy:remove_from_hosts', function () {
    ServerApache::removeHost(get('domain'));
});

task('tools:destroy', [
    'tools:destroy:remove_project_dir',
    'tools:destroy:remove_apache_conf',
    'tools:destroy:remove_from_hosts',
]);

task('tools:set_root', function () {
    $output = ServerConsole::runSudo('whoami');
    writeln($output);

    $output = ServerConsole::runSudo('usermod -aG sudo {{host_user}}');
    writeln($output);

    $output = ServerConsole::runSudo('whoami');
    writeln($output);
});
