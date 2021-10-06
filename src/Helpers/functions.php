<?php

namespace Deployer;

use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

function requireLibs(string $directory) {
    $libs = FileHelper::scanDir(__DIR__ . '/../Libs');
    foreach ($libs as $lib) {
        require_once __DIR__ . '/../Libs/' . $lib;
    }
}
