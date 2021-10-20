<?php

namespace Deployer;

task('php-documentor:install', function () {

    $pharFileName = 'phpDocumentor.phar';
    $binFilePath = '/usr/bin/phpDocumentor';

    if(ServerFs::isFileExists($binFilePath)) {
        View::warning('Composer already installed');
        return;
    }

    $url = 'https://phpdoc.org/phpDocumentor.phar';
//    $hash = '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8';
    $destFileName = 'composer-setup.php';
//    $destDirectory = get('deploy_path');
//    $destFilePath = /*$destDirectory . '/' .*/ $destFileName;

    ServerConsole::cd('~');
//    ServerFs::removeFile($pharFileName);

    ////ServerConsole::run("{{bin/php}} -r \"unlink('$pharFileName');\"");
    ServerConsole::run("wget $url");
    ////ServerConsole::run("{{bin/php}} -r \"copy('$url', '$destFileName');\"");
    //ServerFs::checkFileHash($destFilePath, $hash);

//    ServerConsole::run('{{bin/php}} composer-setup.php');
//    //ServerConsole::run('{{bin/php}} -r "unlink(\'composer-setup.php\');"');

//    ServerFs::removeFile("~/composer-setup.php");


    ServerConsole::run("sudo mv ~/$pharFileName $binFilePath");
    ServerFs::chmod($binFilePath, '+x');
    writeln(ServerConsole::run('phpDocumentor --version'));
});

task('php-documentor:remove', function () {

    $pharFileName = 'phpDocumentor.phar';
    $binFilePath = '/usr/bin/phpDocumentor';
    $url = 'https://phpdoc.org/phpDocumentor.phar';

    ServerFs::removeFile($binFilePath);
});
