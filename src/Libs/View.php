<?php

namespace Deployer;

class View
{

    public static function newLine(int $count = 1)
    {
        for ($i=0; $i < $count; $i++) {
            writeln(" ");
        }
    }
    
    public static function listItem(string $item)
    {
        writeln(" * $item");
    }

    public static function list(array $list)
    {
        foreach ($list as $item) {
            self::listItem($item);
        }
    }

    public static function warning(string $output)
    {
        //if (get('show_detail')) {
        writeln("<fg=yellow>! $output</>");
        //}
    }

    public static function info(string $output)
    {
        //if (get('show_detail')) {
        writeln("<fg=blue>i $output</>");
        //}
    }

    public static function result(string $output)
    {
        if (get('show_detail')) {
            writeln($output);
        }
    }

    public static function head(string $output)
    {
        if (get('show_detail')) {
            writeln("<info>  $output</info>");
        }
    }

    public static function success(string $output)
    {
        //if (get('show_detail')) {
        writeln("<fg=green>✔ $output</>");
        //}
    }

    public static function error(string $output)
    {
        //if (get('show_detail')) {
        writeln("<fg=red>❌ $output</>");
        throw new \Exception($output);
        //}
    }
}
