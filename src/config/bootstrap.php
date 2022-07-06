<?php

namespace Deployer;

use ZnCore\Code\Helpers\PhpHelper;

require_once __DIR__ . '/../Helpers/functions.php';
PhpHelper::requireFromDirectory(__DIR__ . '/../Libs');
PhpHelper::requireFromDirectory(__DIR__ . '/../Command', true);

//PhpHelper::requireFromDirectory(__DIR__ . '/../Command/Base');
//PhpHelper::requireFromDirectory(__DIR__ . '/../Command/Local');
//PhpHelper::requireFromDirectory(__DIR__ . '/../Command/Server');

set('show_detail', 0);
set('sudo_cmd', 'sudo -S {command} < ~/sudo-pass');
set('allow_anonymous_stats', 1);
set('git_tty', 1);
set('default_stage', 'staging');
