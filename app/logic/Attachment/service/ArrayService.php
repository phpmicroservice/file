<?php

namespace logic\Attachment\service;

use core\Sundry\Trace;
use logic\Attachment\model as thisModel;


/**
 * Created by PhpStorm.
 * User: saisai
 * Date: 17-5-23
 * Time: 上午11:07
 */
class ArrayService
{

    /**
     * 删除这个附件集
     */
    public static function delectt($array_id)
    {
        # 删除附件集自己
        $model = thisModel\attachment_array::findFirst([
            'id=:id:',
            'bind' => [
                'id' => $array_id
            ]
        ]);
        if ($model->delete() === false) {
            return false;
        }
        # 删除附件集内的附件
        $correlation = new correlation();
        $old_list = $correlation->get_attachment_user_id_list($array_id);
        $re = $correlation->del($array_id, $old_list);
        if ($re === false) {
            return false;
        }
        return true;


    }

    /**
     * 创建附件集
     * @param $user_id
     * @param $remark
     * @param $only
     * @return \Phalcon\Mvc\Model\Resultset|\Phalcon\Mvc\Phalcon\Mvc\Model
     */
    public static function create_array($user_id, $remark, $only)
    {
        $data = [
            'user_id' => $user_id,
            'remark' => $remark,
            'create_time' => time(),
            'total' => 0,
            'status' => 1,
            'only' => $only
        ];
        $model = new thisModel\attachment_array();
        $model->setData($data);
        if ($model->save() === false) {
            Trace::add('error', $model->getMessage());
            return fasle;
        }
        return $model->id;
    }

    /**
     * 进行附件 附件集关系处理
     * @param $index
     * @param $attachment_id_list
     * @return bool
     */
    public static function correlation(int $array_id, $attachment_id_list)
    {
        if (empty($attachment_id_list)) {
            return true;
        }
        $correlation = new correlation();
        $old_list = $correlation->get_attachment_user_id_list($array_id);
        $del = array_diff($old_list, $attachment_id_list);
        $add = array_diff($attachment_id_list, $old_list);
        $re = $correlation->add($array_id, $add);
        if ($re === false) {

            return false;
        }
        $re = $correlation->del($array_id, $del);
        if ($re === false) {
            return false;
        }

        # 更新附件集关系完成
        # 更新附件集的附件数量
        $re57 = self::update_total($array_id);
        if ($re57 === false) {
            return false;
        }
        return true;
    }

    /**
     * 更新这个附件集的附件数量
     * @param $array_id
     * @return bool
     */
    public static function update_total($array_id)
    {
        $model = thisModel\attachment_array::findFirst('id =' . $array_id);
        $total = thisModel\attachment_array_correlation::count([
                'array_id = :array_id:',
                'bind' => [
                    'array_id' => $array_id
                ]
            ]
        );


        $model->setData(['total' => $total]);
        if ($model->save() === false) {
            Trace::add('error', $model->getMessages());
            return false;
        }
        return true;
    }

    /**
     * 进行附件 附件集关系处理
     * @param $index
     * @param $attachment_id_list
     * @return bool
     */
    public static function correlation_one(int $array_id, $attachment_id)
    {
        $correlation = new correlation();
        $old_list = $correlation->get_attachment_user_id_list($array_id);
        if (isset($old_list[0])) {
            $old_id = $old_list[0];
        } else {
            $old_id = 0;
        }

        if (is_array($attachment_id)) {
            $attachment_id = $attachment_id[0];
        }
        if ($attachment_id != $old_id) {
            $add = [$attachment_id];
            $del = [$old_id];

            $re = $correlation->add($array_id, $add);
            if ($re === false) {
                return false;
            }

            $re = $correlation->del($array_id, $del);
            if ($re === false) {

                return false;
            }
        }


        # 更新附件集关系完成
        # 更新附件集的附件数量
        $re57 = self::update_total($array_id);
        if ($re57 === false) {
            return false;
        }
        return true;
    }
}