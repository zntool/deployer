<?php

namespace Deployer;

class ServerUser {

    public static function setSudoPassword(): void
    {
        $pass = askHiddenResponse('Input sudo password:');
        ServerFs::uploadContent($pass, '~/sudo-pass');
    }
}
