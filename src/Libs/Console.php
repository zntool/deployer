<?php

namespace Deployer;

class Console
{

    public static function writelnResult(string $output)
    {
        if (get('show_detail')) {
            writeln($output);
        }
    }

    public static function writelnHead(string $output)
    {
        if (get('show_detail')) {
            writeln("<info>  $output</info>");
        }
    }

    public static function writelnSuccess(string $output)
    {
        //if (get('show_detail')) {
            writeln("<fg=green>✔ $output</>");
        //}
    }
}
