<?php

namespace Deployer;

use ZnCore\Base\Develop\Helpers\DeprecateHelper;

/**
 * @deprecated 
 * @see zn:migrate_up
 */
task('database:migrate_up', function () {
    DeprecateHelper::hardThrow();
    $output = ServerZn::migrateUp();
//    $output = Zn::run('db:migrate:up --withConfirm=0');
    View::result($output);
})->desc('Run migrations');

/**
 * @deprecated
 * @see zn:fixtures_import
 */
task('database:fixtures_import', function () {
    DeprecateHelper::hardThrow();
    $output = ServerZn::fixtureImport();
//    $output = Zn::run('db:fixture:import --withConfirm=0');
    View::result($output);
})->desc('Import fixtures');
