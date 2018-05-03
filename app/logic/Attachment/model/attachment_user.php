<?php


namespace logic\Attachment\model;


class attachment_user extends \core\CoreModel
{
    public $id = 0;
    public $attachment_id = 0;
    public $user_id = 0;
    public $primitive_name = '';
    public $status = 0;
    public $type = '';
    public $auxiliary = [];

    /**
     * 获取单条记录使用id
     * @param $id
     */
    public static function findFirst4id($id): attachment_user
    {
        $info = self::findFirst([
            'id=:id:', 'bind' => [
                'id' => $id
            ]
        ]);
        return $info;
    }

    /**
     * 更新之前
     */
    public function beforeUpdate()
    {
        $this->auxiliary = $this->serialize($this->auxiliary);
    }

    /**
     * 读取之后
     */
    protected function afterFetch()
    {
        if (empty($this->auxiliary)) {
            $this->auxiliary = [];
        } else {
            $this->auxiliary = $this->unserialize($this->auxiliary);
        }
    }

    /**
     * 保存之前
     */
    protected function beforeSave()
    {
        $this->auxiliary = $this->serialize($this->auxiliary);
    }
}