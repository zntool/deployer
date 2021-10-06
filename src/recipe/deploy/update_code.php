<?php

namespace Deployer;

// check out code from main repo and put into release folder
task( 'code:update', function () {
    ServerConsole::run("{{bin/git}} clone -b {{branch}} -q --depth 1 {{repository}} {{release_path}}" );

    // remove a few assorted things that are in the repo but should not be on the server
    /*cd( "{{release_path}}" );
    ServerConsole::runSudo("rm -rf server-extensions" );
    ServerConsole::runSudo("rm -rf tests" );
    ServerConsole::runSudo("rm -rf README.md" );
    ServerConsole::runSudo("rm -rf codecept" );
    ServerConsole::runSudo("rm -rf codeception.yml" );*/
} );
