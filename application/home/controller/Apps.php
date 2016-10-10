<?php
/**
 * 文件的简短描述：应用管理
 * 
 * LICENSE:
 * @author lijin
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\home\controller;

use think\Request;

use app\home\model\QyApps as AppsModel;
use app\home\model\QyAppMenu as AppMenuModel;

class Apps extends Base
{
    public function __construct()
    {
        parent::__construct();
        if(empty($this->isSuper))
        {
            $this->error('权限错误');
        }
        
        $request = Request::instance();

        $this->assign('class', 'apps');
        $this->assign('subClass', $request->controller());
        $this->assign('action', $request->action());
    }

    // 应用列表入口
    public function index()
    {
    	$appList = AppsModel::all(['status' => ['neq', -1]]);

    	$this->assign('appList', $appList);

    	return $this->fetch();
    }

    // 添加应用
    public function appAdd($id = 0)
    {
    	$id   = intval($id);
    	$info = [];
    	if ($id)
    	{
    		$info = AppsModel::get(['id' => $id]);

    		if ($info['app_logo'] && file_exists(ROOT_PATH . '/public' . $info['app_logo']))
    		{
    			$info['logo'] = 1;
    		}    			
    	}

    	$this->assign('info', $info);

    	return $this->fetch();
    }

    // 保存应用
    public function appSave()
    {
        $request = Request::instance();
        $id 	 = intval($request->post('id'));
        $data 	 = [
            'app_name'		=> trim($request->post('name')),
            'app_logo'		=> trim($request->post('logo')),
            'app_desc'		=> trim($request->post('desc')),
            'app_controller'=> trim($request->post('controller')),
            'status'		=> $request->post('status'),
            'sort'			=> intval($request->post('sort')),
            'memo'			=> trim($request->post('memo')),
        ];

        $data['status']  = $data['status'] == 'on' ? 1 : 0;
        $data['app_controller'] = strtolower($data['app_controller']);

        if (empty($data) || empty($data['app_name']))
        {
        	\Util::echoJson('请填写相关内容后再提交');
        }

       	$appObj = new AppsModel();
        if ($id)
        {
        	$data = array_filter($data);
            $res  = $appObj->save($data, ['id' => $id]);
        }
        else
        {
        	$data['create_time'] = time();
            $res = $appObj->save($data);
        }

        if ($res === false)
        {
        	\Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }

    // 删除应用
    public function appDelete($id = 0)
    {
    	$id = intval($id);
    	if (! $id)
    	{
    		 \Util::echoJson('请求参数错误');
    	}

    	$appObj = new AppsModel();

    	$data['status'] = -1;

    	$res = $appObj->save($data, ['id' => $id]);

        if ($res === false)
        {
        	\Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }

    // ================================================
	// ===============菜单管理=========================
	
    // 查看应用的菜单列表
    public function menuList($id = 0)
    {
    	$id = intval($id);
    	if (! $id)
    	{
    		$this->error('请求参数错误', 'index');
    	}

    	$appInfo  = AppsModel::get(['id' => $id]);

    	if (! $appInfo)
    	{
    		$this->error('应用不存在', 'index');
    	}

    	$menuList = AppMenuModel::all(['app_id' => $id]);

    	$this->assign('menuList', $menuList);
    	$this->assign('appInfo', $appInfo);

    	return $this->fetch();
    }

    // 添加应用菜单
    public function menuAdd($pid, $id = 0)
    {
    	$pid = intval($pid);
    	$id  = intval($id);
    	if (! $pid)
    	{
    		$this->error('请求参数错误', 'index');
    	}

		$appInfo  = AppsModel::get(['id' => $pid]);

    	if (! $appInfo)
    	{
    		$this->error('应用不存在', 'index');
    	}

    	$info = [];

    	if ($id)
    	{
    		$info  = AppMenuModel::get(['app_id' => $pid, 'id' => $id]);
    	}

    	$menuList  = AppMenuModel::all(['app_id' => $pid]);

    	$this->assign('appInfo', $appInfo);
    	$this->assign('info', $info);
    	$this->assign('menuList', $menuList);

    	return $this->fetch();
    }

    // 保存应用菜单
    public function menuSave()
    {
        $request = Request::instance();
        $id 	 = intval($request->post('id'));
        $appId 	 = intval($request->post('appId'));
        $data 	 = [
        	'app_id' => $appId,
            'pid' 	 => intval($request->post('pid')),
            'name'	 => trim($request->post('name')),
            'type'	 => trim($request->post('type')),
            'code'	 => trim($request->post('code')),
            'sort'	 => intval($request->post('sort')),
            'level'	 => intval($request->post('level')),
        ];

        if (! $appId || empty($data))
        {
        	\Util::echoJson('请填写相关内容后再提交');
        }

       	$appMenuObj = new AppMenuModel();

        if ($id)
        {
            $res = $appMenuObj->save($data, ['id' => $id]);
        }
        else
        {
            $res = $appMenuObj->save($data);
        }

        if ($res === false)
        {
        	\Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }

    // 删除应用菜单
    public function menuDelete($id = 0)
    {
    	$id = intval($id);
    	if (! $id)
    	{
    		\Util::echoJson('请求参数错误');
    	}

    	$res = AppMenuModel::where(['id' => $id])->delete();

        if (! $res)
        {
            \Util::echoJson('操作失败');
        }

        \Util::echoJson('操作成功', true);
    }




}
