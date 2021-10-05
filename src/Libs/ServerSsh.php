<?php

namespace Deployer;

class ServerSsh {

    public static function run()
    {
        run('eval $(ssh-agent)');

        /*    run('if ps -p $SSH_AGENT_PID > /dev/null
    then
       echo "ssh-agent is already running"
    else
        eval $(ssh-agent)
    fi');*/

    }

    public static function uploadKey(string $source)
    {
        $dest = "~/.ssh/$source";
        $isUploadedPrivateKey = ServerFs::uploadIfNotExist("{{ssh_directory}}/$source", $dest);
        $isUploadedPublicKey = ServerFs::uploadIfNotExist("{{ssh_directory}}/$source.pub", "$dest.pub");
        if($isUploadedPrivateKey || $isUploadedPublicKey) {
            ServerSsh::run();
            run("ssh-add -D $dest");
            run("ssh-add $dest");
        }
    }
}