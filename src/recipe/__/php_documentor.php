<?php

namespace Deployer;

set('phpDocumentorBinPath','/usr/bin/phpDocumentor');

task('php-documentor:install', function () {

    $pharFilePath = '~/phpDocumentor.phar';
    $binFilePath = '{{phpDocumentorBinPath}}';
    $url = 'https://phpdoc.org/phpDocumentor.phar';



    if(ServerFs::isFileExists($binFilePath)) {
        View::warning('PHP documentor already installed');
//        return;
    }

    ServerFs::wget($url, $pharFilePath);
//    dd(ServerFs::list('~'));



    ServerConsole::run("sudo mv $pharFilePath $binFilePath");
    ServerFs::chmod($binFilePath, '+x');
    writeln(ServerConsole::run('phpDocumentor --version'));
});

task('php-documentor:remove', function () {
    ServerFs::removeFile('{{phpDocumentorBinPath}}');
});
