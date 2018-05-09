<?php

namespace app\logic;

/**
 * Created by PhpStorm.
 * User: saisai
 * Date: 17-7-10
 * Time: 下午2:07
 */
class File
{
    # 文件名字
    private $_name;
    # 文件大小
    private $_size;
    # 文件类型
    private $_type;
    # 文件位置
    private $_filePath;
    # 文件映射名字
    private $_asName;
    private $_data;


    public function __construct($filePath, $data = [])
    {
        $this->_name = \basename($filePath);
        $this->_asName = $this->_name;
        $this->_size = \filesize($filePath);
        $this->_type = \filetype($filePath);
        $this->_filePath = $filePath;
        $this->_data = $data;
    }

    /**
     * 获取文件的映射名字
     * @return string
     */
    public function getAsName(): string
    {
        return $this->_asName;
    }

    /**
     * 设置文件的映射名字
     * @param $name
     */
    public function setAsname(string $name)
    {
        $this->_asName = $name;

    }

    public function getSize(): int
    {
        return $this->_size;
    }


    public function getName(): string
    {
        return $this->_name;
    }


    public function getType(): string
    {
        return $this->_type;

    }

    public function getPath()
    {
        return $this->_filePath;
    }

    public function getData()
    {
        return $this->_data;
    }


}