<?php

namespace ZnTool\Deployer\Command\Base;

use Deployer\ServerFs;
use Deployer\SshConfig;

abstract class BaseSsh extends Base
{

    public static function list()
    {
        $output = static::run('ssh-add -l');
        return explode(PHP_EOL, $output);
    }

    public static function getList(): array
    {
        $sshConfig = self::getSshConfig();
        return $sshConfig->getConfig();
    }

    protected static function getSshConfig(): SshConfig
    {
        $sshConfig = new SshConfig();
        $configData = [];
        if (ServerFs::isFileExists('~/.ssh/config')) {
            $config = ServerFs::downloadContent('~/.ssh/config');
            $configData = $sshConfig->parse($config);
        }
        return $sshConfig;
    }

    public static function setConfig(array $keyList)
    {
        $sshConfig = self::getSshConfig();
//        $configData = $sshConfig->getConfig();
//    $indexed = ArrayHelper::index($configData, 'name');

        foreach ($keyList as $item) {
            $keyName = $item['name'];
            $domain = $item['host'];
//        $isExists = isset($indexed[$keyName]);
            if (!$sshConfig->hasByName($keyName)) {
                $sshConfig->add($keyName, $domain, "~/.ssh/$keyName");
                /*$configData[] = [
                    'name' => $keyName,
                    'host' => $domain,
                    'path' => "~/.ssh/$keyName",
                ];*/
            }
        }

        $code = $sshConfig->generate();

        ServerFs::uploadContent($code, '~/.ssh/config');
    }

    public static function add(string $key)
    {
        static::run("ssh-add $key");
    }

    public static function generate(string $keyFile, string $type = 'rsa', int $bit = 2048, string $email = 'my@example.com')
    {
        static::run("ssh-keygen -t $type -b 2048 -C \"$email\" -f $keyFile -N \"\"");
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
//            dump($dest);
            ServerFs::chmod($dest, '=600');
            ServerFs::chmod("$dest.pub", '=600');
        }
    }
}
