<?php

namespace ZnTool\Deployer\Command\Base;

use Deployer\ServerConsole;

abstract class BaseSsh extends Base
{

    public static function list()
    {
        $output = static::run('ssh-add -l');
        return explode(PHP_EOL, $output);
    }

    public static function runAgent()
    {
        static::run('eval $(ssh-agent)');

        /*    static::run('if ps -p $SSH_AGENT_PID > /dev/null
    then
       echo "ssh-agent is already running"
    else
        eval $(ssh-agent)
    fi');*/

    }

    public static function uploadKey(string $source)
    {
        $dest = "~/.ssh/$source";
        $isUploadedPrivateKey = static::fsClass()::uploadFile("{{ssh_directory}}/$source", $dest);
        $isUploadedPublicKey = static::fsClass()::uploadFile("{{ssh_directory}}/$source.pub", "$dest.pub");
        if ($isUploadedPrivateKey || $isUploadedPublicKey) {
            static::runAgent();
            static::run("ssh-add -D $dest");
            static::run("ssh-add $dest");
        }
    }
}
