<?php

namespace Deployer;

use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;

class ServerFs {

    public static function chmodRecurse(string $path)
    {
        ServerConsole::runSudo("chmod -R a+w $path");
    }

    public static function chmod(string $path)
    {
        ServerConsole::runSudo("chmod a+w $path");
    }

    public static function removeFile(string $path)
    {
        if (!ServerFs::isFileExists($path)) {
            return false;
        }
        ServerConsole::runSudo("rm $path");
    }

    public static function removeDir(string $path)
    {
        if (!ServerFs::isDirectoryExists($path)) {
            return false;
        }
        ServerConsole::runSudo("rm -rf $path");
    }
    
    public static function makeDirectory(string $directory)
    {
        run("mkdir -p $directory");
    }

    public static function touch(string $file): bool
    {
        run("touch $file");
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
        self::uploadFile($source, $dest);
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

    public static function uploadContent(string $content, string $destination)
    {
        $dir = TempHelper::getTmpDirectory('deployer_upload');
        $file = basename($destination);
        $fileName = $dir . '/' . $file;
        FileHelper::save($fileName, $content);
//        ServerFs::makeDirectory(dirname($destination));
//        upload($fileName, $destination);
        self::uploadFile($fileName, $destination);
    }

    public static function uploadFile($source, $destination, array $config = [])
    {
        ServerFs::makeDirectory(dirname($destination));
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
