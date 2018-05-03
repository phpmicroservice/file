<?php

namespace logic\Attachment\storeDriver;

/**
 * 文件本地储存驱动
 * @author Dongasai
 */
class Local implements Action
{

    protected static $config = [
        'dir' => '../upload/'
    ];
    protected $info = [];

    /**
     * 初始化
     */
    public function __construct($config)
    {
        self::$config = array_merge(self::$config, $config);

    }

    /**
     *
     */
    public function remove($old, $newFile_dir, $newfile_name)
    {

    }

    /**
     * 删除文件
     * @param $file_name
     */
    public function rm($file_name)
    {
        $file_name2 = self::$config['dir'] . $file_name;
        unlink($file_name2);
    }

    /**
     * 存放进去
     */
    public function add($file, $newFile_dir, $newfile_name)
    {

        $md5 = md5_file($file);
        $dirname = self::$config['dir'] . $newFile_dir . '/';

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            return "__ERROR_CREATE_DIR";
        } else if (!is_writeable($dirname)) {
            return "__ERROR_DIR_NOT_WRITEABLE";
        }
        if (is_file($dirname . $newfile_name)) {
            pre(self::$config['dir'] . $dirname . $newfile_name);
            return "__ERROR_FILE_MOVE";
        }
        //移动文件
        $re = (move_uploaded_file($file, $dirname . $newfile_name) && file_exists($dirname . $newfile_name));
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
     * 获取存放信息
     */
    public function getInfo()
    {
        $this->info['StoreConfig'] = self::$config;
        $this->info['SaveDriver'] = 'Local';

        return $this->info;
    }

}
