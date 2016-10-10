<?php
namespace app\app\model;
use think\Model;
use think\Db;
use app\home\model\AppCar as AppcarModel;

class AppCarApplyReply extends Model
{
    // 设置完整的数据表（包含前缀）
    protected $table = 'app_car_apply_reply';
    protected $autoWriteTimestamp = false;

    /**
     * 获取派车情况（内部派车：车辆信息，司机，里程；外部约车：名称 如 首汽 滴滴..，约车数量。）
     * 没有数据返回空
     * @param $data
     * @return null array
     */
    public function getUseCarInfo($data){
        if( isset($data['mark3'])){
            $mark3Data= $data['mark3'];
            $result = array();
            if( !empty($mark3Data['end_info'])){
                foreach ($mark3Data['end_info'] as $k=>$v) {
                    $carData = AppcarModel::get($k);
                    $result[$k]['car_name'] = $carData['name'];
                    $result[$k]['car_idcard'] = $carData['idcard'];
                    $result[$k]['driver'] = $v['driver'];
                    $result[$k]['mileage'] = $v['mileage'];
                }
                $mark3Data['car_type1'] = $result;
                unset($result);
            }
            if( !empty($mark3Data['cars_num'])){
                foreach ($mark3Data['cars_num'] as $k=>$v) {
                    if($v > 0){
                        $carData2 = AppcarModel::get($k);
                        $result[$k]['carType2_name'] = $carData2['name'];
                        $result[$k]['carType2_num'] = $v;
                    }
                }
                $mark3Data['car_type2'] = $result;
            }
            return $mark3Data;
        }else{
            return null;
        }
    }

}