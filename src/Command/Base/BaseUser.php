<?php

namespace ZnTool\Deployer\Command\Base;

use function Deployer\askHiddenResponse;

abstract class BaseUser extends Base
{

    public static function setSudoPassword(): void
    {
        $pass = askHiddenResponse('Input sudo password:');
        static::fsClass()::uploadContent($pass, '~/sudo-pass');
    }
}
