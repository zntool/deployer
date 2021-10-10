<?php

namespace ZnTool\Deployer\Libs\Base;

use ZnCore\Base\Helpers\TemplateHelper;

abstract class BaseApache extends Base
{

    public static function restart()
    {
        static::run('sudo systemctl restart apache2');
    }

    public static function start()
    {
        static::run('sudo systemctl start apache2');
    }

    public static function status()
    {
        return static::run('sudo systemctl status apache2');
    }

    public static function removeConf(string $domain)
    {
        $file = $domain . '.conf';
        static::fsClass()::removeFile('/etc/apache2/sites-available/' . $file);
    }

    public static function addConf(string $domain, string $directory)
    {
        $template = '<VirtualHost *:80>
    ServerName {{domain}}
    DocumentRoot {{directory}}
</VirtualHost>';
        $code = TemplateHelper::render($template, [
            'domain' => $domain,
            'directory' => $directory,
        ], '{{', '}}');
        $file = $domain . '.conf';
        //$fs = static::fsClassName();
        static::fsClass()::uploadContentIfNotExist($code, '/etc/apache2/sites-available/' . $file);
    }
}
