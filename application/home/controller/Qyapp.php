<?php
/**
 * 文件的简短描述：应用安装
 * 
 * LICENSE:
 * @author lijin
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\home\controller;

use think\Request;
use think\Cache;

use app\home\model\QyApps as AppsModel;
use app\home\model\QyAppMenu as AppMenuModel;
use app\home\model\QychatApp as QyAppModel;

class Qyapp extends Base
{

    public function __construct()
    {
        parent::__construct();

        $request = Request::instance();

        $this->assign('class', 'app');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());
    }

    // 应用入口管理
    public function index()
    {
    	$qyAppObj = new QyAppModel();
        $appObj   = new AppsModel();
    	$appList  = $appObj->where(['status' => 1])->order('sort desc')->select();
    	$qyApps   = $qyAppObj->where(['qychat_id' => $this->qychatId, 'status' => 1])->field('app_id')->select();

    	$qyAppIds = [];
    	foreach ($qyApps as $key => $value) 
    	{
    		$qyAppIds[] = $value['app_id'];
    	}

    	$this->assign('appList', $appList);
    	$this->assign('qyAppIds', $qyAppIds);

    	return $this->fetch();
    }

    // 应用安装
    public function install()
    {
        $request    = Request::instance();

        $appId      = intval($request->post('appId'));
        $agentId    = intval($request->post('agentId'));

        if (! $appId || ! $agentId)
        {
            \Util::echoJson('请求参数错误');
        }

        // 安装状态变更后设置微信菜单
        $callBack   = Cache::get($this->qychatId . 'appInstallResult'. $appId);

        if(! $callBack)
        {
            \Util::echoJson('应用未成功安装，请检查微信端设置');
        }
        // 设置菜单
        $menuList = AppMenuModel::all(['app_id' => $appId]);

        foreach ($menuList as $key => &$value)
        {
            $value = $value->toArray();
            unset($value['sort']);
            unset($value['level']);
            unset($value['app_id']);
        }

        if ($menuList)
        {
            $menuList = QychatLib::madeMenuList($menuList);

            $qychat_api = new \Qychat($this->corpInfo);
            
            $res = $qychat_api->createMenu($menus, $agentId);

            if(! $res)
            {
                \Util::echoJson($qychat_api->errMsg);
            }
        }

        $qyAppObj = new QyAppModel();
        $row = [
            'update_time' => time(),
            'status'      => 1,
        ];
        $res = $qyAppObj->save($row, ['qychat_id' => $this->qychatId, 'app_id' => $appId]);

        if(! $res)
        {
            \Util::echoJson("应用安装状态同步失败");
        }
    
        \Util::echoJson('操作成功', true);
    }


    // 设置应用详情
    public function setApp()
    {
        $request    = Request::instance();
        $appId      = intval($request->post('appId'));
        $agentId    = intval($request->post('agentId'));

        if (! $appId || ! $agentId)
        {
            \Util::echoJson('请求参数错误');
        }
        
        $appInfo  = AppsModel::get(['id' => $appId]);

        if(! $appInfo)
        {
            \Util::echoJson('应用不存在');
        }

        $agentApp = QyAppModel::get(['qychat_id' => $this->qychatId, 'app_id' => $appId]);
        if ($agentApp)
        {
            if ($agentApp['status'] == 1)
            {
                \Util::echoJson('该微信应用ID对应的应用已成功安装！');
            }

            $token  = $agentApp['token'];
            $aeskey = $agentApp['aeskey'];
            $id     = $agentApp['id'];
        }

        $token  = isset($token) ? $token : md5($this->qychatId . $appId . 'token');
        $aeskey = isset($aeskey) ? $aeskey : \QychatLib::appAeskey(43);

        $row = [
            'agent_id'          => $agentId,
            'app_custom_name'   => $appInfo['app_name'],
            'app_custom_logo'   => $appInfo['app_logo'],
            'app_custom_desc'   => $appInfo['app_desc'],
            'token'             => $token,
            'aeskey'            => $aeskey,
        ];

        $qyAppObj = new QyAppModel();
        if (isset($id))
        {
            $row['update_time']  = time();
            $res = $qyAppObj->save($row, ['id' => $id]);
        }
        else
        {
            $tmp = [
                'app_id'       => $appId,
                'qychat_id'    => $this->qychatId,
                'create_time'  => time(),
                'update_time'  => time(),
            ];
            
            $row = array_merge($row, $tmp);

            $res = $qyAppObj->save($row);
        }

        if ($res === false)
        {
            \Util::echoJson('操作失败，请稍后重试');
        }   

        $cacheVal = [
            'appid'          => $this->corpInfo['appid'], //企业corp_id
            'encodingaeskey' => $aeskey, //应用key
            'token'          => $token, // 应用token
        ];

        Cache::set($this->qychatId . 'appInstall'. $appId, json_encode($cacheVal), 3600);

        $url   = "http://" . $_SERVER['HTTP_HOST'] . "/app/weixin/callback?qyid={$this->qychatId}&appid={$appId}";

        \Util::echoJson('操作成功', true, ['url' => $url, 'token' => $token, 'aeskey' => $aeskey]);
    }



}
