<?php

namespace Deployer;

use ZnCore\Base\Helpers\TemplateHelper;

class ServerHosts {

    public static function add(string $domain, string $ip = '127.0.0.1') {
        $content = ServerFs::downloadContent('/etc/hosts');
        if(strpos($content, $domain) === false) {
            $content .= PHP_EOL . $ip . ' ' . $domain;
        }
        ServerFs::uploadContent($content, '~/tmp/hosts');
        ServerConsole::runSudo('mv -f ~/tmp/hosts /etc/hosts');
    }

    public static function remove(string $domain, string $ip = '127.0.0.1') {
        $content = ServerFs::downloadContent('/etc/hosts');
        if(strpos($content, $domain) !== false) {
            $content = preg_replace("#".preg_quote($ip)."\s+".preg_quote($domain)."#i", '', $content);
        }
        ServerFs::uploadContent($content, '~/tmp/hosts');
        ServerConsole::runSudo('mv -f ~/tmp/hosts /etc/hosts');
    }
}
