<?php

namespace Deployer;

task('tools:destroy', function () {
    $output = run('rm -rf {{deploy_path}}');
    writeln($output);
});
