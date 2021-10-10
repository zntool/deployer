<?php

namespace ZnTool\Deployer\Libs\Base;

abstract class BaseSsh extends Base
{

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
