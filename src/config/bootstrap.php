<?php

namespace Deployer;

use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

\ZnCore\Base\Libs\DotEnv\DotEnv::init();

/*$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../../../../.env');
if (file_exists(__DIR__ . '/../../../../../.env.local')) {
    $dotenv->overload(__DIR__ . '/../../../../../.env.local');
}*/

require_once __DIR__ . '/../Helpers/functions.php';
require_once __DIR__ . '/../../../../deployer/deployer/recipe/common.php';

//require_once __DIR__ . '/../recipe/init_vars.php';initHostByUser();
//require_once __DIR__ . '/../recipe/init_ssh.php';

//initVars();
//initHostByUser();

App::init();

require_once __DIR__ . '/../recipe/zn.php';
