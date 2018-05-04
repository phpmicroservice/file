<?php

namespace app\logic;

use app\Base;


/**
 * 附件集的处理
 * Class attachmentArray
 * @package logic\Attachment
 */
class attachmentArray extends Base
{


    /**
     * 根据集合id获取这个集合下的附件列表
     * @param $array_id
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function list4id($array_id, $info = false, $first = false)
    {
        if ($array_id == 0) {
            return [];
        }
        $list = service\correlation::get_attachment_user_id_list($array_id, $info);
        $list = \tool\Arr::for_index($list, null, function ($list) use ($info) {
            $lissss = service\attachment_user::list4lid($list, $info);
            return $lissss;
        }, true);
        if ($first) {
            if (isset($list[0])) {
                return $list[0];
            }
        }
        return $list;
    }


    /**
     * 删除这个id
     * @param $array_id
     */
    public function del($array_id)
    {
        return service\ArrayService::delectt($array_id);

    }

    /**
     * 单图的附件集
     * @param $user_id 用户id
     * @param $remark 备注
     * @param $index 附件集id
     * @param $attachment_id 附件id
     * @return bool|\Phalcon\Mvc\Model\Resultset|\Phalcon\Mvc\Phalcon\Mvc\Model
     */
    public function one($user_id, $remark, int $index, $attachment_id)
    {
        if (!$index) {
            # 不存在的附件集 先创建一个
            $index = service\ArrayService::create_array($user_id, $remark, 1);
            if ($index === false) {
                return false;
            }
        }
        # 建立附件及其附件集的关系
        $re = service\ArrayService::correlation_one($index, $attachment_id);
        if ($re === false) {
            return false;
        }

        return $index;
    }

    /**
     * 多图的附件集 处理
     * @param $user_id 用户id
     * @param $remark 备注
     * @param $index 附件集id
     * @param $attachment_id_list  附件数组
     * @return bool|\Phalcon\Mvc\Model\Resultset|\Phalcon\Mvc\Phalcon\Mvc\Model
     */
    public function many($user_id, $remark, int $index, array $attachment_id_list)
    {

        if (!$index) {
            # 不存在的附件集 先创建一个
            $index = service\ArrayService::create_array($user_id, $remark, 0);
            if ($index === false) {
                return false;
            }
        }
        # 建立附件及其附件集的关系
        $re = service\ArrayService::correlation($index, $attachment_id_list);
        if ($re === false) {
            Trace::add('error', 98);
            return false;
        }

        return $index;
    }
}