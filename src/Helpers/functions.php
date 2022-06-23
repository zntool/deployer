<?php

namespace Deployer;

function skipTask(string $name)
{
    task($name, function () {
        View::warning('skip');
    });
}
