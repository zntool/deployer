<?php

namespace Deployer;

use ZnCore\Text\Helpers\TemplateHelper;
use ZnTool\Deployer\Command\Base\BaseApache;

class ServerApache extends BaseApache
{

    protected static function side(): string
    {
        return static::SIDE_SERVER;
    }

    /*public static function addHost(string $domain, string $ip = '127.0.0.1') {
        $content = ServerFs::downloadContent('/etc/hosts');
        if(strpos($content, $domain) === false) {
            $content .= PHP_EOL . $ip . ' ' . $domain;
        }
        ServerFs::uploadContent($content, '~/tmp/hosts');
        ServerConsole::run('sudo mv -f ~/tmp/hosts /etc/hosts');
    }

    public static function removeHost(string $domain, string $ip = '127.0.0.1') {
        $content = ServerFs::downloadContent('/etc/hosts');
        if(strpos($content, $domain) !== false) {
            $content = preg_replace("#".preg_quote($ip)."\s+".preg_quote($domain)."#i", '', $content);
        }
        ServerFs::uploadContent($content, '~/tmp/hosts');
        ServerConsole::run('sudo mv -f ~/tmp/hosts /etc/hosts');
    }*/
}
