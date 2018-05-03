<?php

namespace apps\web\controllers;

use apps\api\upload as mainUpload;

/**
 * 废弃的文件上传控制器
 * 曾经微ueditor设计的控制器
 */
class UploadController extends \core\CoreController
{

    protected $image_config = [
        /* 执行上传图片的action名称 */
        "imageActionName" => "uploadimage",
        "imageFieldName" => "upfile", /* 提交的图片表单名称 */
        "imageMaxSize" => 2048000, /* 上传大小限制，单位B */
        "imageAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
        "imageCompressEnable" => true, /* 是否压缩图片,默认是true */
        "imageCompressBorder" => 1600, /* 图片压缩最长边限制 */
        "imageInsertAlign" => "none", /* 插入的图片浮动方式 */
        "imageUrlPrefix" => "", /* 图片访问路径前缀 */
        "imagePathFormat" => "/image/{yyyy}/{mm}/{dd}/{uq}",
        /* 上传保存路径,可以自定义保存路径和文件名格式 */
        /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
        /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
        /* {time} 会替换成时间戳 */
        /* {yyyy} 会替换成四位年份 */
        /* {yy} 会替换成两位年份 */
        /* {mm} 会替换成两位月份 */
        /* {dd} 会替换成两位日期 */
        /* {hh} 会替换成两位小时 */
        /* {ii} 会替换成两位分钟 */
        /* {ss} 会替换成两位秒 */
        /* 非法字符 \ : * ? " < > | */
        /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */
    ];
    protected $scrawl_config = [
        /* 涂鸦图片上传配置项 */
        "scrawlActionName" => "uploadscrawl", /* 执行上传涂鸦的action名称 */
        "scrawlFieldName" => "upfile", /* 提交的图片表单名称 */
        "scrawlPathFormat" => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "scrawlMaxSize" => 2048000, /* 上传大小限制，单位B */
        "scrawlUrlPrefix" => "", /* 图片访问路径前缀 */
        "scrawlInsertAlign" => "none",
    ];
    protected $snapscreen_config = [
        /* 截图工具上传 */
        "snapscreenActionName" => "uploadimage", /* 执行上传截图的action名称 */
        "snapscreenPathFormat" => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "snapscreenUrlPrefix" => "", /* 图片访问路径前缀 */
        "snapscreenInsertAlign" => "none", /* 插入的图片浮动方式 */
    ];
    protected $catcher_config = [
        /* 抓取远程图片配置 */
        "catcherLocalDomain" => ["127.0.0.1", "localhost", "img.baidu.com"],
        "catcherActionName" => "catchimage", /* 执行抓取远程图片的action名称 */
        "catcherFieldName" => "source", /* 提交的图片列表表单名称 */
        "catcherPathFormat" => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "catcherUrlPrefix" => "", /* 图片访问路径前缀 */
        "catcherMaxSize" => 2048000, /* 上传大小限制，单位B */
        "catcherAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 抓取图片格式显示 */
    ];
    protected $video_config = [
        /* 上传视频配置 */
        "videoActionName" => "uploadvideo", /* 执行上传视频的action名称 */
        "videoFieldName" => "upfile", /* 提交的视频表单名称 */
        "videoPathFormat" => "/video/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "videoUrlPrefix" => "", /* 视频访问路径前缀 */
        "videoMaxSize" => 102400000, /* 上传大小限制，单位B，默认100MB */
        "videoAllowFiles" => [
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav",
            ".mid"], /* 上传视频格式显示 */
    ];
    protected $file_config = [
        /* 上传文件配置 */
        "fileActionName" => "uploadfile", /* controller里,执行上传视频的action名称 */
        "fileFieldName" => "upfile", /* 提交的文件表单名称 */
        "filePathFormat" => "/file/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "fileUrlPrefix" => "", /* 文件访问路径前缀 */
        "fileMaxSize" => 51200000, /* 上传大小限制，单位B，默认50MB */
        "fileAllowFiles" => [
            ".png", ".jpg", ".jpeg", ".gif", ".bmp",
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav",
            ".mid",
            ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
            ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt",
            ".md", ".xml"
        ], /* 上传文件格式显示 */
    ];
    protected $imageManager_config = [
        /* 列出指定目录下的图片 */
        "imageManagerActionName" => "listimage", /* 执行图片管理的action名称 */
        "imageManagerListPath" => "/image/", /* 指定要列出图片的目录 */
        "imageManagerListSize" => 20, /* 每次列出文件数量 */
        "imageManagerUrlPrefix" => "", /* 图片访问路径前缀 */
        "imageManagerInsertAlign" => "none", /* 插入的图片浮动方式 */
        "imageManagerAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */
    ];
    protected $fileManager_config = [
        /* 列出指定目录下的文件 */
        "fileManagerActionName" => "listfile", /* 执行文件管理的action名称 */
        "fileManagerListPath" => "/file/", /* 指定要列出文件的目录 */
        "fileManagerUrlPrefix" => "", /* 文件访问路径前缀 */
        "fileManagerListSize" => 20, /* 每次列出文件数量 */
        "fileManagerAllowFiles" => [
            ".png", ".jpg", ".jpeg", ".gif", ".bmp",
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav",
            ".mid",
            ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
            ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt",
            ".md", ".xml"
        ] /* 列出的文件类型 */
    ];

