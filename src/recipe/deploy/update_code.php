<?php

namespace Deployer;

// check out code from main repo and put into release folder
task( 'code:update', function () {
    Git::clone('{{repository}}', '{{branch}}', '{{release_path}}');
//    ServerConsole::run("{{bin/git}} clone -b {{branch}} -q --depth 1 {{repository}} {{release_path}}" );

    // remove a few assorted things that are in the repo but should not be on the server
    /*cd( "{{release_path}}" );
    ServerConsole::run("sudo rm -rf server-extensions" );
    ServerConsole::run("sudo rm -rf tests" );
    ServerConsole::run("sudo rm -rf README.md" );
    ServerConsole::run("sudo rm -rf codecept" );
    ServerConsole::run("sudo rm -rf codeception.yml" );*/
} );
