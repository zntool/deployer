<?php

namespace Deployer;

// if deploy to production, then ask to be sure
task( 'confirm', function () {
    if ( ! askConfirmation( 'Are you sure you want to deploy to production?') ) {
        write( 'Ok, quitting.');
        die;
    }
} )->onStage( 'production');

// finally, notify user that we're done and compute total time
task( 'notify:done', function () {
    $seconds = App::getTotalTime();
    $minutes = substr( '0' . intval( $seconds / 60 ), - 2 );
    $seconds %= 60;
    $seconds = substr( '0' . $seconds, - 2 );

    // show (and speak) notification on desktop so we know it's done!
    // note that next 2 commands are mac-specific
//    shell_exec( "osascript -e 'display notification \"Total time: $minutes:$seconds\" with title \"Deploy Finished\"'" );
//    shell_exec( 'say --rate 200 deployment finished');
    writeln("Finished! Total time: $minutes:$seconds");
} );