    public function indexAction()
    {
        $action = $this->request->get('action');
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->response->setJsonContent(['state' => '请求地址出错']);
        }
    }

    /**
     * 上传图片
     */
    public function uploadimage()
    {
        if ($this->request->isPost()) {
            $CONFIG = $this->image_config;
            $config = array(
                'FieldName' => $CONFIG['imageFieldName'],
                "pathFormat" => $CONFIG['imagePathFormat'],
                "maxSize" => $CONFIG['imageMaxSize'],
                "allowFiles" => $CONFIG['imageAllowFiles'],
                'CompressBorder' => $CONFIG['imageCompressBorder']
            );
            //上传之前进行文件验证
            $validation = new \apps\main\upload\Validation();
            $validation->setStore($this->attachment_store);
            //验证唯一性
            $fileInfo = $this->request->getUploadedFiles($config['FieldName'])[0];
            $re50 = $validation->validHash($fileInfo);
            //写入验证规则
            if ($re50) {
                //存在文件

                $data = $this->oldTo($re50, $fileInfo);
                return $this->response->setJsonContent($data);
            }
            $re = $validation->setRules($config);
            if (is_string($re)) {
                return $this->response->setJsonContent(array(
                    'state' => $re
                ));
            }
            //进行验证
            $remessages2 = $validation->validate();
            if (count($remessages2)) {
                return $this->response->setJsonContent(array(
                    'state' => $remessages2[0]->getMessage()
                ));
            }
            # 进行验证 采用Upload中的验证


            $fieldName = $CONFIG['imageFieldName'];
            //进行上传
            $base64 = "upload";
            $up = new mainUpload\Uploader($fieldName, $config, $this->attachment_store, $base64);

            return $this->response->setJsonContent($up->getFileInfo());
        } else {
            return $this->response->setJsonContent(array(
                'state' => "缺少上传内容!"
            ));
        }
    }

    /**
     * 旧文件细腻转换
     * @param \apps\main\upload\attachment $attachment
     */
    private function oldTo(\apps\main\upload\attachment $attachment, \Phalcon\Http\Request\File $file)
    {
        /**
         * original         :          "QQ截图20170407153156.png"
         * saveStore         :          1
         * savepath          :          null
         * size          :          8513
         * state         :          "未知错误ERROR_SAVE_SQL"
         * title         "58e75065aae5e.png"
         *           type       :          ".png"
         * url         :          ""
         */
        $array = [
            'original' => $file->getName(),
            'saveStore' => $attachment->saveStore,
            'savepath' => $attachment->savepath,
            'size' => $file->getSize(),
            'state' => 'SUCCESS',
            'url' => $this->url->get('main/file/pic', ['id' => $attachment->id])
        ];
        return $array;
    }

    /**
     * 抓取远程图片
     */
    public function catchimage()
    {
        $CONFIG = $this->catcher_config;
        /* 上传配置 */
        $config = array(
            "pathFormat" => $CONFIG['catcherPathFormat'],
            "maxSize" => $CONFIG['catcherMaxSize'],
            "allowFiles" => $CONFIG['catcherAllowFiles'],
            "oriName" => "remote.png"
        );
        $fieldName = $CONFIG['catcherFieldName'];

        /* 抓取远程图片 */
        $list = array();
        if ($this->request->hasPost($fieldName)) {
            $source = $this->request->getPost($fieldName);
        } else {
            $source = $this->request->get($fieldName);
        }
        foreach ($source as $imgUrl) {
            $item = new mainUpload\Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
            ));
        }

        /* 返回抓取数据 */
        return json_encode(array(
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list' => $list
        ));
    }

    /**
     * 返回配置信息
     * @return type
     */
    private function config()
    {
        $config = array_merge(
            $this->catcher_config, $this->fileManager_config, $this->file_config, $this->imageManager_config, $this->image_config, $this->scrawl_config, $this->snapscreen_config, $this->video_config
        );
        return $this->response->setJsonContent($config);
    }

}
