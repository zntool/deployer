<?php

namespace Deployer;

function initHostByUser(string $userName = null) {
    $userName = $userName ?: get('host_user');
    $host = host(get('host_ip'));
    $host->user($userName);
    $host->port(get('host_port'));
    /*if(isset($_ENV['DEPLOYER_HOST_IDENTITY_FILE'])) {
        $host->identityFile($_ENV['DEPLOYER_HOST_IDENTITY_FILE']);
    }*/
}

function initVars()
{
    foreach ($_ENV as $name => $value) {
        if(strpos($name, 'DEPLOYER_') === 0) {
            $varName = substr($name, 9);
            $varName = mb_strtolower($varName);
            set($varName, $value);
            //dump($varName);
        }
    }
}

function runZn(string $command)
{
    cd('{{release_path}}/vendor/bin');
    return run('{{bin/php}} zn ' . $command);
}

function makeDirectory(string $directory)
{
    run("mkdir -p $directory");
}

function isFileExists(string $file): bool
{
    return test("[ -f $file ]");
}

function isFileExistsLocally(string $file): bool
{
    return testLocally("[ -f $file ]");
}

function runSshAgent()
{
    run('eval $(ssh-agent)');

    /*    run('if ps -p $SSH_AGENT_PID > /dev/null
then
   echo "ssh-agent is already running"
else
    eval $(ssh-agent)
fi');*/

}

function uploadKey(string $source)
{
    $dest = "~/.ssh/$source";
    $isUploadedPrivateKey = uploadIfNotExist("{{ssh_directory}}/$source", $dest);
    $isUploadedPublicKey = uploadIfNotExist("{{ssh_directory}}/$source.pub", "$dest.pub");
    if($isUploadedPrivateKey || $isUploadedPublicKey) {
        runSshAgent();
        run("ssh-add -D $dest");
        run("ssh-add $dest");
    }
}

function uploadIfNotExist(string $source, string $dest): bool
{
    if (!isFileExists($dest)) {
        upload($source, $dest);
        return true;
        //writeln("File \"$dest\" already exist");
    } else {
        return false;
        //writeln("File \"$dest\" already exist");
    }
}
