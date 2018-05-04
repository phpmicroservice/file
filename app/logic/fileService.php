<?php

namespace app\logic;

use logic\Attachment\model as thisModel;

/**
 * Description of fileService
 *
 * @author Dongasai
 */
class fileService
{

    public $savepath;

    /**
     * 获取附件信息
     * @param $id
     * @param $uid
     * @return \Phalcon\Mvc\Model
     */
    public static function getInfo($id, $user_id)
    {
        $attachment_user = thisModel\attachment_user::findFirst([
            'user_id =:user_id: and id =:id:',
            'bind' => [
                'user_id' => $user_id,
                'id' => $id
            ]
        ]);

        if (!$attachment_user) {
            return \core\ReturnMsg::create(404, false, [$id, $user_id]);
        }
        $attachment_user_info = $attachment_user->toArray();
        $info = \app\model\attachment::findFirst('id = ' . $attachment_user->attachment_id);

        return $attachment_user_info;
    }

    /**
     * 获取文件图片对象
     * @param type $id
     */
    public function getPic($id, $uid)
    {
        $fileObj = $this->getfile($id, $uid);
        if (!$fileObj instanceof \core\Sundry\File) {
            return $fileObj;
        }
        $image = new \Phalcon\Image\Adapter\Gd($fileObj->getPath());
        return $image;

    }

    /**
     * 获取文件
     * @param $id
     * @param $uid
     * @return \core\ReturnMsg|string
     */
    private function getfile($id, $uid)
    {
        $validation = new \core\CoreValidation();
        $validation->add_Validator('id', [
            'name' => Validator\getPicPath::class,
            'message' => 'unfound'
        ]);
        $data = [
            'user_id' => $uid,
            'id' => $id
        ];
        if (!$validation->validate($data)) {
            return \core\ReturnMsg::create(400, $validation->getMessage());
        }
        $attachment_user = thisModel\attachment_user::findFirst4id($id);


        $info = \app\model\attachment::findFirst('id = ' . $attachment_user->attachment_id);
        # 此处就不用储存驱动来读取图片对象了 ,直接读取图片对象
        $fileName = ROOT_DIR . '/../upload/' . $info->savepath;
        if (!is_file($fileName)) {
            return \core\ReturnMsg::create(410, 'gone' . $fileName);
        }
        $fileObj = new \core\Sundry\File($fileName);
        $fileObj->setAsname($attachment_user->primitive_name);
        return $fileObj;
    }

    /**
     * 获取文件对象
     * @param $id
     * @param $uid
     * @return \core\ReturnMsg|string
     */
    public function getFileObj($id, $uid)
    {
        $fileObj = $this->getfile($id, $uid);
        if (!$fileObj instanceof \core\Sundry\File) {
            return $fileObj;
        }
        return $fileObj;

    }

}
