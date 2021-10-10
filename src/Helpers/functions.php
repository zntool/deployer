<?php

namespace Deployer;

use ZnCore\Base\Helpers\PhpHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

function skipTask(string $name) {
    task($name, function () {
        View::warning('skip');
    });
}
