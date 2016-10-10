<?php
/**
 * 文件的简短描述：默认控制器文件
 *
 * 文件的详细描述：默认控制器文件，仪表板
 *
 * LICENSE:
 * @author wangzhen 2016/7/27
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Session;

/**
 * 类名：Index
 *
 * 类的详细描述：默认控制器类
 *
 * LICENSE:
 * @author wangzhen 2016/7/27
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
 **/
class Index extends Base
{
    public function __construct(){
        parent::__construct();
        $this->assign('title', '主页');
        //导航菜单contact模块
        $this->assign('class','index_manage');
        //导航菜单sub_class模块
        $this->assign('subClass','index_list');
    }

    public function index(){
        return $this->fetch();
    }

    public function getUserInfo(){
        $userInfo = db('user')->where('id',$this->userId)->find();
        \Util::echoJson($userInfo['user_name'],true);
    }

    //退出登录
    public function logout(){
        if (!empty($_SESSION)) {
            $_SESSION = [];
        }
        session_unset();
        session_destroy();
        $this->redirect('home/login/index');
    }

}
