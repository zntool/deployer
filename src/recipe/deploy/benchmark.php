<?php

namespace Deployer;

use ZnCore\Base\Libs\Develop\Libs\Benchmark;

class BenchmarkWidget
{

    public static function total(): ?string
    {
        if (!Benchmark::has('deployer')) {
            return null;
        }

        $seconds = Benchmark::one('deployer');
        if ($seconds == 0) {
            Benchmark::end('deployer');
            $seconds = Benchmark::one('deployer');
        }
        $minutes = substr('0' . intval($seconds / 60), -2);
        $seconds %= 60;
        $seconds = substr('0' . $seconds, -2);
        return "$minutes:$seconds";
    }
}

task('benchmark:start', function () {
    Benchmark::begin('deployer');
});

task('benchmark:end', function () {
    Benchmark::end('deployer');
});

task('benchmark:total', function () {
    $total = BenchmarkWidget::total();
    writeln("Total time: $total");
});
