<?php

namespace Deployer;

// if deploy to production, then ask to be sure
task('confirm', function () {
    if (!askConfirmation('Are you sure you want to deploy to production?')) {
        write('Ok, quitting.');
        die;
    }
})->onStage('production');

// finally, notify user that we're done and compute total time
task('notify:finished', function () {
    Console::writelnSuccess("Finished!");
    $total = BenchmarkWidget::total();
    if($total) {
        writeln("Total time: $total");
    }
});
