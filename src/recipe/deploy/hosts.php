<?php

namespace Deployer;

task('hosts:add', function () {
    ServerHosts::add(get('domain'));
    /*$content = ServerFs::downloadContent('/etc/hosts');
    if(strpos($content, get('domain')) === false) {
        $content .= PHP_EOL . '127.0.0.1 ' . get('domain');
    }
//    ServerConsole::run('sudo su - {{host_user}}');
//    ServerFs::makeDirectory('~/tmp');
    ServerFs::uploadContent($content, '~/tmp/hosts');
    ServerConsole::run('sudo mv -f ~/tmp/hosts /etc/hosts');
//    ServerFs::uploadContent($content, '/etc/hosts');*/
});

task('hosts:remove', function () {
    ServerHosts::remove(get('domain'));
});
