<?php

namespace ZnTool\Deployer\Helpers;

use ZnCore\Base\Helpers\DeprecateHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

DeprecateHelper::hardThrow();

class LoaderHelper
{

    public static function loadTasks(string $taskDir): void {
        $fileList = FileHelper::scanDir($taskDir);
        foreach ($fileList as $fileName) {
            self::loadTask($taskDir . '/' . $fileName);
        }
    }

    public static function loadTask(string $fileName): void {
        if(FileHelper::fileExt($fileName) == 'php') {
            require_once $fileName;
        }
    }
}
