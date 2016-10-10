<?php
/**
 * 文件的简短描述：企业微信号类
 *
 * 文件的详细描述：企业微信号类，用了管理企业微信号信息，配置管理员等操作
 *
 * LICENSE:
 * @author wangzhen 2016/8/2
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\controller;

use think\Request;
use app\home\model\Qychat as QychatModel;

class Qychat extends Base {

    protected $qychat;

    public function __construct(){
        parent::__construct();
        if(empty($this->isSuper))
            $this->error('权限错误');
        //导航菜单contact模块
        $this->assign('class','qychat_manage');
        //导航菜单sub_class模块
        $this->assign('subClass','qychat_list');
        //实例化模型类
        $this->qychat = new QychatModel();
    }

    public function index(){
        $list = QychatModel::all();
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function add(){
        $request = Request::instance();
        if($request->isAjax()){
            //接收POST参数
            $data = [
                'chat_str'      => $request->post('chat_str'),
                'name'          => $request->post('corp_name'),
                'corp_id'       => $request->post('corp_id'),
                'corp_secret'   => $request->post('corp_secret'),
                'status'        => $request->post('status'),
            ];
            //校验参数
            $result = $this->validate($data,'Qychat');
            if(true !== $result){
                \Util::echoJson($result);
            }
            //校验通过，保存数据
            $this->qychat->data($data);
            if ($this->qychat->save()) {
                \Util::echoJson('创建成功！',true);
            } else {
                \Util::echoJson($this->qychat->getError());
            }
        }else{
            return $this->fetch();
        }
        return false;
    }

    public function edit($id  = 0){
        $request = Request::instance();
        if($request->isAjax()){
            //接收POST参数
            $data = [
                'chat_str'      => $request->post('chat_str'),
                'name'          => $request->post('corp_name'),
                'corp_id'       => $request->post('corp_id'),
                'corp_secret'   => $request->post('corp_secret'),
                'status'        => $request->post('status'),
            ];
            //校验参数
            $result = $this->validate($data,'Qychat');
            if(true !== $result){
                \Util::echoJson($result);
            }
            //校验通过，保存数据
            $id = $request->post('id');
            $qychat = $this->qychat;
            if ($qychat->save($data,['id' => $id])) {
                \Util::echoJson('编辑成功！',true);
            } else {
                \Util::echoJson($qychat->getError());
            }
        }else{
            //查询数据
            $qychat = $this->qychat->get($id);
            if( ! $qychat){
                $this->error('页面不存在！');
            }
            $this->assign('qychat',$qychat);
            return $this->fetch();
        }
        return false;
    }

    public function delete($id  = 0){
        $request = Request::instance();
        if($request->isAjax()){
            $qychat = $this->qychat->get($id);
            if($qychat->delete()){
                \Util::echoJson('删除成功！',true);
            }else{
                \Util::echoJson($qychat->getError());
            }
        }
    }

}