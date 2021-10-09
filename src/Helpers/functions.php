<?php

namespace Deployer;

use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

function requireLibs(string $directory) {
    $directory = rtrim($directory, '/');
    $libs = FileHelper::scanDir($directory);
    foreach ($libs as $lib) {
        require_once $directory . '/' . $lib;
    }
}

function skipTask(string $name) {
    task($name, function () {
        View::warning('skip');
    });
}
