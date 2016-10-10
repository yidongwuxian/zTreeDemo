<?php
/**
 * 文件的简短描述：部门模型类文件
 *
 * 文件的详细描述：部门模型类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/18
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\model;

use think\Model;
use think\Db;

class Department extends Model {

    // 设置完整的数据表（包含前缀）
    protected $table = 'qy_department';


    /**
     * 获取部门及其子部门列表
     * @param $qychatId
     * @param int $departmentId
     * @return array
     */
    public function getDepartmentList($qychatId, $departmentId = 0){
        $departmentList = Db::table($this->table)->where('qychat_id',$qychatId)->select();
        $idList = [$departmentId];
        return $this->_getSubDepartmentListTreeId($departmentList, $departmentId, $idList);
    }

    public function getDepartmentTreeList($qychatId){
        $departmentList = Db::table($this->table)->where('qychat_id',$qychatId)->select();
        //return $this->_getDepartmentListTree($departmentList, $parentId);
        $resultArray = array();
        foreach($departmentList as $department){
            $resultArray[$department['department_id']] = [
                'id' => $department['department_id'],
                'parent_id' => $department['parent_id'],
                'name' => $department['department_name']
            ];
        }
        return $resultArray;
    }

    /**
     * 返回部门及其子部门下的Id列表
     * @param $departmentList
     * @param int $parentId
     * @param array $idList
     * @return array
     */
    private function _getSubDepartmentListTreeId($departmentList, $parentId = 1, &$idList = array()){
        foreach($departmentList as $v){
            if($v['parent_id'] == $parentId){
                array_push($idList,$v['department_id']);
                //父亲找到儿子
                $this->_getSubDepartmentListTreeId($departmentList,$v['department_id'],$idList);
            }
        }
        return $idList;
    }

    public function _getDepartmentListTree($departmentList, $parentId = 0){
        $tree = array();
        foreach($departmentList as $v){
            if($v['parent_id'] == $parentId){
                //父亲找到儿子
                $v['child'] = $this->_getDepartmentListTree($departmentList,$v['department_id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     * 组合 用车（配置人员数据），增加深度level,用于页面输出分层,增加当前部门管理员和分管领导
     * @param $departmentList
     * @param int $parent_id
     * @param int $level
     * @param bool $isClear
     * @param $qychatId
     * @return array
     */
    public function _getTree($departmentList, $parent_id=0, $level=0, $isClear = FALSE, $qychatId)
    {
        static $ret = array();
        if($isClear)
            $ret = array();
        foreach ($departmentList as $k => $v)
        {
            if($v['parent_id'] == $parent_id)
            {
                // 把level放到这个权限中用来标记当前这个权限是第几级的
                $v['level'] = $level;
                $v['depart_name'] = $this->getDepartManageName($qychatId, $v['department_id'], MANAGE_ONE);
                $v['leader_name'] = $this->getDepartManageName($qychatId, $v['department_id'], MANAGE_TWO);
                $ret[] = $v;
                // 再找当前这个权限的子权限
                $this->_getTree($departmentList, $v['department_id'], $level+1, FALSE, $qychatId);
            }
        }
        return $ret;
    }

    /**
     * 根据 当前企业微信号id,部门id,类型 找对应的管理者name
     * @param $qychatId
     * @param $departId
     * @param $type
     * @return mixed|null
     */
    public function getDepartManageName($qychatId, $departId, $type){
        //先从用车管理者关系表找是否有管理者
        $data = Db::table('app_car_manage')
            ->where('type',$type)
            ->where('qychat_id',$qychatId)
            ->where('department_id',$departId)
            ->find();
        //如果有管理者,再去member表取管理者name,没有返回null
        if(!empty($data)){
            $result = Db::table('qy_member')->where('id', $data['member_id'])->value('name');
        }else{
            $result = null;
        }
        return $result;

    }

}