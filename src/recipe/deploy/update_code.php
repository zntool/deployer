<?php

namespace Deployer;

// check out code from main repo and put into release folder
task( 'code:update', function () {
    run( "{{sudo_cmd}} {{bin/git}} clone -b {{branch}} -q --depth 1 {{repository}} {{release_path}}" );

    // remove a few assorted things that are in the repo but should not be on the server
    /*cd( "{{release_path}}" );
    run( "{{sudo_cmd}} rm -rf server-extensions" );
    run( "{{sudo_cmd}} rm -rf tests" );
    run( "{{sudo_cmd}} rm -rf README.md" );
    run( "{{sudo_cmd}} rm -rf codecept" );
    run( "{{sudo_cmd}} rm -rf codeception.yml" );*/
} );
