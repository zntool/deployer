<?php

namespace Deployer;

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

    public static function addHost(string $dimain, string $ip = '127.0.0.1') {
        $content = ServerFs::downloadContent('/etc/hosts');
        if(strpos($content, $dimain) === false) {
            $content .= PHP_EOL . $ip . ' ' . $dimain;
        }
        ServerFs::uploadContent($content, '~/tmp/hosts');
        ServerConsole::runSudo('mv -f ~/tmp/hosts /etc/hosts');
    }

    public static function removeHost(string $domain, string $ip = '127.0.0.1') {
        $content = ServerFs::downloadContent('/etc/hosts');
        if(strpos($content, $domain) !== false) {
            //dd("#".preg_quote($ip)."\s+".preg_quote($domain)."#i");
            $content = preg_replace("#".preg_quote($ip)."\s+".preg_quote($domain)."#i", '', $content);
        }
        //dd($content);
        ServerFs::uploadContent($content, '~/tmp/hosts');
        ServerConsole::runSudo('mv -f ~/tmp/hosts /etc/hosts');
    }
}
