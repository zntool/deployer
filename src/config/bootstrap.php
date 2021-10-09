<?php

namespace Deployer;

use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

require_once __DIR__ . '/../Helpers/functions.php';
requireLibs(__DIR__ . '/../Libs');

App::initVarsFromArray([
    'show_detail' => 0,
    'sudo_cmd' => 'sudo -S {command} < ~/sudo-pass',
    'allow_anonymous_stats' => 1,
    'git_tty' => 1,
    'default_stage' => 'staging',
]);
