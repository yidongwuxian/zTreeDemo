<?php
namespace app\home\model;
use think\Model;
use think\Db;

class AppCarApply extends Model
{
    // 设置完整的数据表（包含前缀）
    protected $table = 'app_car_apply';

    public function getAuthApplyData($qychatId, $mark, $limt, $userId){
        if($mark == 2){
            //如果是派发流程的管理者，取所有审核通过的申请
            $list = AppCarApply::where('qychat_id', $qychatId)
                ->where('status', '>=', 2)
                ->order('use_date desc')
                ->select();
        }else{
            //如果是办理流程的管理者，取所有已办理的申请
            $list = Db::table('app_car_apply')
                ->alias('a')
                ->join(['car_apply_reply'=>'b', 'app_'],'a.id = b.apply_id','LEFT')
                ->where('a.qychat_id', $qychatId)
                ->where('a.status', '>=', 3)
                ->where('b.execute_id', $userId)
                ->order('a.use_date desc')
                ->column(['a.*']);
        }
        return $list;
    }
}