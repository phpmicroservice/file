<?php

namespace app\logic;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class storeDriver
{


    /**
     * @param $type local|awsS3|aliyunOOS|ftp
     * @param $config
     * @return Filesystem
     */
    public static function getObject($type, $config): \League\Flysystem\Filesystem
    {
        return self::$type($config);
    }


    /**
     * 路径转换
     * @param $type
     * @param $name
     * @return string
     */
    public static function topath($type, $name)
    {
        if ($type == 'local') {
            return ROOT_DIR . $name;
        } else {
            return $name;
        }
    }


    /**
     * 本地储存的对象实例化
     */
    private static function local($config)
    {
        $adapter = new Local('/');
        $filesystem = new Filesystem($adapter);
        return $filesystem;
    }


    /**
     * aws的s3的 对象实例化
     * @param $config
     */
    private static function awsS3($config)
    {
        //pre($config);
        $client = S3Client::factory([
            'credentials' => [
                'key' => $config['credentials_key'],
                'secret' => $config['credentials_secret'],
            ],
            'region' => $config['region'],
            'version' => 'latest',
        ]);

        $adapter = new AwsS3Adapter($client, $config['bucket'], $config['dir']);

        $filesystem = new Filesystem($adapter);
        return $filesystem;
    }

}