<?php

namespace Deployer;

// if deploy to production, then ask to be sure
use Symfony\Component\Console\Output\ConsoleOutput;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

task('confirm', function () {
    if (!askConfirmation('Are you sure you want to deploy to production?')) {
        write('Ok, quitting.');
        die;
    }
})->onStage('production');

// finally, notify user that we're done and compute total time
task('notify:finished', function () {
    $total = BenchmarkWidget::total();
    if($total) {
        writeln("Total time: $total");
    }
    View::success("Finished!");
});
