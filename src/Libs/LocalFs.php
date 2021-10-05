<?php

namespace Deployer;

class LocalFs {

    public static function isFileExists(string $file): bool
    {
        return testLocally("[ -f $file ]");
    }
}
