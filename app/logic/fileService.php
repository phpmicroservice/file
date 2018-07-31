<?php

namespace app\logic;

use app\model as thisModel;
use app\web\ReturnMsg;
use app\validator\getPicPath;
use pms\Validation;

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
            return ReturnMsg::create(404, false, [$id, $user_id]);
        }
        $attachment_user_info = $attachment_user->toArray();
        # $info = \app\model\attachment::findFirst('id = ' . $attachment_user->attachment_id);

        return $attachment_user_info;
    }

    /**
     * 获取文件图片对象
     * @param type $id
     */
    public function getPic($id, $uid)
    {
        $fileObj = $this->getfile($id, $uid);
        if (!$fileObj instanceof File) {
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
        $validation = new Validation();
        $validation->add_Validator('id', [
            'name' => getPicPath::class,
            'message' => 'unfound'
        ]);
        $data = [
            'user_id' => $uid,
            'id' => $id
        ];
        if (!$validation->validate($data)) {
            return \app\web\ReturnMsg::create(400, $validation->getErrorMessages());
        }

        $attachment_user = thisModel\attachment_user::findFirst4id($id);
        $info = \app\model\attachment::findFirst('id = ' . $attachment_user->attachment_id);
        # 此处就不用储存驱动来读取图片对象了 ,直接读取本地图片对象
        $fileName = ROOT_DIR . '/upload/' . $info->savepath;
        if (!is_file($fileName)) {
            return ReturnMsg::create(410, 'gone' . $fileName);
        }
        $fileObj = new File($fileName);
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
