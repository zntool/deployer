<?php

namespace Deployer;

// create new release folder on server
task('release:create', function () {
    $i = 0;
    do {
        $releasePath = '{{deploy_path}}/releases/' . date('Y-m-d_H-i-s_') . $i++;
    } while (ServerFs::isDirectoryExists($releasePath));
    ServerFs::makeDirectory($releasePath);
//    ServerConsole::runSudo("mkdir $releasePath", ['tty' => true] );
    set('release_path', $releasePath);
    Console::writelnResult("Release path: $releasePath");
});

task('release:update_symlinks:current', function () {
    ServerConsole::runSudo('ln -nfs {{release_path}} {{current_path}}');
});

// change the symlinks that the webserver uses, to actually "launch" this release
task('release:update_symlinks:var', function () {
    if(ServerFs::isDirectoryExists('{{deploy_var_path}}')) {
        ServerFs::removeDir('{{release_var_path}}');
    } else {
        ServerConsole::runSudo('mv {{release_var_path}} {{deploy_var_path}}');
    }
    ServerConsole::runSudo('ln -nfs {{deploy_var_path}} {{release_var_path}}');
    ServerFs::chmodRecurse('{{deploy_var_path}}');
});

task('release:update_symlinks:env_local', function () {
    if(ServerFs::isFileExists('{{deploy_path}}/.env.local')) {
        ServerFs::removeFile('{{release_path}}/.env.local');
    } else {
        ServerConsole::runSudo('mv {{release_path}}/.env.local {{deploy_path}}/.env.local');
    }
    ServerConsole::runSudo('ln -nfs {{deploy_path}}/.env.local {{release_path}}/.env.local');
    //ServerFs::makeDirectory('{{deploy_path}}/.env.local');
    //ServerFs::chmod('{{deploy_path}}/.env.local');
});

// change the symlinks that the webserver uses, to actually "launch" this release
task('release:update_symlinks:public', function () {
    // for each of the links below, first we we check for (and remove) any existing symlink
    // then put the new link in place
    // -e means if file exists, -h is if it is a symlink

//    ServerConsole::runSudo('cd {{deploy_path}} && if [ -e {{public_directory}} ]; then rm {{public_directory}}; fi');
//    ServerConsole::runSudo('cd {{deploy_path}} && if [ -h {{public_directory}} ]; then rm {{public_directory}}; fi');
    ServerConsole::runSudo('ln -nfs {{release_public_path}} {{deploy_public_path}}');

    /*ServerConsole::runSudo('cd {{deploy_path}} && if [ -e messages ]; then rm messages; fi');
    ServerConsole::runSudo('cd {{deploy_path}} && if [ -h messages ]; then rm messages; fi');
    ServerConsole::runSudo('ln -nfs {{release_path}}/messages {{deploy_path}}/messages');

    ServerConsole::runSudo('cd {{deploy_path}} && if [ -e vendor ]; then rm vendor; fi');
    ServerConsole::runSudo('cd {{deploy_path}} && if [ -h vendor ]; then rm vendor; fi');
    ServerConsole::runSudo('ln -nfs {{release_path}}/vendor {{deploy_path}}/vendor');*/
});

// get a list of all the releases as an array
set('releases_list', function () {
    return explode("\n", run('ls -dt {{deploy_path}}/releases/*'));
});

task('release:configure_domain', [
    'apache:config:add_conf',
    'hosts:add',
    'apache:restart',
]);

// erase any extra old releases
task('release:cleanup', function () {
    $releases = get('releases_list');
    $keep = get('keep_releases');     // how many to keep?

    // first, forget about the releases we want to keep...
    while ($keep-- > 0) {
        array_shift($releases);
    }

    // ...and delete the remaining (old) releases
    foreach ($releases as $release) {
        ServerFs::removeDir($release);
    }
});

// roll back to previous release
task('rollback', function () {
    $releases = get('releases_list');
    if (isset($releases[1])) {
        // if we are using laravel artisan, take down site
        // writeln(sprintf('  <error>%s</error>', run('php {{deploy_path}}/live/artisan down')));
        $releaseDir = $releases[1];
        ServerConsole::runSudo("ln -nfs $releaseDir {{deploy_path}}/live");
//        ServerConsole::runSudo("rm -rf {$releases[0]}");
        ServerFs::removeDir($releases[0]);
        writeln("Rollback to `{$releases[1]}` release was successful.");
        // if we are using laravel artisan, bring site back up
        // writeln(sprintf('  <error>%s</error>', run("php {{deploy_path}}/live/artisan up")));
    } else {
        writeln('  <comment>No more releases you can revert to.</comment>');
    }
});
