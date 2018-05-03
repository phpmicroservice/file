<?php

namespace logic\Attachment\Validator;

use logic\Attachment\model as thisModel;


class getPicPath extends \core\CoreValidator
{

    /**
     * 执行验证
     * @param \Phalcon\Validation $validation 这个验证器
     * @param string $attribute 要验证的字段名字
     * @return boolean
     */
    public function validate(\Phalcon\Validation $validation, $attribute)
    {

        # 先读取这个用户附件
        $id = $validation->getValue('id');

        $user_id = $validation->getValue('user_id');
        $attachment_user = thisModel\attachment_user::findFirstByid($id);
        if ($attachment_user == false) {
            $this->type = 'user0';
            return $this->appendMessage($validation, $attribute);
        }
        if ($attachment_user->status == 0) {
            # 暂未使用的私有的
            if ($attachment_user->user_id != $user_id) {
                return true;
                # 验证不通过
                $this->type = 'user1';
                return $this->appendMessage($validation, $attribute);
            }
        }
        return true;
        # 读取附件
        $attachment = thisModel\attachment::findFirst4id($attachment_user->attachment_id);
        $attachment_array = $attachment->toArray();

    }


}