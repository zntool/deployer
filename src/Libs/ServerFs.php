<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

class ServerFs {

    public static function makeDirectory(string $directory)
    {
        run("mkdir -p $directory");
    }

    public static function isFileExists(string $file): bool
    {
        return test("[ -f $file ]");
    }

    public static function isDirectoryExists(string $file): bool
    {
        return test("[ -d $file ]");
    }

    public static function uploadIfNotExist(string $source, string $dest): bool
    {
        if (ServerFs::isFileExists($dest)) {
            return false;
        }
        upload($source, $dest);
        return true;
    }

    public static function uploadContentIfNotExist(string $content, string $dest): bool
    {
        if (ServerFs::isFileExists($dest)) {
            return false;
        }
        ServerFs::uploadContent($content, $dest);
        return true;
    }

    public static function uploadContent(string $content, string $dest)
    {
        $dir = TempHelper::getTmpDirectory('deployer_upload');
        $file = basename($dest);
        $fileName = $dir . '/' . $file;
        FileHelper::save($fileName, $content);
        ServerFs::makeDirectory(dirname($dest));
        upload($fileName, $dest);
    }

    public static function downloadContent(string $source): string
    {
        $dir = TempHelper::getTmpDirectory('deployer_upload');
        $file = basename($source);
        $fileName = $dir . '/' . $file;
        download($source, $fileName);
        return FileHelper::load($fileName);
    }
}
