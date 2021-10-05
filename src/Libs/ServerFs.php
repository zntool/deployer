<?php

namespace Deployer;

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
        if (!ServerFs::isFileExists($dest)) {
            upload($source, $dest);
            return true;
            //writeln("File \"$dest\" already exist");
        } else {
            return false;
            //writeln("File \"$dest\" already exist");
        }
    }
}
