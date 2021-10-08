<?php

namespace Deployer;

use Deployer\Exception\GracefulShutdownException;

task('deploy:lock', function () {
    ServerFs::makeDirectory('{{deploy_path}}/.dep');
    $locked = test("[ -f {{deploy_path}}/.dep/deploy.lock ]");

    if ($locked) {
        $stage = input()->hasArgument('stage') ? ' ' . input()->getArgument('stage') : '';

        throw new GracefulShutdownException(
            "Deploy locked.\n" .
            sprintf('Execute "' . Deployer::getCalledScript() . ' deploy:unlock%s" to unlock.', $stage)
        );
    } else {
        run("touch {{deploy_path}}/.dep/deploy.lock");
    }
});
