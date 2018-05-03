<?php

namespace logic\Attachment\storeDriver;

/**
 * 上传文件存放 接口
 * @author Dongasai
 */
Interface Action
{

    /**
     * 初始化
     */
    public function __construct($config);

    /**
     * 获取存放信息
     */
    public function getInfo();

    /**
     * 移动
     */
    public function remove($old, $file_dir, $file_name);

    /**
     * 存放进去
     */
    public function add($localfile, $file_dir, $file_name);

    /**
     * 删除
     * @param $file_name
     * @return mixed
     */
    public function rm($file_name);


}
