<?php
/**
 * 类名：Base
 *
 * 类的详细描述：Base类，app前台业务的基类
 *
 * LICENSE:
 * @author zhangpeng 2016/9/7
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\app\controller;

use app\home\model\Qychat as QychatModel;
use app\home\model\Member as MemberModel;
use think\Controller;
use think\Request;
use think\Session;

class Base extends Controller{

    protected $wxapi;
    protected $codeId;
    protected $userId;
    protected $userName;
    protected $corpInfo;
    protected $appQychatId;     //企业号表主键Id
    protected $state = 888;

    public function __construct(){
        parent::__construct();
        //如果已经获取过当前用户信息
        if(session('app_user_id') && session('app_qychat_id'))
        {
            $this->userId = session('app_user_id');
            $this->userName = session('app_user_name');
            $this->appQychatId = session('app_qychat_id');

        }else{
            $request = Request::instance();
            //如果是已授权并跳转过来
            if($request->param('code') && session('app_appid') && session('app_appsecret')) {
                $this->codeId = $request->param('code');
                $this->corpInfo = array(
                    'appid' => session('app_appid'),
                    'appsecret' => session('app_appsecret')
                );
                $this->wxapi = new \Qychat($this->corpInfo);
                //用过 access_token + code 获取当前微信用户id，access_token已经在new Qychat的时候生成了。
                $this->userId = $this->wxapi->getUserId($this->codeId);
                $userData = MemberModel::where('qychat_id', session('app_qychat_id'))
                    ->where('userid', $this->userId)
                    ->find();
                if( !empty($userData)){
                    Session::set('app_user_id', $this->userId);     //当前微信用户userid
                    Session::set('app_user_name', $userData->name);     //当前微信用户username
                }else{
                    if( !empty($_SESSION))
                        $_SESSION = [];
                    session_unset();
                    session_destroy();
                    die('您不在企业通讯录中，等联系管理员');
                }

            }elseif($_GET['qychat_id'] && $_GET['callback']){    //菜单地址必须带qychat_id和回调地址,第一次访问走这里
                //var_dump($_GET['qychat_id'] , $_GET['callback']);die;
                $this->appQychatId = $_GET['qychat_id'];
                $qychatInfo = QychatModel::get($this->appQychatId);
                //如果企业号存在
                if( !empty($qychatInfo))
                {
                    $this->corpInfo = array(
                        'appid' => $qychatInfo->corp_id,
                        'appsecret' => $qychatInfo->corp_secret
                    );
                    Session::set('app_appid', $qychatInfo->corp_id);     //当前企业号corp_id
                    Session::set('app_appsecret', $qychatInfo->corp_secret);     //当前企业号corp_secret
                    Session::set('app_qychat_id', $this->appQychatId);     //当前企业号id
                    // Session::set('app_user_id', 'lijin');     //开发环境需要  线上删除
                    // Session::set('app_user_name', '李金');     //开发环境需要  线上删除
                    $this->wxapi = new \Qychat($this->corpInfo);
                    $redirectUri = $this->wxapi->getOauthRedirect($_GET['callback'], $this->state);    //跳转到获取code连接
                    //var_dump($redirectUri);die;
                    //Header("HTTP/1.1 303 See Other");
                    //Header("Location: $redirectUri");
                    exit;
                    //var_dump($redirectUri);die;
                }else{
                    die('企业号不存在');
                }

            }else{
                die('参数错误');
            }

        }

    }

    public function index(){

    }


}