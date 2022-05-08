<?php

namespace ZnTool\Deployer\Command\Base;

use Deployer\ServerConsole;
use Deployer\ServerFs;
use ZnCore\Base\Helpers\TempHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnCore\Base\Libs\FileSystem\Helpers\FileStorageHelper;
use function Deployer\download;
use function Deployer\upload;

abstract class BaseFs extends Base
{

    const A_W = 'a+w';
    const UGO_RWX = 'ugo+rwx';

    public static function wget(string $url, string $filePath, string $hash = null, string $algo = 'sha384')
    {
//        ServerConsole::cd('~');
        ServerFs::removeFile($filePath);
//    ServerConsole::run("{{bin/php}} -r \"copy('$url', '$destFile');\"");
        ServerConsole::run("wget $url -o $filePath");
       // dd(ServerFs::list('~'));
        if(!empty($hash)) {
            
            ServerFs::checkFileHash($filePath, $hash, $algo);
        }
    }

    public static function list(string $path)
    {
        $output = ServerConsole::run("ls -l");
        return $output;
    }

    public static function checkFileHash(string $filePath, string $hash, string $algo = 'sha384')
    {
        //dd($filePath);
        $output = static::run("{{bin/php}} -r \"if (hash_file('$algo', '$filePath') === '$hash') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('$filePath'); }\"");
        //dd($output);
        self::isValidFileHash($filePath, $hash, $algo);
        if ($output != 'Installer verified') {
            throw new \Exception('File hash not verified!');
        }
    }

    public static function isValidFileHash(string $filePath, string $hash, string $algo = 'sha384'): bool
    {
        $output = static::run("{{bin/php}} -r \"if (hash_file('sha384', '$filePath') === '$hash') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('$filePath'); } echo PHP_EOL;\"");
        return $output != 'Installer verified';
    }

    public static function makeLink(string $filePath, string $linkPath, string $options = '-nfs'): bool
    {
        return static::run("sudo ln $options $filePath $linkPath");
    }

    public static function move(string $from, string $to, string $options = ''): bool
    {
        return static::run("sudo mv $options $from $to");
    }

    public static function isFileExists(string $file): bool
    {
        return static::test("[ -f $file ]");
    }

    public static function chmodRecurse(string $path, string $options)
    {
        static::chmod($path, $options, true);
//        static::run("sudo chmod -R $options $path");
    }

    public static function chmod(string $path, string $options, bool $isRecursive = false)
    {
        $recursive = $isRecursive ? '-R' : '';
        static::run("sudo chmod $recursive $options $path");
    }

    public static function chown(string $path, string $owner)
    {
        static::run("sudo chown $owner $path");
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

    public static function modifyFileWithCallback(string $file, callable $callback): void
    {
        $content = ServerFs::downloadContent($file);
        $content = call_user_func_array($callback, [$content]);
        ServerFs::uploadContent($content, $file);
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
        FileStorageHelper::save($fileName, $content);
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
        return FileStorageHelper::load($fileName);
    }
}
