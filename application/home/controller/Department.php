<?php
/**
 * 文件的简短描述：部门控制器类文件
 *
 * 文件的详细描述：部门控制器类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/23
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\controller;

use app\home\model\Department as DepartmentModel;
use think\Request;

/**
 * 类名：Department
 *
 * 类的详细描述：部门控制器类
 *
 * LICENSE:
 * @author wangzhen ${DATE}
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
 **/
class Department extends Base {

    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        //实例化模型类
        $this->department = new DepartmentModel();
    }

    /**
     * 获取部门列表
     * @param int $departmentId
     */
    public function getDepartmentList($departmentId = 0){
        $a = $this->department->getDepartmentTreeList($this->qychatId,$departmentId);
        $tree = new \Tree();           // new 之前请记得包含tree文件!
        $tree->tree($a);
        $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $treeListForSelect = $tree->getTree(0, $str, 0);
        var_dump($treeListForSelect);
    }

    /**
     * 创建部门
     */
    public function add(){
        $request = Request::instance();
        if($request->isAjax()){
            //接收POST参数
            $data = [
                'department_name'  => $request->post('department_name'),
            ];
            //校验参数
            $result = $this->validate($data,'Department');
            if(true !== $result){
                \Util::echoJson($result);
            }
            //校验通过，调用微信接口添加部门，并获取departmentid
            $data['parent_id'] = $request->post('parent_id');
            $addDepartmentData = [
                'name' => $data['department_name'],
                'parentid' => $data['parent_id']
            ];
            $qychat_api = new \Qychat($this->corpInfo);
            $addDepartment = $qychat_api->createDepartment($addDepartmentData);
            if($addDepartment == false){
                \Util::echoJson($qychat_api->errMsg);
            }
            //调用微信接口创建成功
            $data['department_id'] = $addDepartment['id']; //微信返回的部门id
            $data['qychat_id'] = $this->qychatId;
            $data['order_num'] = 1;
            //数据库插入数据
            $this->department->data($data);
            if ($this->department->save()) {
                \Util::echoJson('创建成功！',true);
            } else {
                \Util::echoJson($this->department->getError());
            }
        }
    }

    /**
     * 返回zTree树形结构
     */
    public function getDepartmentTree(){
        $request = Request::instance();
        if($request->isAjax()){
            $departmentList = DepartmentModel::all(['qychat_id' => $this->qychatId]);
            $tree = array();
            if( ! empty($departmentList)){
                $tree = $this->_getTreeForJS($departmentList);
                $tree[0]['open'] = true;
            }
            return $tree;
        }
    }

    /**
     * 为select选择框生产部门列表字符串
     * @return array
     */
    public function getDepartmentListForSelect(){
        $request = Request::instance();
        if($request->isAjax()){
            //部门树形结构
            $a = $this->department->getDepartmentTreeList($this->qychatId,0);
            $tree = new \Tree();           // new 之前请记得包含tree文件!
            $tree->tree($a);
            $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $treeListForSelect = $tree->getTree(0, $str, 0);
            $result = [
                'list' => $treeListForSelect
            ];
            return $result;
        }
    }

    /**
     * 为Tree返回树行结构
     * @param $list
     * @param int $pid
     * @return array
     */
    private function _getTreeForJS($list,$pid = 0){
        $tree = array();
        foreach($list as $v){
            if($v['parent_id'] == $pid){
                //父亲找到儿子
                $newValue['name'] = $v['department_name'];
                $newValue['id'] = $v['id'];
                $newValue['department_id'] = $v['department_id'];
                $newValue['parent_id'] = $v['parent_id'];
                $newValue['children'] = $this->_getTreeForJS($list,$newValue['department_id']);
                $tree[] = $newValue;
            }
        }
        return $tree;
    }



}