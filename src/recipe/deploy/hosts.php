<?php

namespace Deployer;

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
