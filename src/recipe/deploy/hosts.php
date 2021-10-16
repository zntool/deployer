<?php

namespace Deployer;

task('hosts:list:all', function () {
    $groups = ServerHosts::loadConfig();
    foreach ($groups as $groupName => $hostList) {
        View::head($groupName);
        foreach ($hostList as $domain => $ip) {
            View::listItem($domain);
        }
    }
});

task('hosts:list:lamp', function () {
    $groups = ServerHosts::loadConfig();
    foreach ($groups['lamp'] as $domain => $ip) {
        View::listItem('http://' . $domain . ':8080/');
    }
});

task('hosts:add', function () {
    $domains = get('domains');
    foreach ($domains as $item) {
        ServerHosts::add($item['domain']);
    }
});

task('hosts:remove', function () {
    $domains = get('domains');
    foreach ($domains as $item) {
        ServerHosts::remove($item['domain']);
    }
});

/*task('hosts:add', function () {
    ServerHosts::add(get('domain'));
});

task('hosts:remove', function () {
    ServerHosts::remove(get('domain'));
});*/
