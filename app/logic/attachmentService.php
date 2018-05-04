<?php

namespace app\logic;

use app\Base;
use \app\model\attachment_array_correlation;
use logic\Attachment\Validation\get_array_index;

class attachmentService extends Base
{

    /**
     * 获取一个集合索引
     * @param $user_id
     * @param $remark
     * @param int $only
     * @return bool|int|string
     */
    public function get_array_index($user_id, $remark, $only = 0)
    {

        $data = [];
        $data['user_id'] = $user_id;
        $data['remark'] = $remark;
        $data['total'] = 0;
        $data['status'] = 0;
        $data['only'] = $only;

        $validation = new get_array_index();
        $validation->validate($data);
        if ($validation->isError()) {
            return $validation->getMessage();
        }
        $model = new  \app\model\attachment_array();
        $model->setData($data);
        if ($model->save() === false) {
            return $model->getMessage();
        }
        return (int)$model->id;
    }

    /**
     * 设置集合与附件关系
     * @param $array_id
     * @param $attachment_user_id
     */
    public function set_array_correlation(int $array_id, $attachment_user_id)
    {

        if (is_array($attachment_user_id)) {

        } else {
            $attachment_user_id = [$attachment_user_id];
        }
        # 读取集合信息
        $array_info = \app\model\attachment_array::findFirstById($array_id);
        if (empty($array_info)) {
            return '_empty-error';
        }
        $attachment_user_id_old = \app\model\attachment_array_correlation::get_list_index($array_id);
        $del = array_diff($attachment_user_id_old, $attachment_user_id);
        $add = array_diff($attachment_user_id, $attachment_user_id_old);
        if ($array_info->only == 1 and count($add)) {
            return '_error';
        }
        # 进行处理
        foreach ($del as $value) {
            $re = $this->del_array_correlation($array_id, $value);
            if (is_string($re)) {
                return $re;
            }
        }
        foreach ($add as $value1) {
            $re71 = $this->add_array_correlation($array_id, $value1);
            if (is_string($re71)) {
                return $re71;
            }
        }
        return true;

    }

    /**
     * 增加 集合 附件关系
     * @param int $array_id
     * @param int $attachment_user_id
     */
    private function del_array_correlation(int $array_id, int $attachment_user_id)
    {
        $where = [
            'conditions' => 'array_id = :array_id: and attachment_user_id =:attachment_user_id:',
            'bind' => [
                'array_id' => $array_id,
                'attachment_user_id' => $attachment_user_id
            ]
        ];
        $model = attachment_array_correlation::findFirst($where);
        if ($model->delete() === false) {
            return $model->getMessage();
        }
        return true;
    }

    /**
     * 增加 集合 附件关系
     * @param int $array_id
     * @param int $attachment_user_id
     */
    private function add_array_correlation(int $array_id, int $attachment_user_id)
    {
        $data = [
            'array_id' => $array_id,
            'attachment_user_id' => $attachment_user_id
        ];
        $mode = new attachment_array_correlation();
        $mode->setData($data);
        if ($mode->save() === false) {
            return $mode->getMessage();
        }
        return true;

    }

}