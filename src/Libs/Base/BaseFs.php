<?php

namespace ZnTool\Deployer\Libs\Base;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use function Deployer\download;
use function Deployer\upload;

abstract class BaseFs extends Base
{

    public static function isFileExists(string $file): bool
    {
        return static::test("[ -f $file ]");
    }

    public static function chmodRecurse(string $path)
    {
        static::run("sudo chmod -R a+w $path");
    }

    public static function chmod(string $path)
    {
        static::run("sudo chmod a+w $path");
    }

    public static function removeFile(string $path)
    {
        if (!static::isFileExists($path)) {
            return false;
        }
        static::run("sudo rm $path");
    }

    public static function removeDir(string $path)
    {
        if (!static::isDirectoryExists($path)) {
            return false;
        }
        static::run("sudo rm -rf $path");
    }

    public static function makeDirectory(string $directory)
    {
        static::run("mkdir -p $directory");
    }

    public static function touch(string $file): bool
    {
        static::run("touch $file");
    }

    public static function isDirectoryExists(string $file): bool
    {
        return static::test("[ -d $file ]");
    }

    public static function uploadIfNotExist(string $source, string $dest): bool
    {
        if (static::isFileExists($dest)) {
            return false;
        }
        static::uploadFile($source, $dest);
        return true;
    }

    public static function uploadContentIfNotExist(string $content, string $dest): bool
    {
        if (static::isFileExists($dest)) {
            return false;
        }
        static::uploadContent($content, $dest);
        return true;
    }

    public static function uploadContent(string $content, string $destination)
    {
        $dir = TempHelper::getTmpDirectory('deployer_upload');
        $file = basename($destination);
        $fileName = $dir . '/' . $file;
        FileHelper::save($fileName, $content);
//        static::makeDirectory(dirname($destination));
//        upload($fileName, $destination);
        self::uploadFile($fileName, $destination);
    }

    public static function uploadFile($source, $destination, array $config = [])
    {
        static::makeDirectory(dirname($destination));
        upload($source, $destination, $config);
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
