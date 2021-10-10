<?php

namespace ZnTool\Deployer\Command\Base;

abstract class BaseConsole
{

    protected static $sudoCommandName = 'sudo';

    abstract public static function test(string $command);

    abstract public static function cd(string $path);

    abstract protected static function _run(string $command, $options = []);

    abstract protected static function getSudoCommandTemplate();

    protected static function getSudoCommandName(): string
    {
        return static::$sudoCommandName . ' ';
    }

    public static function run(string $command, $options = [])
    {
        if (static::isSudo($command)) {
            $command = static::stripSudo($command);
            return static::runSudo($command, $options);
        }
        return static::_run($command, $options);
    }

    protected static function runSudo(string $command, $options = [])
    {
        $sudoCmdTpl = static::getSudoCommandTemplate();
        if ($sudoCmdTpl) {
            $command = str_replace('{command}', $command, $sudoCmdTpl);
        }
        return static::_run($command, $options);
    }

    protected static function stripSudo(string $command): string
    {
        $command = trim($command);
        $command = substr($command, strlen(static::getSudoCommandName()));
        return $command;
    }

    protected static function isSudo(string $command): bool
    {
        $command = trim($command);
        return strpos($command, static::getSudoCommandName()) === 0;
    }
}
