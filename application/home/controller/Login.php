<?php
/**
 * 文件的简短描述：登录类文件
 *
 * 文件的详细描述：后台管理模块，登录类文件
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
use app\home\model\Qychat;

/**
 * 类名：Login
 *
 * 类的详细描述：登录类
 *
 * LICENSE:
 * @author wangzhen 2016/7/27
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
 **/
class Login extends Controller {

    public function _initialize(){
        if(session('user_id') && session('qychat_id'))
            $this->redirect('home/index/index');
    }

    public function index(){
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function login(){
        $request = Request::instance();
        if($request->isAjax()){
            $userModel = model('User');
            $data['user_name'] = trim($this->request->post('user_name'));
            $data['password'] = trim($this->request->post('password'));
            $checkRet = $this->validate($data,'User.login');
            if(true !== $checkRet){
                \Util::echoJson($checkRet);
            }
            $result = $userModel->login($data);
            if($result == false){
                \Util::echoJson('用户名或密码错误');
            }else{
                $userInfo = db('user')->where('id', session('user_id'))->find();
                $hasQyIds = unserialize($userInfo['qy_ids']);   //获取当前用户所管理的企业ids
                //如果登录用户拥有的可管理企业微信号大于1个，跳到选择企业微信号页面
                if(count($hasQyIds) > 1){
                    \Util::echoJson('登录成功！',true,'/home/login/selectQy');
                }else{
                    //如果没有课管理的企业,提示错误信息，删除session
                    if(empty($hasQyIds[0])){
                        if(!empty($_SESSION))
                            $_SESSION = [];
                        session_unset();
                        session_destroy();
                        \Util::echoJson('您没有可管理的企业，请联系客服！');
                    }else{
                        //如果只有一个企业微信号，把企业微信id,appid,secret存到session
                        $qyChat = db('qychat')->where('id', $hasQyIds[0])->find();
                        Session::set('qychat_id', $qyChat['id']);
                        Session::set('corp_id', $qyChat['corp_id']);
                        Session::set('corp_secret', $qyChat['corp_secret']);
                        \Util::echoJson('登录成功！',true,'/home/index/index');
                    }
                }
            }
        }
    }

    public function selectQy(){
        $request = Request::instance();
        if($request->param('qyId')){
            //把企业微信id,appid,secret存到session
            $qyChat = db('qychat')->where('id', $request->param('qyId'))->find();
            Session::set('qychat_id', $qyChat['id']);
            Session::set('corp_id', $qyChat['corp_id']);
            Session::set('corp_secret', $qyChat['corp_secret']);
            $this->redirect('/home/index/index');
        }else{
            $userInfo = db('user')->where('id',session('user_id'))->find();
            $hasQyIds = unserialize($userInfo['qy_ids']);   //获取当前用户所管理的企业ids
            if($hasQyIds){
                $qyModel = new Qychat();
                $list = $qyModel::all($hasQyIds);   //获取企业
                $listPage = ceil(count($list)/3);
                $this->assign('list',$list);
                $this->assign('listPage',$listPage);

                $this->view->engine->layout(false);
                return $this->fetch('selectqy');

            }else{
                $this->error('您没有可管理的企业，请联系客服','/home/login/index');
            }
        }
    }

}