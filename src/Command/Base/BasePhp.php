<?php

namespace ZnTool\Deployer\Command\Base;

use Deployer\PhpConfig;
use Deployer\ServerFs;

abstract class BasePhp extends Base
{

    public static function setConfig(string $configFile, array $config) {
        $content = static::fsClass()::downloadContent('/etc/php/7.2/apache2/php.ini');
        $phpConfig = new PhpConfig($content);
        foreach ($config as $key => $value) {
            $phpConfig->enable($key);
            $phpConfig->set($key, $value);
        }
        static::fsClass()::uploadContent($content, '/etc/php/7.2/apache2/php.ini');
    }
}
