<?php

namespace app\logic;

use app\Base;

/**
 * 附件工具
 * Class attachment
 * @package logic\Common
 * @property \app\logic\attachmentArray attachmentArray
 */
class attachment extends Base
{

    public function __construct()
    {
        $this->di->set('attachmentArray', function () {
            return new \app\logic\attachmentArray();
        });
    }

    /**
     * 处理附件数据 新增的时候
     * @param $user_id 用户id
     * @param $data 数据
     * @param $index 索引
     * @param $type 类型
     * @param bool $one 是否单图
     */
    public function dispose_data_add($user_id, $data, $index, $type, $one = true)
    {
        if ($one) {
            $content = $this->attachmentArray->one($user_id, $type, 0, $data[$index]);
        } else {
            $content = $this->attachmentArray->many($user_id, $type, 0, $data[$index]);
        }
        $data[$index] = $content;
        return $data;
    }

    /**
     * 处理附件数据 修改的时候
     * @param $user_id 用户
     * @param $data 数据
     * @param $index 数据索引
     * @param $old 就得数据集id
     * @param $type 类型
     * @param bool $one 是否单图
     * @return mixed
     */
    public function dispose_data_edit($user_id, $data, $index, $old, $type, $one = true)
    {

        if ($one) {

            $content = $this->attachmentArray->one($user_id, $type, $old, $data[$index]);
        } else {
            $data58 = $data[$index];
            if (is_array($data)) {

            } else {
                $data58 = explode(',', $data58);
            }

            $content = $this->attachmentArray->many($user_id, $type, $old, $data58);
        }
        $data[$index] = $content;
        return $data;
    }
}