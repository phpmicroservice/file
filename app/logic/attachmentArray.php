<?php

namespace app\logic;

use app\Base;
use app\model\attachment_array;

/**
 * 附件集的处理
 * Class attachmentArray
 * @package logic\Attachment
 */
class attachmentArray extends Base
{

    /**
     * 建立关系
     * @param $one
     * @param $index
     * @param $attachment_id
     * @param bool $additional
     * @return bool
     */
    public function array_correlation($server_name, $index, array $attachment_id_list, bool $additional = false): bool
    {
        # 验证是否可以进行关联
        $model = attachment_array::findFirst([
            'id =:id: and server_name =:server_name: ',
            'bind' => [
                'id' => $index,
                'server_name' => $server_name
            ]
        ]);
        if (!($model instanceof attachment_array)) {
            return false;
        }
        # 建立附件及其附件集的关系
        $re = service\ArrayService::correlation($index, $attachment_id_list, $additional);
        if ($re === false) {
            return false;
        }
        return true;
    }

    /**
     * 创建一个附件集合
     * @param $user_id
     * @param $remark
     * @param $only
     * @param $server_name
     */
    public function create_array($user_id, $remark, $only, $server_name)
    {
        $index = service\ArrayService::create_array($user_id, $remark, $only, $server_name);
        return $index;
    }

    /**
     * 设置附件集的状态为0=>1 使用中
     * @param $index
     * @param $server_name
     * @return bool
     */
    public function array_status01($index, $server_name)
    {
        $model = attachment_array::findFirst([
            'id =:id: and server_name =:server_name: and status = 0',
            'bind' => [
                'id' => $index,
                'server_name' => $server_name
            ]
        ]);
        if ($model instanceof attachment_array) {
            # 成功读取这个附件集的信息
            $model->status = 1;
            if (!$model->save()) {
                return false;
            }
            return true;
        }
        return false;
    }


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
        $list = \funch\Arr::for_index($list, null, function ($list) use ($info) {
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
     * @param int $index 附件集id
     * @param $attachment_id 附件id
     * @param $server_name 服务名字
     * @return bool|int|\Phalcon\Mvc\Model\Resultset|\Phalcon\Mvc\Phalcon\Mvc\Model
     */
    public function one($user_id, $remark, int $index, $attachment_id, $server_name)
    {
        if (!$index) {
            # 不存在的附件集 先创建一个
            $index = service\ArrayService::create_array($user_id, $remark, 1, $server_name);
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
    public function many($user_id, $remark, int $index, array $attachment_id_list, $server_name)
    {

        if (!$index) {
            # 不存在的附件集 先创建一个
            $index = service\ArrayService::create_array($user_id, $remark, 0, $server_name);
            if ($index === false) {
                return false;
            }
        }
        # 建立附件及其附件集的关系
        $re = service\ArrayService::correlation($index, $attachment_id_list);
        if ($re === false) {

            return false;
        }

        return $index;
    }
}