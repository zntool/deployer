<?php

namespace Deployer;

set('composerBinPath','/usr/bin/composer');

task('composer:install:base', function () {

    $pharFilePath = '~/composer.phar';
    $binFilePath = '{{composerBinPath}}';
    $url = 'https://getcomposer.org/installer';
    $installerHash = '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8';
    $installerFile = 'composer-setup.php';

    if(ServerFs::isFileExists($binFilePath)) {
        View::warning('Composer already installed');
        return;
    }

    ServerFs::wget($url, "$installerFile"/*, $installerHash, 'sha384'*/);

    ServerConsole::run("{{bin/php}} ~/$installerFile");
    ServerFs::removeFile("~/$installerFile");

    ServerConsole::run("sudo mv $pharFilePath $binFilePath");
    ServerFs::chmod($binFilePath, '+x');
    writeln(ServerConsole::run('{{bin/composer}} --version'));
});

task('composer:remove', function () {
    ServerFs::removeFile('{{composerBinPath}}');
});
