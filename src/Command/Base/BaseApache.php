<?php

namespace ZnTool\Deployer\Command\Base;

use Deployer\ServerApache;
use ZnCore\Base\Helpers\TemplateHelper;
use ZnTool\Deployer\Entities\ApacheStatusEntity;
use function Deployer\get;

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
    
    public static function status(): ApacheStatusEntity
    {
        $output = static::run('sudo systemctl status apache2');
        
        $statusEntity = new ApacheStatusEntity();
        
        $isActive = preg_match('/(Active:\s+active\s+\(running\))/i', $output, $matches);
        $statusEntity->setStatus($isActive ? 'active' : '');

        preg_match('/Active:.+;\s*(.+)\s+ago/i', $output, $matches);
        $statusEntity->setAgo($matches[1]);

        preg_match('/Process:\s*(\d+)/i', $output, $matches);
        $statusEntity->setProcessId($matches[1]);

        preg_match('/Main\sPID:\s*(\d+)/i', $output, $matches);
        $statusEntity->setMainPid($matches[1]);

        preg_match('/Tasks:\s*(\d+)/i', $output, $matches);
        $statusEntity->setTaksCount($matches[1]);

        preg_match('/Memory:\s*(\d+)/i', $output, $matches);
        $statusEntity->setMemory($matches[1]);
        
        return $statusEntity;
    }

    public static function removeConf(string $domain)
    {
        $file = $domain . '.conf';
        static::fsClass()::removeFile('/etc/apache2/sites-available/' . $file);
    }

    public static function addConf(string $domain, string $directory)
    {
        static::removeConf($domain);

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
