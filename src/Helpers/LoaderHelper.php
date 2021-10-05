<?php

namespace ZnTool\Deployer\Helpers;

use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

class LoaderHelper
{

    public static function loadTasks(string $taskDir): void {
        $fileList = FileHelper::scanDir($taskDir);
        foreach ($fileList as $fileName) {
            if(FileHelper::fileExt($fileName) == 'php') {
                require_once $taskDir . '/' . $fileName;
            }
        }
    }
}
