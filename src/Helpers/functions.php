<?php

namespace Deployer;

use ZnCore\Base\Helpers\PhpHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

/*function requireLibs(string $directory) {
    PhpHelper::requireFromDirectory($directory);
//    $directory = rtrim($directory, '/');
//    $libs = FileHelper::scanDir($directory);
//    foreach ($libs as $lib) {
//        $path = $directory . '/' . $lib;
//        if(is_file($path) && FileHelper::fileExt($lib) == 'php') {
//            require_once $path;
//        }
//    }
}*/

function skipTask(string $name) {
    task($name, function () {
        View::warning('skip');
    });
}
