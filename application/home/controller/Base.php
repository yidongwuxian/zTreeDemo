<?php
/**
 * 文件的简短描述：业务基础类文件
 *
 * 文件的详细描述：业务基础类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/1
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\controller;

use think\Controller;

/**
 * 类名：Base
 *
 * 类的详细描述：Base类，业务的基类
 *
 * LICENSE:
 * @author wangzhen 2016/8/1
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
 **/
class Base extends Controller{

    protected $userName;
    protected $userId;
    protected $isSuper;
    protected $qychatId;
    protected $corpInfo;
    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        $this->checkLogin();
        $this->assign('isSuper', $this->isSuper);
        $this->assign('userName', $this->userName);
    }

    public function index(){

    }

    //判断当前是否有用户登录
    public function checkLogin(){
        if(!session('user_id') && !session('qychat_id')){
            $this->error('请先登录','/home/login/index');
        }elseif(session('user_id') && !session('qychat_id')){
            $this->error('请选择要管理的企业微信号','/home/login/selectQy');
        }else{
            $this->userId = session('user_id');
            $this->isSuper = session('is_super');
            $this->userName = session('user_name');
            $this->qychatId = session('qychat_id');
            $this->corpInfo = array(
                'appid' => session('corp_id'),
                'appsecret' => session('corp_secret')
                //'appid' => 'wxb247be9eadad8e37',
                //'appsecret' => '19QysFmakTNzc33EbGU8BoHeHZ3G-UnXnqaVMjC-AcfLbCQ4OCpiw0-ShxmxY1LS'
            );
            return true;
        }


    }

}