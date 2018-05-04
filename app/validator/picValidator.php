<?php

namespace logic\Attachment\Validator;

/**
 * picValidator
 * 验证是否为图片附件
 * @author Dongasai
 */
class picValidator extends \core\CoreValidator
{

    public function validate(\Phalcon\Validation $validation, $attribute)
    {
        $info = $validation->getValue($attribute);
        if (is_array($info)) {
            foreach ($info as $attachment_id) {
                $re = $this->validation_info($attachment_id);
                if ($re === false) {
                    return $this->appendMessage($validation, $attribute);
                }
            }
        }
        # 仅有一个数据
        $re = $this->validation_info($info);
        if ($re === false) {
            return $this->appendMessage($validation, $attribute);
        }
        return true;

    }

    /**
     * 进行附件id验证
     * @param $attachment_id
     */
    private function validation_info($attachment_id)
    {

    }


}
