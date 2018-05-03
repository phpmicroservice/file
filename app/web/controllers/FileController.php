<?php

namespace apps\web\controllers;

use app\Controller;
use logic\Attachment as mainUpload;

/**
 * 文件上传处理
 */
class FileController extends Controller
{

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * 图片显示
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function pic()
    {
        $pid = $this->getParam(0);
        $fileService = new mainUpload\fileService();
        $image = $fileService->getPic($pid, $this->user_id);
        if ($image instanceof \Phalcon\Image\Adapter\Gd) {
            $this->response->setCache(3600);
            $this->response->setHeader('Pragma', 'public');
            $this->response->setHeader('Content-Type', $image->getMime());
            //存在图片
            return $this->response->setContent(file_get_contents($image->getRealpath()));
        } else {
            return $this->restful_return($image);
        }
    }

    /**
     * 获取附件信息
     */
    public function info()
    {
        $pid = $this->request->get('id', 'int', 0);
        $info = mainUpload\fileService::getInfo($pid, $this->user_id);
        return $this->restful_return($info);
    }

    /**
     * 下载文件
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function downloadfile()
    {
        $pid = $this->getParam(0);
        $fileService = new mainUpload\fileService();
        $obj = $fileService->getFileObj($pid, $this->user_id);
        if ($obj instanceof \core\Sundry\File) {
            $this->response->setContentLength($obj->getSize());
            header("Content-Type:application/octet-stream");
            header("Content-Disposition:attachment;filename=" . $obj->getAsName());
            return $this->response->setContent(file_get_contents($obj->getPath()));
        } else {
            return $this->restful_return($obj);
        }
    }

}
