<?php

namespace Deployer;

task('composer:install:base', function () {
    if(ServerFs::isFileExists('/usr/bin/composer')) {
        View::warning('Composer already installed');
        return;
    }
    ServerConsole::cd('~');
    ServerConsole::run('{{bin/php}} -r "unlink(\'composer.phar\');"');
    ServerConsole::run('{{bin/php}} -r "copy(\'https://getcomposer.org/installer\', \'composer-setup.php\');"');
    $output = ServerConsole::run('{{bin/php}} -r "if (hash_file(\'sha384\', \'composer-setup.php\') === \'906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8\') { echo \'Installer verified\'; } else { echo \'Installer corrupt\'; unlink(\'composer-setup.php\'); } echo PHP_EOL;"');
    if ($output != 'Installer verified') {
        throw new \Exception('composer not verified!');
    }
    ServerConsole::run('{{bin/php}} composer-setup.php');
    ServerConsole::run('{{bin/php}} -r "unlink(\'composer-setup.php\');"');
    ServerConsole::run('sudo mv ~/composer.phar /usr/bin/composer');
    writeln(ServerConsole::run('{{bin/composer}} --version'));
});
