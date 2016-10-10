<?php
/**
 * 文件的简短描述：成员控制器类文件
 *
 * 文件的详细描述：成员控制器类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/22
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\home\controller;

use app\home\model\Department as DepartmentModel;
use app\home\model\Member as MemberModel;
use app\home\model\MemberDepartment as MemberDepartmentModel;
use think\Request;

class Member extends Base {

    /**
     * 部门模型
     * @var DepartmentModel
     */
    protected $department;
    /**
     * 成员模型
     * @var MemberModel
     */
    protected $member;
    /**
     * 请求参数
     * @var Request
     */
    protected $request;

    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        //实例化模型类
        $this->department = new DepartmentModel();
        $this->member = new MemberModel();
        $this->memberDepart = new MemberDepartmentModel();
        $this->request = Request::instance();
    }

    /**
     * 动态获取成员列表（供DataTables调用）
     * @param int $departmentid
     * @return array
     */
    public function getMemberList($departmentid = 1){
        if($this->request->isAjax()) {
            $departmentIdList = $this->department->getDepartmentList($this->qychatId, $departmentid);
            $memberIdList = MemberDepartmentModel::where('qychat_id', $this->qychatId)
                ->where('department_id', 'in', $departmentIdList)
                ->column('userid');
            $memberInfoList = array();
            if( ! empty($memberIdList)){
                $memberList = MemberModel::where('qychat_id', $this->qychatId)
                    ->where('userid', 'in', $memberIdList)
                    ->select();
                foreach ($memberList as $member) {
                    $memberInfoList[] = [
                        $member->name,
                        $member->userid,
                        $member->weixinid,
                        $member->mobile,
                        $member->email,
                        '<span class="label label-sm ' . $member->status_class . '">' . $member->status_text . '</span>',
                        '<div class="hidden-sm hidden-xs btn-group">
                    <a class="btn btn-xs btn-info" onclick="getMemberInfo(' . $member->id . ')">
                        <i class="ace-icon fa fa-pencil bigger-120"> 编辑 </i>
                    </a>

                    <a class="btn btn-xs btn-danger" onclick="deleteMember(' . $member->id . ',\'' . $member->userid . '\')">
                        <i class="ace-icon fa fa-trash-o bigger-120"> 删除 </i>
                    </a>
                </div>'
                    ];
                }
            }
            $memberObj = array('aaData' => $memberInfoList);
            return $memberObj;
        }
    }

    /**
     * 动态获取成员列表（供DataTables调用），如果传了type参数，则生成的列表为复选框，反之为单选
     * @param int $departmentid
     * @param $type
     * @return array
     */
    public function getMemberListForModal($departmentid = 1, $type = 0){
        $request = Request::instance();
        if($request->isAjax()) {
            $departModel = new DepartmentModel();
            $departmentIdList = $departModel->getDepartmentList($this->qychatId, $departmentid);
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
                        '<label>
                            <input type="'.$inputType.'" name="member_id" class="ace" value="'.$member->userid.'">
                            <span class="lbl"></span>
                        </label>'
                    ];
                }
            }
            $memberObj = array('aaData' => $memberInfoList);
            return $memberObj;
        }
    }

    /**
     * 添加成员
     * @throws \Exception
     */
    public function add(){
        if($this->request->isAjax()){
            //接收POST参数
            $data = [
                'name'          => $this->request->post('name'),
                'userid'        => $this->request->post('userid'),
                'weixinid'      => $this->request->post('weixinid'),
                'mobile'        => $this->request->post('mobile'),
                'gender'        => $this->request->post('gender'),
                'email'         => $this->request->post('email'),
                'department'    => $this->request->post('department/a'),
            ];
            //校验参数
            $result = $this->validate($data,'Member');
            if(true !== $result){
                \Util::echoJson($result);
            }
            //校验通过，保存数据
            //调用接口创建微信用户
            $qychat_api = new \Qychat($this->corpInfo);
            $addMember = $qychat_api->createUser($data);
            if($addMember == false){
                \Util::echoJson($qychat_api->errMsg);
            }
            //调用接口创建成功，插入数据库
            $departmentIds = $data['department']; //部门id
            $data['qychat_id'] = $this->qychatId;
            unset($data['department']);
            $insertMemberDepartmentData = array();
            foreach($departmentIds as $departmentId){
                $insertMemberDepartmentData[] = [
                    'qychat_id' => $this->qychatId,
                    'userid'    => $data['userid'],
                    'department_id' => $departmentId
                ];
            }
            //插入数据库
            if ( ! $this->member->save($data)) {
                \Util::echoJson($this->member->getError());
            }
            if( ! $this->memberDepart->saveAll($insertMemberDepartmentData)){
                \Util::echoJson($this->memberDepart->getError());
            }
            \Util::echoJson('创建成功！',true);
        }
    }

    /**
     * 获取成员信息
     * @param string $id
     */
    public function getMemberInfo($id = ''){
        $id OR \Util::echoJson('参数错误！');
        if($this->request->isAjax()){
            //查询用户信息
            $memberInfo = $this->member->where('qychat_id', $this->qychatId)->where('id', $id)->find();
            $memberInfo OR \Util::echoJson('无此用户信息！');
            //查询用户所在部门id列表
            $departmentIds = $this->memberDepart->where('qychat_id', $this->qychatId)->where('userid', $memberInfo->userid)->column('department_id');
            $memberInfo->department = $departmentIds ? $departmentIds : [];
            \Util::echoJson('查询成功！', true, $memberInfo);
        }
    }

    /**
     * 成员编辑
     * @throws \Exception
     * @throws \think\Exception
     */
    public function edit(){
        if($this->request->isAjax()){
            //接收POST参数
            $id = $this->request->post('id');
            $data = [
                'name'          => $this->request->post('name'),
                'weixinid'      => $this->request->post('weixinid'),
                'userid'        => $this->request->post('userid'),
                'mobile'        => $this->request->post('mobile'),
                'gender'        => $this->request->post('gender'),
                'email'         => $this->request->post('email'),
                'department'    => $this->request->post('department/a'),
            ];
            //校验参数
            $result = $this->validate($data,'Member.edit');
            if(true !== $result){
                \Util::echoJson($result);
            }
            //校验通过，更新数据
            //调用接口更新微信用户
            $qychat_api = new \Qychat($this->corpInfo);
            $editMember = $qychat_api->updateUser($data);
            if($editMember == false) {
                \Util::echoJson($qychat_api->errMsg);
            }
            //调用接口更新成功，更新数据库
            //更新用户信息
            $userid = $data['userid'];
            unset($data['userid']); //用户账号id不允许修改
            $departmentIds = $data['department']; //部门id
            unset($data['department']);
            if ($this->member->save($data,['id' => $id,'qychat_id' => $this->qychatId]) === false) {
                \Util::echoJson($this->member->getError());
            }
            //更新用户成功，更新用户部门关系（删除老关系，创建新关系）
            MemberDepartmentModel::where('qychat_id',$this->qychatId)->where('userid',$userid)->delete();
            foreach($departmentIds as $departmentId){
                $insertMemberDepartmentData[] = [
                    'qychat_id' => $this->qychatId,
                    'userid'    => $userid,
                    'department_id' => $departmentId
                ];
            }
            //更新数据库
            if($this->memberDepart->saveAll($insertMemberDepartmentData) === false){
                \Util::echoJson($this->memberDepart->getError());
            }
            \Util::echoJson('更新用户信息成功！',true);
        }
    }

    /**
     * 成员删除
     * @throws \think\Exception
     */
    public function delete(){
        if($this->request->isAjax()){
            $userid = $this->request->post('userid');
            $id = $this->request->post('id');
            //调用微信接口删除用户
            $qychat_api = new \Qychat($this->corpInfo);
            $deleteMember = $qychat_api->deleteUser($userid);
            if($deleteMember == false) {
                \Util::echoJson($qychat_api->errMsg);
            }
            //接口调用成功，删除本地数据
            $deleteMemberResult = MemberModel::where('qychat_id',$this->qychatId)->where('id',$id)->delete();
            $deleteMemberDepartResult = MemberDepartmentModel::where('qychat_id',$this->qychatId)->where('userid',$userid)->delete();
            if( ! $deleteMemberResult || ! $deleteMemberDepartResult){
                \Util::echoJson('删除本地用户失败！');
            }
            \Util::echoJson('用户删除成功！',true);
        }
    }

}