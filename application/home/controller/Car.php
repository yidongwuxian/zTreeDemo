<?php
namespace app\home\controller;

use app\home\model\AppCar as CarModel;
use app\home\model\Member as MemberModel;
use app\home\model\Department as DepartmentModel;
use app\home\model\AppCarApply as CarApplyModel;
use app\home\model\AppCarManage as CarManageModel;
use app\home\model\AppCarProcess as CarProcessModel;
use app\home\model\MemberDepartment as MemberDepartmentModel;
use app\app\model\AppCarApplyReply as AppCarApplyReplyModel;
use think\Request;
use think\Db;

/**
 * 类名：Car
 *
 * 类的详细描述：后台用车申请控制器类
 *
 * LICENSE:
 * @author zhangpeng 2016/8/19
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
class Car extends Base
{
    public function __construct(){
        parent::__construct();
        $this->assign('class','app_manage');
        $this->assign('subClass','app_car');
    }

    /**
     * 后台用车申请列表
     */
    public function index(){
        $this->assign('botClass','app_car_car');
        return $this->fetch();
    }


    //*************************************流程管理****************************************//
    /**
     * 流程管理列表，通过order正排序，mark=1为审核流程，2为派发流程，3为办理流程
     * @return mark
     * @return mixed
     */
    public function process(){
        $request = Request::instance();
        $mark = $request->param('mark') ? $request->param('mark') : 1;
        $list = CarProcessModel::where('qychat_id', $this->qychatId)
                ->where('mark', $mark)
                ->order('order', 'asc')
                ->select();
        //审核流程的设置操作是在配置人员功能中，其他流程的设置操作要在流程管理中
        if($mark > 1){
            //$memberModel = new MemberModel();
            $carManageModel = new CarManageModel();
            foreach($list as $v){
                //根据流程节点id，qychatid，部门id获取car_manage表数据
                $carManageMemberInfo = $carManageModel->getDepartManage($v->id, $this->qychatId, '0');
                //如果已经配置了管理者，取出管理者信息
                if( !empty($carManageMemberInfo)){
                    $v['manage_id'] = $carManageMemberInfo->id;
                    $v['member_id'] = $carManageMemberInfo->member_id;
                    //考虑到办理流程的子节点可以配置多个人员。如果有多个办理人员，在数据库中是用','隔开的
                    $manageName = array();
                    if(!empty($carManageMemberInfo->member_id)){
                        $carManageMemberIds = explode(',', $carManageMemberInfo->member_id);
                        foreach($carManageMemberIds as $vo){
                            $manageName[] = MemberModel::get(['userid' => $vo])->name;
                        }
                        $v['member_name'] = $manageName;
                    }
                }
            }

        }
        $this->assign('botClass','app_car_process');
        $this->assign('list',$list);
        $this->assign('mark',$mark);
        return $this->fetch();
    }

    /**
     * 添加流程子节点，同一标记order须唯一
     * @return mixed
     */
    public function processAdd(){
        $request = Request::instance();
        if($request->isAjax()){
            $data = array(
                'mark'      => $request->post('mark'),
                'name'      => trim($request->post('name')),
                'order'     => trim($request->post('order')),
                'qychat_id' => $this->qychatId,
            );
            //校验参数
            $checkRet = $this->validate($data,'Car.addprocess');
            if(true !== $checkRet){
                \Util::echoJson($checkRet);
            }

            $model = new CarProcessModel();
            //校验当前排序是否存在
            $result = $model->checkOrderIsset($data);
            if($result !== true){
                \Util::echoJson($result);
            }
            $model->data($data);
            if($model->save()){
                \Util::echoJson('添加成功！', true, $request->post('mark'));
            }else{
                \Util::echoJson($model->getError());
            }
        }else{
            $this->assign('botClass','app_car_process');
            return $this->fetch();
        }

    }

    /**
     * 修改流程子节点
     * @param $id
     * @return mixed
     */
    public function processEdit($id){
        $request = Request::instance();
        if($request->isAjax()){
            $data = array(
                'mark'   => $request->post('mark'),
                'name'   => trim($request->post('name')),
                'order'  => trim($request->post('order')),
                'qychat_id'  => $this->qychatId,
            );
            //校验参数
            $checkRet = $this->validate($data,'Car.addprocess');
            if(true !== $checkRet){
                \Util::echoJson($checkRet);
            }

            $model = new CarProcessModel();
            //如果排序id有改动，校验当前排序是否存在
            if( $request->post('old_order_id') != $data['order'] || $request->post('old_mark_id') != $data['mark'] ){
                $result = $model->checkOrderIsset($data);
                if($result !== true){
                    \Util::echoJson($result);
                }
            }
            $id = $request->post('id');
            if($model->save($data, ['id' => $id])){
                \Util::echoJson('修改成功！', true, $request->post('mark'));
            }else{
                \Util::echoJson($model->getError());
            }
        }else{
            $data = CarProcessModel::where('qychat_id', $this->qychatId)
                    ->where('id', $id)
                    ->find();
            if(!$data){
                $this->error('参数错误');
            }
            $this->assign('data', $data);
            $this->assign('botClass', 'app_car_process');
            return $this->fetch();
        }

    }

    /**
     * 删除流程子节点,同时删除car_manage表里面包含该子节点id的数据
     * @param $id
     */
    public function processDel($id){
        $request = Request::instance();
        if($request->isAjax()){
            //开启事务
            Db::startTrans();
            $carManageModel = new CarManageModel();
            $carProcess = CarProcessModel::get($id);
            $delManageRet = $carManageModel->delManageByProceId($id);
            if($carProcess->delete() && $delManageRet){
                Db::commit();
                \Util::echoJson('删除成功！',true);
            }else{
                Db::rollback();
                \Util::echoJson('操作失败，请稍后重试！');
            }
        }
    }


    //*************************************车辆管理****************************************//
    /**
     * 车辆管理/列表
     */
    public function carList(){
        $list = CarModel::all(function($query){
            $query->where('qychat_id', $this->qychatId);
        });
        $this->assign('list',$list);
        $this->assign('botClass','app_car_car');
        return $this->fetch();
    }

    /**
     * 车辆管理/添加
     * @return bool|string
     */
    public function carAdd(){
        $request = Request::instance();
        if($request->isAjax()){
            //接收POST参数
            $data = array(
                'type'    => $request->post('type'),
                'name'    => trim($request->post('name')),
                'firm'    => trim($request->post('firm')),
                'idcard'  => trim($request->post('idcard')),
                'desc'    => $request->post('desc'),
                'status'  => $request->post('status'),
                'qychat_id'  => $this->qychatId,
            );
            //校验参数
            $checkRet = $this->validate($data,'Car.addcar');
            if(true !== $checkRet){
                \Util::echoJson($checkRet);
            }
            //校验通过，保存数据
            $model = new CarModel();
            $model->data($data);
            if ($model->save()) {
                \Util::echoJson('添加成功！',true);
            } else {
                \Util::echoJson($model->getError());
            }
        }else{
            $this->assign('botClass','app_car_car');
            return $this->fetch();
        }
    }

    /**
     * 车辆管理/修改
     * @param $id
     * @return bool|string
     */
    public function carEdit($id){
        $model = new CarModel();
        $request = Request::instance();
        if($request->isAjax()){
            $data = array(
                'type'    => $request->post('type'),
                'name'    => trim($request->post('name')),
                'firm'    => trim($request->post('firm')),
                'idcard'  => trim($request->post('idcard')),
                'desc'    => $request->post('desc'),
                'status'  => $request->post('status'),
            );
            //校验参数
            $checkRet = $this->validate($data,'Car.addcar');
            if(true !== $checkRet){
                \Util::echoJson($checkRet);
            }

            $id = $request->post('id');
            if ($model->save($data, ['id' => $id])) {
                \Util::echoJson('修改成功！',true);
            } else {
                \Util::echoJson($model->getError());
            }
        }else{
            //查询数据
            $data = $model->get($id);
            if(!$data){
                $this->error('页面不存在！');
            }
            $this->assign('data',$data);
            $this->assign('botClass','app_car_car');
            return $this->fetch();
        }
    }

    /**
     * 删除车辆
     * @param $id
     * @throws \think\Exception
     */
    public function carDel($id){
        $request = Request::instance();
        if($request->isAjax()){
            $model = new CarModel();
            $qychat = $model->get($id);
            if($qychat->delete()){
                \Util::echoJson('删除成功！',true);
            }else{
                \Util::echoJson($qychat->getError());
            }
        }
    }

    //*************************************部门管理者****************************************//

    /**
     * 配置人员页面
     * @return view
     */
    public function manage(){
        $this->assign('botClass','app_car_manage');
        return $this->fetch();
    }

    /**
     * 动态获取部门流程人员配置信息（供DataTables调用）/home/car/manage
     * @param int $departmentid
     * @return array
     */
    public function getDepartManageList($departmentid = 1){
        $request = Request::instance();
        if($request->isAjax()) {
            $carManageModel = new CarManageModel();
            $processList = CarProcessModel::where('qychat_id', $this->qychatId)
                        ->where('mark', '1')
                        ->order('order', 'asc')
                        ->select();
            $list = array();
            if( !empty($processList) ){
                foreach ($processList as $vo) {
                    //根据流程节点id，qychatid，部门id获取car_manage表数据
                    $departManageInfo = $carManageModel->getDepartManage($vo->id, $this->qychatId, $departmentid);
                    $list[] = [
                        $vo->order,
                        $vo->name,
                        ( !empty($departManageInfo['member_id']) ) ? MemberModel::get(['userid' => $departManageInfo['member_id'] ])->name : '',
                        '<div class="hidden-sm hidden-xs btn-group">
                            <a class="btn btn-xs btn-info" data-id="' . $vo->id . '" data-manage-id="' .$departManageInfo['id']. '" href="#set_manage" data-toggle="modal">
                                <i class="ace-icon fa fa-pencil bigger-120"> 配置 </i>
                            </a>
                        </div>'
                    ];
                    //unset($departManageInfo);
                }
            }
            $listObj = array('aaData' => $list);
            return $listObj;
        }
    }

    /**
     * 动态获取成员列表（供DataTables调用），如果传了type参数，则生成的列表为复选框，反之为单选
     * @param int $departmentId
     * @param $type
     * @return array
     */
    public function getMemberList($departmentId = 1, $type = 0){
        $request = Request::instance();
        if($request->isAjax()) {
            $departModel = new DepartmentModel();
            $departmentIdList = $departModel->getDepartmentList($this->qychatId, $departmentId);
            $memberIdList = MemberDepartmentModel::where('qychat_id', $this->qychatId)
                ->where('department_id', 'in', $departmentIdList)
                ->column('userid');
            $memberInfoList = array();
            if( ! empty($memberIdList)){
                $memberList = MemberModel::where('qychat_id', $this->qychatId)
                    ->where('userid', 'in', $memberIdList)
                    ->select();
                //判断输出的input按钮是单选还是复选
                $inputType = ($type) ? 'checkbox' : 'radio';
                foreach ($memberList as $member) {
                    $memberInfoList[] = [
                        $member->name,
                        '
                        <label>
                            <input type="'.$inputType.'" name="member_id" class="ace" value="'.$member->userid.'" >
                            <span class="lbl"></span>
                        </label>
                        '
                    ];
                }
            }
            $memberObj = array('aaData' => $memberInfoList);
            return $memberObj;
        }
    }

    /**
     * 设置流程节点管理人员
     */
    public function setManage(){
        $request = Request::instance();
        if($request->isAjax()) {
            $data = array(
                'process_id'    => $request->post('process_id'),
                'department_id' => $request->post('depart_id'),
                'member_id'     => $request->post('member_id'),
                'qychat_id'     => $this->qychatId,
            );
            //校验参数
            $checkRet = $this->validate($data,'Car.setmanage');
            if(true !== $checkRet){
                \Util::echoJson($checkRet);
            }
            $carManageModel = new CarManageModel();
            $id = $request->post('id');
            //如果是修改，先判断要修改的数据是否存在
            if($id){
                $isSet = CarManageModel::where('id', $id)
                        ->where('process_id', $data['process_id'])
                        ->where('qychat_id', $this->qychatId)
                        ->find();
                if($isSet){
                    $res = $carManageModel->save($data, ['id' => $id]);
                }
            }else{
                //添加
                $res = $carManageModel->save($data);
            }
            if(!$res){
                \Util::echoJson($carManageModel->getError());
            }
            \Util::echoJson('操作成功', true, $data['department_id']);
        }
    }


    //*************************************申请****************************************//

    /**
     * 申请记录列表页，同时获取申请人，如果申请人不存在则提示失效
     * @return mixed
     */
    public function apply(){
        $list = CarApplyModel::where('qychat_id', $this->qychatId)
                ->order('id', 'desc')
                ->select();
        foreach($list as $vo){
            $memberInfo = MemberModel::get( ['userid' => $vo['member_id'] ]);
            $vo['member_name'] = (!empty($memberInfo)) ? $memberInfo->name : '申请人失效';
        }
        $this->assign('list',$list);
        $this->assign('botClass','app_car_apply');
        return $this->fetch();
    }

    /**
     * 申请用车详情页
     * @param $id
     * @return mixed
     */
    public function applyInfo($id){
        $data = CarApplyModel::get(['id' => $id, 'qychat_id' => $this->qychatId]);
        if(!$data){
            $this->error('页面不存在！');
        }
        //根据 qychat_id+userid+depart_id+apply_id 从apply_reply表取数据，如果没有 为第一次审批
        $applyReplyData = AppCarApplyReplyModel::where('qychat_id', $this->qychatId)
            ->where('apply_id', $data['id'])
            ->where('department_id', $data['department_id'])
            ->find();
        $applyReplyInfo = ($applyReplyData) ? unserialize($applyReplyData->info) : array();  //如果有记录 取info，并反序列化。反之为空
        if( !empty($applyReplyInfo)){   //取各个流程的进度
            $mark1ReplyData = isset($applyReplyInfo['mark1']) ? $applyReplyInfo['mark1'] : '';
            $mark2ReplyData = isset($applyReplyInfo['mark2']) ? $applyReplyInfo['mark2'] : '';
            $this->assign('mark1ReplyData', $mark1ReplyData);
            $this->assign('mark2ReplyData', $mark2ReplyData);
            $replyModel = new AppCarApplyReplyModel();
            $mark3ReplyData = $replyModel->getUseCarInfo($applyReplyInfo);
            $this->assign('mark3ReplyData', $mark3ReplyData);
        }
        //取审核状态
        switch ($data['status'])
        {
            case 1:
                $processData = AppCarProcess::get($data['process_id']);
                $tip = ($processData->mark == 1) ? ' 审核中' : ' 办理中';
                $data['reply_status'] = ( !empty($processData)) ? $processData->name.$tip : '';
                break;
            case 2:
                $data['reply_status'] = '审核通过，办理中';
                break;
            case 3:
                $data['reply_status'] = '已办理，派车中';
                break;
            case 4:
                $data['reply_status'] = '已派车';
                break;
            case 5:
                $data['reply_status'] = '已结束';
                break;
            default:
                $data['reply_status'] = '已拒绝';
        }
        $this->assign('data', $data);
        return $this->fetch();
    }

}
