<?php
/**
 * 文件的简短描述：通讯录类文件
 *
 * 文件的详细描述：通讯录类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/1
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
 **/
namespace app\home\controller;

use app\home\model\Department as DepartmentModel;
use app\home\model\Member as MemberModel;
use app\home\model\MemberDepartment as MemberDepartmentModel;

class Contact extends Base{

    public function __construct(){
        parent::__construct();
        //导航菜单contact模块
        $this->assign('class','contact');
    }

    public function index(){
        //导航菜单sub_class模块
        $this->assign('subClass','contact');
        //部门树形结构
        $department = new DepartmentModel();
        $a = $department->getDepartmentTreeList($this->qychatId,0);
        $tree = new \Tree();           // new 之前请记得包含tree文件!
        $tree->tree($a);
        $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $treeListForSelect = $tree->getTree(0, $str, 0);
        $this->assign('departmentSelectStr',$treeListForSelect);
        return $this->fetch();
    }

    public function init(){
        if($this->request->isAjax()){
            //判断部门是否为空
            $departmentCount = DepartmentModel::where('qychat_id',$this->qychatId)->count();
            if($departmentCount){
                \Util::echoJson('已有通讯录数据，不能再次初始化！');
            }
            //只要部门为空，则认为通讯录不存在，为避免成员表还有垃圾数据，这里做步删除操作
            MemberDepartmentModel::destroy('qychat_id',$this->qychatId);
            MemberModel::destroy('qychat_id',$this->qychatId);
            //初始化数据
            if($this->_initDepartment() && $this->_initMember()){
                \Util::echoJson('初始化成功！',true);
            }else{
                \Util::echoJson('初始化失败！',true); //虽然初始化失败，但是需要刷新下页面看看
            }
        }
    }

    /**
     * 初始化部门
     * @return array|bool|false
     * @throws \Exception
     */
    private function _initDepartment(){
        //微信接口获取部门列表并插入数据库
        $qychatApi = new \Qychat($this->corpInfo);
        $department = $qychatApi->getDepartment();
        $department = $department['department'];
        if(empty($department)){
            return false;
        }
        $insertData = array();
        foreach($department as $depart){
            $insertData[] = [
                'department_id'     => $depart['id'],
                'department_name'   => $depart['name'],
                'parent_id'         => $depart['parentid'],
                'order_num'         => $depart['order'],
                'qychat_id'         => $this->qychatId
            ];
        }
        //批量插入
        $departmentModel = new DepartmentModel();
        return $departmentModel->saveAll($insertData);
    }

    /**
     * 初始化企业成员信息，包括成员详情、成员部门对应关系
     * @return bool
     * @throws \Exception
     */
    private function _initMember(){
        //微信接口获取成员列表并插入数据库
        $qychatApi = new \Qychat($this->corpInfo);
        $member = $qychatApi->getUserListInfo(1,1);
        $member = $member['userlist'];
        if(empty($member)){
            return false;
        }
        $insertMemberData = array();
        $insertMemberDepartData = array();
        foreach($member as $value){
            $data = [
                'qychat_id'     => $this->qychatId,
                'userid'        => $value['userid'],
                'name'          => $value['name'],
                'gender'        => $value['gender'],
                'status'        => $value['status']
            ];
            //如果有用户头像，则给头像赋值，如果用户未关注，微信不会返回该字段
            if(isset($value['avatar'])){
                $data['avatar'] = $value['avatar'];
            }
            //微信号、邮箱、手机有可能微信不返回该字段
            $data['weixinid'] = isset($value['weixinid']) ? $value['weixinid'] : '';
            $data['email']    = isset($value['email']) ? $value['email'] : '';
            $data['mobile']   = isset($value['mobile']) ? $value['mobile'] : '';
            //成员部门对应关系表
            foreach($value['department'] as $item){
                $insertMemberDepartData[] = [
                    'qychat_id'     => $this->qychatId,
                    'userid'        => $value['userid'],
                    'department_id' => $item
                ];
            }
            $insertMemberData[] = $data;
        }
        $memberModel = new MemberModel();
        $memberDepartmentModel = new MemberDepartmentModel();
        if($memberModel->saveAll($insertMemberData) === false || $memberDepartmentModel->saveAll($insertMemberDepartData) === false){
            return false;
        }
        return true;
    }


}