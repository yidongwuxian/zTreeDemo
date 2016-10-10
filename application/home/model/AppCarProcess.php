<?php
namespace app\home\model;
use think\Model;
use think\Db;

class AppCarProcess extends Model
{
    // 设置完整的数据表（包含前缀）
    protected $table = 'app_car_process';

    /**
     * 判断要编辑的数据 qychat_id和mark和order必须唯一
     * @param $data
     * @return bool|string
     */
    public function checkOrderIsset($data){
        $result = AppCarProcess::where('qychat_id', $data['qychat_id'])
                    ->where('mark', $data['mark'])
                    ->where('order', $data['order'])
                    ->find();
        if(empty($result)){
            return true;
        }else{
            $tip = ($data['mark'] == 1) ? '审核' : '办理';
            return $message = '此 '.$tip.'流程 顺序号已存在';
        }
    }

}