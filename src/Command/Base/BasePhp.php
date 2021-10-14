<?php

namespace ZnTool\Deployer\Command\Base;

use Deployer\PhpConfig;
use Deployer\ServerFs;

abstract class BasePhp extends Base
{

    public static function setConfig(string $configFile, array $config) {
        $content = static::fsClass()::downloadContent($configFile);
        $phpConfig = new PhpConfig($content);
        foreach ($config as $key => $value) {
            $phpConfig->enable($key);
            $phpConfig->set($key, $value);
        }
        static::fsClass()::uploadContent($content, $configFile);
    }
}
