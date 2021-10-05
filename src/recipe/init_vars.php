<?php

namespace Deployer;

set('repository', $_ENV['DEPLOYER_REPOSITORY']);
set('deploy_path', $_ENV['DEPLOYER_DIRECTORY']);
set('release_path', $_ENV['DEPLOYER_DIRECTORY']);
set('branch', $_ENV['DEPLOYER_BRANCH']);
//set('hostname', $_ENV['DEPLOYER_HOST_IP']);
//set('user', $_ENV['DEPLOYER_HOST_USER']);
