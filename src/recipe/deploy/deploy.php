<?php

namespace Deployer;

use Deployer\Exception\ConfigurationException;
use Deployer\Exception\GracefulShutdownException;

task('deploy:lock', function () {
    ServerFs::makeDirectory('{{deploy_path}}/.dep');
    $locked = ServerConsole::test("[ -f {{deploy_path}}/.dep/deploy.lock ]");

    if ($locked) {
        $stage = input()->hasArgument('stage') ? ' ' . input()->getArgument('stage') : '';

        throw new GracefulShutdownException(
            "Deploy locked.\n" .
            sprintf('Execute "' . Deployer::getCalledScript() . ' deploy:unlock%s" to unlock.', $stage)
        );
    } else {
        ServerConsole::run("touch {{deploy_path}}/.dep/deploy.lock");
    }
});

task('deploy:profile', function () {
    try {
        $profiles = get('profiles');
    } catch (ConfigurationException $e) {
        View::info("Empty profile config");
        return;
    }
    if(count($profiles) == 1) {
        $profileName = key($profiles);
    } else {
        $profileNames = array_keys($profiles);
        $profileName = askChoice('Selectt profile', $profileNames);
    }
    View::info("Selected profile \"$profileName\"");
    $profileConfig = $profiles[$profileName];
    App::initVarsFromArray($profileConfig);
});
