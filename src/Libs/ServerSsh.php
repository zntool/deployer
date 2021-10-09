<?php

namespace Deployer;

class ServerSsh {

    public static function run()
    {
        ServerConsole::run('eval $(ssh-agent)');

        /*    ServerConsole::run('if ps -p $SSH_AGENT_PID > /dev/null
    then
       echo "ssh-agent is already running"
    else
        eval $(ssh-agent)
    fi');*/

    }

    public static function uploadKey(string $source)
    {
        $dest = "~/.ssh/$source";
        $isUploadedPrivateKey = ServerFs::uploadFile("{{ssh_directory}}/$source", $dest);
        $isUploadedPublicKey = ServerFs::uploadFile("{{ssh_directory}}/$source.pub", "$dest.pub");
        if($isUploadedPrivateKey || $isUploadedPublicKey) {
            ServerSsh::run();
            ServerConsole::run("ssh-add -D $dest");
            ServerConsole::run("ssh-add $dest");
        }
    }
}
