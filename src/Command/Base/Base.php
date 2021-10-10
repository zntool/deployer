<?php

namespace ZnTool\Deployer\Command\Base;

abstract class Base
{

    const SIDE_SERVER = 'server';
    const SIDE_LOCAL = 'local';

    abstract protected static function side(): string;

    /**
     * @return string | BaseConsole
     */
    protected static function consoleClass(): string
    {
        $className = self::handlerClassName('console');
        return $className;
    }

    /**
     * @return string | BaseFs
     */
    protected static function fsClass(): string
    {
        $className = self::handlerClassName('fs');
        return $className;
    }

    protected static function handlerClassName(string $name, string $namespace = 'Deployer'): string
    {
        $className = $namespace . '\\' . ucfirst(static::side()) . ucfirst($name);
        return $className;
    }

    protected static function test(string $command): bool
    {
        $consoleClassName = static::consoleClass();
        return $consoleClassName::test($command);
    }

    protected static function run(string $command)
    {
        $consoleClassName = static::consoleClass();
        return $consoleClassName::run($command);
    }

    protected static function cd(string $path)
    {
        $consoleClassName = static::consoleClass();
        return $consoleClassName::cd($path);
    }
}
