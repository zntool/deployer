<?php

namespace Deployer;

function initHostByUser(string $userName = null) {
    $userName = $userName ?: $_ENV['DEPLOYER_HOST_USER'];
    $host = host($_ENV['DEPLOYER_HOST_IP']);
    $host->user($userName);
    $host->port($_ENV['DEPLOYER_HOST_PORT']);
    /*if(isset($_ENV['DEPLOYER_HOST_IDENTITY_FILE'])) {
        $host->identityFile($_ENV['DEPLOYER_HOST_IDENTITY_FILE']);
    }*/
}

initHostByUser();

/*task('ssh:connect', function () {
    //initHostByUser($_ENV['DEPLOYER_HOST_USER']);
});

task('ssh:connect_by_root', function () {
    //run('su - user');
});*/
