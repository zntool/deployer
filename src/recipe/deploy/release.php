<?php

namespace Deployer;

// create new release folder on server
task( 'release:create', function () {
    $i = 0;
    do {
        $releasePath = '{{deploy_path}}/releases/' . date( 'Y-m-d_H-i-s_') . $i ++;
    } while (ServerFs::isDirectoryExists($releasePath));
    ServerFs::makeDirectory($releasePath);
//    run( "{{sudo_cmd}} mkdir $releasePath", ['tty' => true] );
    set( 'release_path', $releasePath );
    writeln( "Release path: $releasePath" );
} );

// change the symlinks that the webserver uses, to actually "launch" this release
task( 'release:update_symlinks', function () {
    // for each of the links below, first we we check for (and remove) any existing symlink
    // then put the new link in place
    // -e means if file exists, -h is if it is a symlink

//    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -e {{public_directory}} ]; then {{sudo_cmd}} rm {{public_directory}}; fi');
//    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -h {{public_directory}} ]; then {{sudo_cmd}} rm {{public_directory}}; fi');
    run( '{{sudo_cmd}} ln -nfs {{release_path}}/{{public_directory}} {{deploy_path}}/{{public_directory}}');

    /*run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -e messages ]; then {{sudo_cmd}} rm messages; fi');
    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -h messages ]; then {{sudo_cmd}} rm messages; fi');
    run( '{{sudo_cmd}} ln -nfs {{release_path}}/messages {{deploy_path}}/messages');

    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -e vendor ]; then {{sudo_cmd}} rm vendor; fi');
    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -h vendor ]; then {{sudo_cmd}} rm vendor; fi');
    run( '{{sudo_cmd}} ln -nfs {{release_path}}/vendor {{deploy_path}}/vendor');*/
} );

// get a list of all the releases as an array
set('releases_list', function () {
    return explode("\n", run('ls -dt {{deploy_path}}/releases/*'));
});

// erase any extra old releases
task( 'release:cleanup', function () {
    $releases = get( 'releases_list');
    $keep     = get( 'keep_releases');     // how many to keep?

    // first, forget about the releases we want to keep...
    while ( $keep-- > 0 ) {
        array_shift( $releases );
    }

    // ...and delete the remaining (old) releases
    foreach ( $releases as $release ) {
        run( "{{sudo_cmd}} rm -rf $release" );
    }
} );

// roll back to previous release
task( 'rollback', function () {
    $releases = get( 'releases_list');
    if ( isset( $releases[1] ) ) {
        // if we are using laravel artisan, take down site
        // writeln(sprintf('  <error>%s</error>', run('php {{deploy_path}}/live/artisan down')));
        $releaseDir = $releases[1];
        run( "{{sudo_cmd}} ln -nfs $releaseDir {{deploy_path}}/live" );
        run( "{{sudo_cmd}} rm -rf {$releases[0]}" );
        writeln( "Rollback to `{$releases[1]}` release was successful." );
        // if we are using laravel artisan, bring site back up
        // writeln(sprintf('  <error>%s</error>', run("php {{deploy_path}}/live/artisan up")));
    } else {
        writeln( '  <comment>No more releases you can revert to.</comment>');
    }
} );
