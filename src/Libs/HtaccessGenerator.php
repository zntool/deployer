<?php

namespace Deployer;

class HtaccessGenerator
{

    private $code = null;

    public function __construct()
    {
        $this->code =
            'RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d';
    }

    public function setRewriteRule(string $path, string $script)
    {
        $this->code .= PHP_EOL . "RewriteRule $path $script";
    }

    public function setDirectoryIndex(array $files)
    {
        $this->code .= PHP_EOL . "DirectoryIndex " . implode(' ', $files);
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
