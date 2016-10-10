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
namespace app\app\controller;

use think\Controller;
use think\Request;
use think\Cache;

use app\home\model\Qychat as QychatModel;
use app\home\model\QychatApp as QyAppModel;

class Weixin extends Controller
{
	// 微信回调
	public function callBack()
	{
		$request 	= Request::instance();
		$appId 		= intval($request->param('appid'));
		$qychatId 	= intval($request->param('qyid'));
		$echostr 	= $request->param('echostr');

		if (! $appId || !$qychatId || ! isset($echostr))
		{
			exit('param err');
		}

		$qyInfo = Cache::get($qychatId . 'appInstall'. $appId);

		if (! $qyInfo)
		{
			exit('param err');
		}

		$qyInfo = json_decode($qyInfo, true);

		$qychatObj = new \Qychat($qyInfo);
		
		$res = $qychatObj->valid(true);
		
		Cache::set($qychatId . 'appInstallResult'. $appId, $res, 36000);

		echo $res;

		exit;
	}


}