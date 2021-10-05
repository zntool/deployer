<?php

namespace Deployer;

// create new release folder on server
task( 'create:release', function () {
    $i = 0;
    do {
        $releasePath = '{{deploy_path}}/releases/' . date( 'Y-m-d_Hi_' ) . $i ++;
    } while ( run( "if [ -d $releasePath ]; then echo exists; fi;" ) == 'exists' );
    ServerFs::makeDirectory($releasePath);
//    run( "{{sudo_cmd}} mkdir $releasePath", ['tty' => true] );
    set( 'release_path', $releasePath );
    writeln( "Release path: $releasePath" );
} );

// check out code from main repo and put into release folder
task( 'update:code-main', function () {
    run( "{{sudo_cmd}} git clone -b {{branch}} -q --depth 1 {{repo-main}} {{release_path}}" );

    // remove a few assorted things that are in the repo but should not be on the server
    /*cd( "{{release_path}}" );
    run( "{{sudo_cmd}} rm -rf server-extensions" );
    run( "{{sudo_cmd}} rm -rf tests" );
    run( "{{sudo_cmd}} rm -rf README.md" );
    run( "{{sudo_cmd}} rm -rf codecept" );
    run( "{{sudo_cmd}} rm -rf codeception.yml" );*/
} );

// update external libraries (npm, composer, etc)
task( 'update:vendors', function () {
    writeln( '<info>  Updating composer</info>' );
    cd( '{{release_path}}' );
    run( 'composer install --no-dev' );
//    cd( '{{release_path}}/html/final' );
//    run( 'composer install --no-dev' );

    /*writeln( '<info>  Updating npm</info>' );
    cd( '{{release_path}}/html' );
    run( 'npm update' );
    cd( '{{release_path}}/html/final' );
    run( 'npm update' );*/
} );

// change the symlinks that the webserver uses, to actually "launch" this release
task( 'update:release_symlinks', function () {
    // for each of the links below, first we we check for (and remove) any existing symlink
    // then put the new link in place
    // -e means if file exists, -h is if it is a symlink

//    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -e {{public_directory}} ]; then {{sudo_cmd}} rm {{public_directory}}; fi' );
//    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -h {{public_directory}} ]; then {{sudo_cmd}} rm {{public_directory}}; fi' );
    run( '{{sudo_cmd}} ln -nfs {{release_path}}/{{public_directory}} {{deploy_path}}/{{public_directory}}' );

    /*run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -e messages ]; then {{sudo_cmd}} rm messages; fi' );
    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -h messages ]; then {{sudo_cmd}} rm messages; fi' );
    run( '{{sudo_cmd}} ln -nfs {{release_path}}/messages {{deploy_path}}/messages' );

    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -e vendor ]; then {{sudo_cmd}} rm vendor; fi' );
    run( '{{sudo_cmd}} cd {{deploy_path}} && if [ -h vendor ]; then {{sudo_cmd}} rm vendor; fi' );
    run( '{{sudo_cmd}} ln -nfs {{release_path}}/vendor {{deploy_path}}/vendor' );*/
} );

// get a list of all the releases as an array
set('releases_list', function () {
    return explode("\n", run('ls -dt {{deploy_path}}/releases/*'));
});

// erase any extra old releases
task( 'cleanup', function () {
    $releases = get( 'releases_list' );
    $keep     = get( 'keep_releases' );     // how many to keep?

    // first, forget about the releases we want to keep...
    while ( $keep-- > 0 ) {
        array_shift( $releases );
    }

    // ...and delete the remaining (old) releases
    foreach ( $releases as $release ) {
        run( "{{sudo_cmd}} rm -rf $release" );
    }
} );
