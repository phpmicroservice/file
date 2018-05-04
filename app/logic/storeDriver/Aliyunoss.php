<?php

namespace app\logic\storeDriver;

use OSS\OssClient;
use OSS\Core\OssException;

/**
 * 阿里云的 oss 储存驱动
 * @author Dongasai
 */
class Aliyunoss implements Action
{
    protected static $config = [
        'dir' => '../upload/',
        'endpoint' => 'endpoint',
        'accessKeyId' => 'accessKeyId',
        'accessKeySecret' => 'accessKeySecret',
        'bucket' => 'bucket'
    ];
    protected $info = [];
    private $ossClient;

    /**
     * 初始化
     */
    public function __construct($config)
    {
        self::$config = array_merge(self::$config, $config);
        $this->ossClient = self::getOssClient();
        if (is_null($this->ossClient)) exit('不能正确的初始化上传配置');

    }


    /**
     * 根据Config配置，得到一个OssClient实例
     * @return OssClient 一个OssClient实例
     */
    private static function getOssClient()
    {
        try {
            $ossClient = new OssClient(self::$config['accessKeyId'], self::$config['accessKeySecret'], self::$config['endpoint'], false);
        } catch (OssException $e) {
            printf(__FUNCTION__ . "creating OssClient instance: FAILED\n");
            printf($e->getMessage() . "\n");
            return null;
        }
        return $ossClient;
    }

    /**
     * 移动文件
     */
    public function remove($old, $newFile_dir, $newfile_name)
    {
        $newfile_name2 = $newFile_dir . '/' . $newfile_name;
        $from_bucket = self::getBucketName();
        $to_bucket = self::getBucketName();
        try {
            $this->ossClient->copyObject($from_bucket, $old, $to_bucket, $newfile_name2);
        } catch (OssException $e) {
            Trace::add("error", $e->getMessage());
            return false;
        }
        return true;

    }

    /**
     * 获取储存空间名称
     * @return mixed
     */
    private static function getBucketName()
    {
        return self::$config['bucket'];
    }

    /**
     * 删除文件
     * @param $file_name
     */
    public function rm($file_name)
    {
        try {
            $this->ossClient->deleteObject(self::getBucketName(), $file_name);
        } catch (OssException $e) {
            Trace::add("error", $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 存放进去
     */
    public function add($file, $newFile_dir, $newfile_name)
    {
        $md5 = md5_file($file);
        $dirname = self::$config['dir'] . $newFile_dir . '/';

        //判断文件夹是否存在,是否可写,新文件是否已经存在

        if ($this->file_exists($dirname . $newfile_name)) {
            pre(self::$config['dir'] . $dirname . $newfile_name);
            return "__ERROR_FILE_MOVE";
        }
        //移动文件
        $re = ($this->upload($file, $dirname . $newfile_name) && $this->file_exists($dirname . $newfile_name));
        if (!$re) { //移动失败
            return "__ERROR_FILE_MOVE";
        } else { //移动成功
            $this->info = [
                'savePath' => $newFile_dir . '/' . $newfile_name,
                'md5' => $md5
            ];
        }
        return $this->getInfo();
    }

    /**
     * 判断文件是够存在
     */
    private function file_exists($file)
    {

        $bucket = self::getBucketName();
        try {
            $exist = $this->ossClient->doesObjectExist($bucket, $file);
        } catch (OssException $e) {
            Trace::add("error", $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 上传文件
     * @throws OssException
     */
    private function upload($old_file, $new_file)
    {


        $bucket = self::getBucketName();
        $options = array(OssClient::OSS_CHECK_MD5 => true);
        try {
            $this->ossClient->uploadFile($bucket, $new_file, $old_file, $options);
        } catch (OssException $e) {
            Trace::add("error", $e->getMessage());
            return false;
        }
        return true;

    }

    /**
     * 获取存放信息
     */
    public function getInfo()
    {
        $this->info['StoreConfig'] = self::$config;
        $this->info['SaveDriver'] = 'Aliyunoss';
        return $this->info;
    }

}
