<?php
/**
 * 文件的简短描述：发送应用消息
 * 
 * LICENSE:
 * @author lijin
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\home\controller;

use think\Request;

use app\home\model\QychatApp as QyAppModel;
use app\home\model\Department as DepartmentModel;

class Message extends Base
{
    public function __construct()
    {
        parent::__construct();
        if(empty($this->isSuper))
        {
            $this->error('权限错误');
        }
        
        $request = Request::instance();
        $this->qychatId = 7;
        $this->assign('class', 'message');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());
    }

    // 进入消息发送界面
    public function index()
    {
        // 应用列表
        $qyAppList = QyAppModel::all(['qychat_id' => $this->qychatId, 'status' => 1]);

        // 部门
        $departObj = new DepartmentModel();
        $departs   = $departObj->getDepartmentTreeList($this->qychatId);
        $tree = new \Tree();
        $tree->tree($departs);
        $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $departList = $tree->getTree(0, $str, 0);

        // 标签
        $tagList = [];


        $this->assign('appList', $qyAppList);
        $this->assign('departList', $departList);
        $this->assign('tagList', $tagList);

        return $this->fetch();
    }


    // 消息发送
    public function send()
    {
        $request    = Request::instance();
        $agentId    = intval($request->post('agentId')); //应用id
        $toDepart   = $request->post('depart/a');        //部门
        $toTag      = $request->post('tag/a');           //标签
        $originUser = $request->post('users');         //添加过的成员名单
        $selectUser = $request->post('userName');       //修改删除的后成员姓名
        $safe       = $request->post('safe');            //是否为保密消息，对于news无效
        $msgType    = trim($request->post('type'));      //根据信息类型，选择下面对应的信息结构体

        $content    = trim($request->post('content'));    // 正文内容
        $title      = trim($request->post('title'));      // 标题
        $filePath   = trim($request->post('filePath'));   // 文件路径
        $author     = trim($request->post('author'));     // 作者
        $url        = trim($request->post('url'));        // 链接地址
        $desc       = trim($request->post('desc'));       // 简介

        $msgTypes   = ['text', 'image', 'voice', 'video', 'file', 'news'];

        if (! in_array($msgType, $msgTypes) || ! $agentId || ! $toDepart && ! $toTag && ! $originUser)
        {
            \Util::echoJson("请求参数错误");
        }

        $toUser = [];
        if ($originUser)
        {
            $originUser = array_filter(json_decode($originUser, true));
            foreach ($originUser as $key => $value)
            {
                $toUser[$value['name']] = $value['val'];
            }
        }

        if ($selectUser)
        {
            $selectUser = explode(', ', $selectUser);
            foreach ($toUser as $key => $value)
            {
                if (! in_array($key, $selectUser))
                {
                    unset($toUser[$key]);
                }
            }
        }
        $toUser = array_values($toUser);

        switch ($msgType)
        {
            case 'text':
                $result = \sendMessage::sendText($this->qychatId, $toUser, $toDepart, $toTag, $agentId, $content, $safe);
                break;
            case 'news':
                
                $articles   = [
                    "title"              => $title,      //图文消息的标题
                    "thumb_media_id"     => "id",        //图文消息缩略图的media_id  注意素材需要上传到永久素材
                    "author"             => $author,     //图文消息的作者(可空)
                    "content_source_url" => $url,        //图文消息点击“阅读原文”之后的页面链接(可空)
                    "content"            => $content,    //图文消息的内容，支持html标签
                    "digest"             => $desc,       //图文消息的描述
                    "show_cover_pic"     => "0",         //是否显示封面，1为显示，0为不显示(可空)
                ];
                $result = \sendMessage::sendMpNews($this->qychatId, $toUser, $toDepart, $toTag, $agentId, $articles, $safe);
                break;
            case 'video':
                $result = \sendMessage::sendVideo($qychatId, $toUser, $toDepart, $toTag, $agentId, $filePath, $title, $content);
                break;
            default:
                $result = \sendMessage::sendMediaMsg($this->qychatId, $toUser, $toDepart, $toTag, $agentId, $filePath, $$msgTypes, $safe);
                break;
        }

        if (! $result || ! isset($result['result']) || ! $result['result'])
        {
            $msg = isset($result['msg']) ? $result['msg'] : '消息发送失败';
            \Util::echoJson($msg);
        }
        
        \Util::echoJson($result['msg']);
       
    }



    public function test()
    {

    }




 

}
