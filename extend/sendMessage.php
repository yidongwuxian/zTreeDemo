<?php

use app\home\model\Qychat as QychatModel;

class sendMessage{
	/**
     * 发送文本消息
     * @param  integer  $qychatId 企业号ID
     * @param  mixed    $toUser   string or array
     * @param  mixed    $toDepart string or array
     * @param  mixed    $toTag    string or array
     * @param  integer  $agentId  应用id
     * @param  string   $content  内容
     * @param  integer  $safe     表示是否是保密消息，0表示否，1表示是，默认0
     * @return array   ['result' => true/false, 'msg' => '描述']
     */
    public static function sendText($qychatId, $toUser, $toDepart, $toTag, $agentId, $content, $safe = 0)
    {
    	$qychatId = intval($qychatId);

        $content = trim($content);

        if(! $qychatId || ! $toUser && ! $toDepart && ! $toTag || ! $agentId || ! $content)
        {
            return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qychatInfo = QychatModel::get(['id' => $qychatId]);
        if (! $qychatInfo)
        {
        	return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qyCorpInfo = [
        	'appid' 	=> $qychatInfo['corp_id'],
            'appsecret' => $qychatInfo['corp_secret'],
        ];

        if (is_array($toUser))
        {
            $toUser = implode('|', $toUser);            
        }
        
        $toDepart = implode('|', $toDepart);
        $toTag    = implode('|', $toTag);

        $data   = [
            'touser'    => $toUser,
            "toparty"   => $toDepart,
            "totag"     => $toTag,
            "safe"      => $safe,
            "agentid"   => $agentId,
            "msgtype"   => "text",
            "text"      => ['content' => $content]
        ];

        $qychatObj = new \Qychat($qyCorpInfo);

        $result = $qychatObj->sendMessage($data);

        if (! $result || $result['errcode'] != 0)
        {
            return ['result' => false, 'msg' => '消息发送失败'];
        }

        return ['result' => true, 'msg' => '消息发送成功'];
    }

    /**
     * 发送图片image、声音voice、文件file消息
     * @param  mixed    $toUser   string or array
     * @param  mixed    $toDepart string or array
     * @param  mixed    $toTag    string or array
     * @param  integer  $agentId  应用id
     * @param  string   $filePath 文件路径
     * @param  string   $type     文件类型 ['image', 'voice', 'file']
     * @param  integer  $safe     表示是否是保密消息，0表示否，1表示是，默认0
     * @return array  ['result' => true/false, 'msg' => '描述']
     */
    public static function sendMediaMsg($qychatId, $toUser, $toDepart, $toTag, $agentId, $filePath, $fileType = 'image', $safe = 0)
    {
    	$qychatId = intval($qychatId);
        $fileType = trim($fileType);

        if(! $qychatId || ! $toUser && ! $toDepart && ! $toTag || ! $agentId || ! $filePath || ! $fileType)
        {
            return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qychatInfo = QychatModel::get(['id' => $qychatId]);
        if (! $qychatInfo)
        {
        	return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qyCorpInfo = [
        	'appid' 	=> $qychatInfo['corp_id'],
            'appsecret' => $qychatInfo['corp_secret'],
        ];

        if (is_array($toUser))
        {
            $toUser = implode('|', $toUser);            
        }
        
        $toDepart = implode('|', $toDepart);
        $toTag    = implode('|', $toTag);

        $data   = [
            'touser'    => $toUser,
            "toparty"   => $toDepart,
            "totag"     => $toTag,
            "safe"      => $safe,
            "agentid"   => $agentId,
            "msgtype"   => $fileType,
        ];

        $qychatObj = new \Qychat($qyCorpInfo);

        if (is_file($filePath))
        {
            // 文件类型：image=>2MB 支持JPG,PNG格式；voice=>2MB 播放长度不超过60s 支持AMR格式；video=>10MB 支持MP4格式；file=>20MB
            $media  = $qychatObj->uploadMedia(['media' => $filePath], $fileType);

            if (! $media || ! isset($media['media_id']))
            {
                return ['result' => false, 'msg' => $qychatObj->errCode . $qychatObj->errMsg];
            } 

            $data[$fileType] = [
                "media_id" => $media['media_id']
            ];
        }

        $result = $qychatObj->sendMessage($data);

        if (! $result || $result['errcode'] != 0)
        {
            return ['result' => false, 'msg' => '消息发送失败'];
        }

        return ['result' => true, 'msg' => '消息发送成功'];
    }

    /**
     * 发送视频消息
     * @param  mixed    $toUser   string or array
     * @param  mixed    $toDepart string or array
     * @param  mixed    $toTag    string or array
     * @param  integer  $agentId  应用id
     * @param  string   $filePath 文件路径
     * @param  string   $title    标题
     * @param  string   $desc     描述
     * @param  integer  $safe     表示是否是保密消息，0表示否，1表示是，默认0
     * @return array    ['result' => true/false, 'msg' => '描述']
     */
    public static function sendVideo($qychatId, $toUser, $toDepart, $toTag, $agentId, $filePath, $title = '', $desc = '', $safe = 0)
    {
    	$qychatId = intval($qychatId);
        $title = trim($title);
        $desc  = trim($desc);

        if(! $qychatId || ! $toUser && ! $toDepart && ! $toTag || ! $agentId || ! $filePath)
        {
            return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qychatInfo = QychatModel::get(['id' => $qychatId]);
        if (! $qychatInfo)
        {
        	return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qyCorpInfo = [
        	'appid' 	=> $qychatInfo['corp_id'],
            'appsecret' => $qychatInfo['corp_secret'],
        ];

        if (is_array($toUser))
        {
            $toUser = implode('|', $toUser);            
        }
        
        $toDepart = implode('|', $toDepart);
        $toTag    = implode('|', $toTag);

        $data   = [
            'touser'    => $toUser,
            "toparty"   => $toDepart,
            "totag"     => $toTag,
            "safe"      => $safe,
            "agentid"   => $agentId,
            "msgtype"   => 'video',
            "safe"      => $safe,
            'video'     => [
                'title'         => $title,
                'description'   => $desc,
            ]
        ];

        $qychatObj = new \Qychat($qyCorpInfo);

        if (is_file($filePath))
        {
            $media  = $qychatObj->uploadMedia(['media' => $filePath], 'video');

            if (! $media || ! isset($media['media_id']))
            {
                return ['result' => false, 'msg' => $qychatObj->errCode . $qychatObj->errMsg];
            } 

            $data['video'] = [
                "media_id" => $media['media_id']
            ];
        }

        $result = $qychatObj->sendMessage($data);

        if (! $result || $result['errcode'] != 0)
        {
            return ['result' => false, 'msg' => '消息发送失败'];
        }

        return ['result' => true, 'msg' => '消息发送成功'];
    }

    /**
     * 发送图文消息
     * @param  mixed    $toUser   string or array
     * @param  mixed    $toDepart string or array
     * @param  mixed    $toTag    string or array
     * @param  integer  $agentId  应用id
     * @param  array    $articles 消息
     * $articles：[[ "title" => "Title", //标题
     *               "description" => "Description", //描述
     *               "url" => "URL",              //点击后跳转的链接
     *               "picurl" => "PIC_URL"       //图文消息的图片链接,支持JPG、PNG格式，
     *              ],
     *              ...
     *              ]
     * @return array    ['result' => true/false, 'msg' => '描述']
     */
    public static function sendNews($qychatId, $toUser, $toDepart, $toTag, $agentId, array $articles)
    {
    	$qychatId = intval($qychatId);

        if(! $qychatId || ! $toUser && ! $toDepart && ! $toTag || ! $agentId || ! $articles)
        {
            return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qychatInfo = QychatModel::get(['id' => $qychatId]);
        if (! $qychatInfo)
        {
        	return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qyCorpInfo = [
        	'appid' 	=> $qychatInfo['corp_id'],
            'appsecret' => $qychatInfo['corp_secret'],
        ];

        if (is_array($toUser))
        {
            $toUser = implode('|', $toUser);            
        }
        
        $toDepart = implode('|', $toDepart);
        $toTag    = implode('|', $toTag);

        $data   = [
            'touser'    => $toUser,
            "toparty"   => $toDepart,
            "totag"     => $toTag,
            "safe"      => $safe,
            "agentid"   => $agentId,
            "msgtype"   => 'news',
            "news"      => ['articles' => $articles],
        ];

        $qychatObj = new \Qychat($qyCorpInfo);

        $result = $qychatObj->sendMessage($data);

        if (! $result || $result['errcode'] != 0)
        {
            \Util::echoJson("消息发送失败" . $qychatObj->errMsg);
        }

        \Util::echoJson("消息发送成功", true);
    }

    /**
     * mpnews消息与news消息类似，不同的是图文消息内容存储在微信后台，并且支持保密选项。每个应用每天最多可以发送100次
     * 发送图文消息
     * @param  mixed    $toUser   string or array
     * @param  mixed    $toDepart string or array
     * @param  mixed    $toTag    string or array
     * @param  integer  $agentId  应用id
     * @param  array    $articles 消息
     * $articles：[[    "title"          => "Title",     //图文消息的标题
     *                   "thumb_media_id" => "id",       //图文消息缩略图的media_id  注意素材需要上传到永久素材
     *                   "author"         => "Author",    //图文消息的作者(可空)
     *                   "content_source_url" => "URL",  //图文消息点击“阅读原文”之后的页面链接(可空)
     *                   "content"        => "Content",   //图文消息的内容，支持html标签
     *                   "digest"         => "Digest description",   //图文消息的描述
     *                   "show_cover_pic" => "0",         //是否显示封面，1为显示，0为不显示(可空)
     *              ],
     *              ...
     *              ]
     * @return array    ['result' => true/false, 'msg' => '描述']
     */
    public static function sendMpNews($qychatId, $toUser, $toDepart, $toTag, $agentId, array $articles, $safe = 0)
    {
    	$qychatId = intval($qychatId);

        if(! $qychatId || ! $toUser && ! $toDepart && ! $toTag || ! $agentId || ! $articles)
        {
            return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qychatInfo = QychatModel::get(['id' => $qychatId]);
        if (! $qychatInfo)
        {
        	return ['result' => false, 'msg' => '请求参数错误'];
        }

        $qyCorpInfo = [
        	'appid' 	=> $qychatInfo['corp_id'],
            'appsecret' => $qychatInfo['corp_secret'],
        ];

        if (is_array($toUser))
        {
            $toUser = implode('|', $toUser);            
        }
        
        $toDepart = implode('|', $toDepart);
        $toTag    = implode('|', $toTag);

        $data   = [
            'touser'    => $toUser,
            "toparty"   => $toDepart,
            "totag"     => $toTag,
            "safe"      => $safe,
            "agentid"   => $agentId,
            "msgtype"   => 'mpnews',
            "safe"      => $safe,
            "mpnews"    => ['articles' => $articles],
        ];

        $qychatObj = new \Qychat($qyCorpInfo);

        $result = $qychatObj->sendMessage($data);

        if (! $result || $result['errcode'] != 0)
        {
            \Util::echoJson("消息发送失败" . $qychatObj->errMsg);
        }

        \Util::echoJson("消息发送成功", true);
    }

}
