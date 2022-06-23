<?php

namespace Deployer;

use ZnCore\Base\Libs\Arr\Helpers\ArrayHelper;

class SshConfig
{

    private $config = [];

    /*public function __construct(string $config)
    {
        $this->config = $config;
    }*/

    public function getConfig(): array {
        return $this->config;
    }

    public function hasByName(string $name)
    {
        $indexed = ArrayHelper::index($this->config, 'name');
        return isset($indexed[$name]);
    }

    public function add(string $name, string $host, string $path)
    {
        $this->config[] = [
            'name' => $name,
            'host' => $host,
            'path' => $path,
        ];
    }

    public function parse(string $config)
    {
        preg_match_all('/Host\s+([^\s]+)\s+IdentityFile\s+([^\s]+)/', $config, $matches);
        foreach ($matches[1] as $index => $domain) {
            $keyName = $matches[2][$index];
            $this->config[] = [
                'name' => basename($keyName),
                'host' => $domain,
                'path' => $keyName,
            ];
        }
        return $this->config;
    }

    public function generate()
    {
        $codeArr = [];
        foreach ($this->config as $item) {
            $keyName = $item['name'];
            $domain = $item['host'];
            $codeArr[] = "Host $domain
 IdentityFile ~/.ssh/$keyName";
        }
        $code = implode(PHP_EOL, $codeArr);
        return $code;
    }
}
