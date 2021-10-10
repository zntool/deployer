<?php

namespace ZnTool\Deployer\Command\Base;

abstract class BaseHosts extends Base
{

    public static function add(string $domain, string $ip = '127.0.0.1')
    {
        $content = static::fsClass()::downloadContent('/etc/hosts');
        if (strpos($content, $domain) === false) {
            $content .= PHP_EOL . $ip . ' ' . $domain;
        }
        static::fsClass()::uploadContent($content, '~/tmp/hosts');
        static::run('sudo mv -f ~/tmp/hosts /etc/hosts');
    }

    public static function remove(string $domain, string $ip = '127.0.0.1')
    {
        $content = static::fsClass()::downloadContent('/etc/hosts');
        if (strpos($content, $domain) !== false) {
            $content = preg_replace("#" . preg_quote($ip) . "\s+" . preg_quote($domain) . "#i", '', $content);
        }
        static::fsClass()::uploadContent($content, '~/tmp/hosts');
        static::run('sudo mv -f ~/tmp/hosts /etc/hosts');
    }
}
