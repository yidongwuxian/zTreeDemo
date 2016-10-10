<?php
/**
 * 文件的简短描述：文件上传
 * 
 * LICENSE:
 * @author lijin
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Image;

class Upload extends Controller
{
	// 上传一张图片
    public function image($fid)
    {
    	$request = Request::instance();
	    $fileId  = trim($fid);
	    if (! $fileId)
        {
            \Util::echoJson('缺少fileId');
        }

        $file 		= $request->file($fileId);
        $fileMime 	= $_FILES[$fileId]["type"];
        $extension  = pathinfo($_FILES[$fileId]["name"], PATHINFO_EXTENSION);
        $extInfo 	= \QychatLib::getImgExt();

        if (! in_array($extension, $extInfo['ext']) || ! in_array($fileMime, $extInfo['mime']))
        {
            \Util::echoJson('文件类型不支持');
        }

	    $info = $file->move(ROOT_PATH . '/public/uploads/', true, false);

	    if (! is_object($info))
        {
            \Util::echoJson('上传失败');
        }

        $imgUrl	= '/uploads/' . date('Ymd') . '/'. $info->getFilename();        

        \Util::echoJson('上传成功', true, $imgUrl);
    }

    // 删除上传的文件
    public function deleteImage()
    { 
    	$request  = Request::instance();
	    $filePath = $request->post('path');

	    if (! $filePath)
	    {
	    	\Util::echoJson('请求参数错误');
	    }

	    $path = ROOT_PATH . '/public' . $filePath;
		if (is_dir($path))
		{
			\Util::echoJson('不是文件不能删除'. $path);
		}

		if (! file_exists($path) ||  ! is_file($path))
		{
			\Util::echoJson('文件不存在');
		}

		if (! unlink($path))
		{
			\Util::echoJson('操作失败');
		}

		\Util::echoJson('操作成功', true);
    }

    // 上传一个文件
    public function file($fid, $type = 'file')
    {
        $request = Request::instance();
        $fileId  = trim($fid);
        $type    = trim($type);
        if (! $fileId)
        {
            \Util::echoJson('缺少fileId');
        }

        $file       = $request->file($fileId);
        $extension  = pathinfo($_FILES[$fileId]["name"], PATHINFO_EXTENSION);

        $types      = ['fileType', 'videoType', 'voiceType'];

        $fileType   = ["txt", "pdf", "xml", "zip", "doc", "ppt", "xls", "xlsx", "docx", "pptx"];
        $videoType  = ["rm", "rmvb", "wmv", "avi", "mpg", "mpeg", "mp4"];
        $voiceType  = ["mp3", "wma", "wav", "amr"];

        $typeIndex  = array_search($type. 'Type', $types);
        if ($typeIndex === false || ! in_array($extension, $$types[$typeIndex]))
        {
            \Util::echoJson('文件类型不支持');
        }

        $info = $file->move(ROOT_PATH . '/public/uploads/', true, false);

        if (! is_object($info))
        {
            \Util::echoJson('上传失败');
        }

        if ($type == 'file')
        {
            $showpic = self::fileShow($extension);
        } 
        else
        {
            $showpic = "/static/demo/filetype/{$type}.png";
        }

        $imgUrl = '/uploads/' . date('Ymd') . '/'. $info->getFilename();        

        \Util::echoJson('上传成功', true, ['show' => $showpic, 'path' => $imgUrl]);
    }

    private function fileShow($ext)
    {
        $showpic = "/static/demo/filetype/file_psd.png";
        switch($ext)
        {
            case 'txt':
                $showpic = "/static/demo/filetype/file_txt.png";
                break;
            case 'pdf':
                $showpic = "/static/demo/filetype/file_pdf.png";
                break;
            case 'zip':
                $showpic = "/static/demo/filetype/file_zip.png";
                break;
            case 'doc':
                $showpic = "/static/demo/filetype/file_doc.png";
                break;
            case 'docx':
                $showpic = "/static/demo/filetype/file_doc.png";
                break;
            case 'ppt':
                $showpic = "/static/demo/filetype/file_ppt.png";
                break;
            case 'pptx':
                $showpic = "/static/demo/filetype/file_ppt.png";
                break;
            case 'xls':
                $showpic = "/static/demo/filetype/file_xls.png";
                break;
            case 'xlsx':
                $showpic = "/static/demo/filetype/file_xls.png";
                break;
            default:
                break;
        }
       return $showpic;
    }


}