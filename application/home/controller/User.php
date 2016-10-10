<?php
namespace app\home\controller;

use app\home\model\Qychat;
use think\Request;
use app\home\model\User as UserModel;

/**
 * 类名：User
 *
 * 类的详细描述：后台企业用户控制器类
 *
 * LICENSE:
 * @author 2016/8/19
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
 **/
class User extends Base
{
    public function __construct(){
        parent::__construct();
        if(empty($this->isSuper))
            $this->error('权限错误');
        $this->assign('class','user_manage');
        $this->assign('subClass','user_list');
    }

    /**
     * 后台企业用户管理列表
     */
    public function index(){
        $list = UserModel::all(function($query){
            $query->order('id', 'desc');
        });
        $qyModel = new Qychat();
        foreach( $list as $key=>$value ){
            $qyInfo = $qyModel->getQychatByIds( unserialize($value->qy_ids) );
            if(!empty($qyInfo)){
                foreach( $qyInfo as $v ){
                    $qyName[$key][] = $v->name;
                }
                $value->qy_name = implode(',',$qyName[$key]);
            }else{
                $value->qy_name = '';
            }
        }
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 添加企业用户
     * 密码由6位以上密码+随机生成的8位密钥 MD5
     */
    public function add(){
        $request = Request::instance();
        if($request->isAjax()){
            //接收POST参数
            $data = array(
                'user_name'   => trim($request->post('user_name')),
                'password'    => trim($request->post('password')),
                'password2'   => trim($request->post('password2')),
                'email'       => trim($request->post('email')),
                'qy_ids'      => serialize( explode(',',$request->post('qy_ids')) ),
                'nick_name'   => trim($request->post('nick_name')),
                'status'      => trim($request->post('status')),
                'create_time' => time(),
            );
            //校验参数
            $checkRet = $this->validate($data,'User');
            if(true !== $checkRet){
                \Util::echoJson($checkRet);
            }
            $data['verify_key'] = \Util::random(8);
            $encryptData = \Util::encrypt($data['password'],$data['verify_key']);
            $data['password'] = $encryptData;

            $userModel = model('User');
            $result = $userModel->checkRegister($data);
            if($result !== true){
                \Util::echoJson($result);
            }
            //校验通过，保存数据
            $userModel->data($data);
            if ($userModel->allowField(true)->save()) {
                \Util::echoJson('添加成功！',true);
            } else {
                \Util::echoJson($userModel->getError());
            }
        }else{
            $qyChatData = db('qychat')->where('status',1)->select();
            $this->assign('qyChatData',$qyChatData);
            return $this->fetch();
        }
    }

    /**
     * 修改企业用户
     * 如果要修改密码，先验证新密码格式，再生成8位新密钥+新密码 MD5
     * @param $id
     * @return bool|string
     */
    public function edit($id){
        $userModel = model('User');
        $request = Request::instance();
        if($request->isAjax()){
            //接收POST参数
            $data = array(
                'email'       => trim($request->post('email')),
                'qy_ids'      => trim($request->post('qy_ids')),
                'nick_name'   => trim($request->post('nick_name')),
                'status'      => trim($request->post('status')),
                'update_time' => time(),
            );
            //校验参数
            $checkRet = $this->validate($data,'User.edit');
            if(true !== $checkRet){
                \Util::echoJson($checkRet);
            }
            //如果要修改密码
            if(($request->post('password')) != null){
                $password = $request->post('password');
                $password2 = $request->post('password2');
                if(strlen($password) < 6)
                    \Util::echoJson('密码至少6位');
                if($password != $password2)
                    \Util::echoJson('两次密码不一致');
                //生成新密码
                $data['verify_key'] = \Util::random(8);
                $encryptData = \Util::encrypt($password,$data['verify_key']);
                $data['password'] = $encryptData;
            }
            $data['qy_ids'] = serialize( explode(',',$request->post('qy_ids')) );   //将企业微信号序列化
            //校验通过，保存数据
            $id = $request->post('id');
            if ($userModel->save($data,['id' => $id])) {
                \Util::echoJson('编辑成功！',true);
            } else {
                \Util::echoJson($userModel->getError());
            }
        }else{
            //查询数据
            $data = $userModel->get($id);
            if(!$data){
                $this->error('页面不存在！');
            }
            $qyChatData = db('qychat')->where('status',1)->select();
            $hasQyChatIds = (!empty($data->qy_ids)) ? unserialize($data->qy_ids) : array();
            $this->assign('data',$data);
            $this->assign('qyChatData',$qyChatData);
            $this->assign('hasQyChatIds',$hasQyChatIds);
            return $this->fetch();
        }
    }

    public function delete($id){
        $request = Request::instance();
        if($request->isAjax()){
            $userModel = UserModel::get($id);
            if($userModel->delete()){
                \Util::echoJson('删除成功！',true);
            }else{
                \Util::echoJson($userModel->getError());
            }
        }
    }

}
