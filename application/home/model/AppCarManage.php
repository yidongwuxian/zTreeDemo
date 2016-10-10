<?php
namespace app\home\model;
use think\Model;
use think\Db;
use app\home\model\AppCarProcess as CarProcessModel;

class AppCarManage extends Model
{
    // 设置完整的数据表（包含前缀）
    protected $table = 'app_car_manage';

    public function getDepartManage2($qychatId, $departId){
        $data = Db::table('app_car_process')
                ->alias('a')
                ->join(['car_manage'=>'b', 'app_'],'a.id = b.process_id','LEFT')
                ->where('a.qychat_id', $qychatId)
                ->where('b.department_id', $departId)
                ->order('a.order', 'asc')
                ->column(['b.*','a.name','a.mark','a.qychat_id','a.order']);
        return $data;
    }

    /**
     * 获取car_manage表数据，如果传了指定列参数，返回该列数据，没传返回全部
     * @param $processId
     * @param $qychatId
     * @param $departId
     * @param bool $column
     * @return mixed|null|static
     */
    public function getDepartManage($processId, $qychatId, $departId, $column = false){
        $data = AppCarManage::get(['process_id'=>$processId, 'qychat_id'=>$qychatId, 'department_id'=>$departId]);
        if( !empty($data)){
            if($column)
                return $data->$column;
            return $data;
        }else{
            return null;
        }

    }

    /**
     * 根据流程子节点id删除对应的数据，如果该子节点没有对应数据，返回true
     * @param $processId
     * @return bool
     */
    public function delManageByProceId($processId){
        $dataIds = AppCarManage::where(['process_id' => $processId])->column('id');
        if( !empty($dataIds)){
            AppCarManage::destroy($dataIds);
            return true;
        }else{
            return true;
        }
    }

    /**
     * 合并当前企业号的审核流程,返回审核顺序从低到高的member_id 如果不同审核节点的管理者相同的，只取审核级别最高的节点
     * @param $data
     * @return array
     */
    public function mergeDepartManage($data){
        $res = array();
        foreach ($data as $key=>$vo) {
            if (isset($res[$vo['member_id']])) {
                unset($res[$vo['member_id']]);
                $res[$vo['member_id']]=$vo;
                unset($data[$key]);
            } else {
                $res[$vo['member_id']]=$vo;
            }
        }
        return $res;
    }

    /**
     * 根据qychat_id,depart_id,mark id（流程标记） 取当前用户部门 流程（审核，派发） 管理者信息
     * @param $qychatId
     * @param $departId
     * @param $mark   审核1，派发2
     * @return array|null
     */
    public function getDepartManageData($qychatId, $departId, $mark){
        //根据当前qychat_id和userid取当前企业号审核流程(mark=1)process_id并正序，如果为空提示错误信息
        $processIds = CarProcessModel::where('qychat_id', $qychatId)
            ->where('mark', $mark)
            ->order('`order` asc')
            ->column('id');
        if(empty($processIds))
            return null;
        //根据process_id,qychat_id,depart_id取当前用户部门管理者信息，如果为空提示错误信息
        $departManageData = array();
        foreach ($processIds as $vo) {
            $tempData = AppCarManage::where('qychat_id', $qychatId)
                ->where('department_id', $departId)
                ->where('process_id', $vo)
                ->find();
            if( !empty($tempData))
                $departManageData[] = $tempData;
        }
        return $departManageData;
    }

}