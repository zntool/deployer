<?php

namespace Deployer;

use ZnCore\Base\Helpers\TemplateHelper;

class ServerApache {

    public static function restart() {
        ServerConsole::runSudo('systemctl restart apache2');
    }

    public static function start() {
        ServerConsole::runSudo('systemctl start apache2');
    }

    public static function status() {
        return ServerConsole::runSudo('systemctl status apache2');
    }

    public static function removeConf(string $domain) {
        $file = $domain . '.conf';
        ServerFs::removeFile('/etc/apache2/sites-available/' . $file);
    }
    
    public static function addConf(string $domain, string $directory) {
        $template = '<VirtualHost *:80>
    ServerName {{domain}}
    DocumentRoot {{directory}}
</VirtualHost>';
        $code = TemplateHelper::render($template, [
            'domain' => $domain,
            'directory' => $directory,
        ], '{{', '}}');
        $file = $domain . '.conf';
        ServerFs::uploadContentIfNotExist($code, '/etc/apache2/sites-available/' . $file);
    }
    
    /*public static function addHost(string $domain, string $ip = '127.0.0.1') {
        $content = ServerFs::downloadContent('/etc/hosts');
        if(strpos($content, $domain) === false) {
            $content .= PHP_EOL . $ip . ' ' . $domain;
        }
        ServerFs::uploadContent($content, '~/tmp/hosts');
        ServerConsole::runSudo('mv -f ~/tmp/hosts /etc/hosts');
    }

    public static function removeHost(string $domain, string $ip = '127.0.0.1') {
        $content = ServerFs::downloadContent('/etc/hosts');
        if(strpos($content, $domain) !== false) {
            $content = preg_replace("#".preg_quote($ip)."\s+".preg_quote($domain)."#i", '', $content);
        }
        ServerFs::uploadContent($content, '~/tmp/hosts');
        ServerConsole::runSudo('mv -f ~/tmp/hosts /etc/hosts');
    }*/
}
