<?php

namespace logic\Attachment\storeDriver;

/**
 * 上传文件存在驱动的抽象方法
 * @author Dongasai
 */
abstract class Base implements Action
{

    protected $config;

    /**
     * 初始化
     */
    public function __construct($config)
    {
        ;
    }

    /**
     * 获取存放信息
     */
    public function getInfo()
    {

    }

    /**
     *
     */
    public function remove($old, $new)
    {

    }

    /**
     * 存放进去
     */
    public function add($file)
    {

    }

}
