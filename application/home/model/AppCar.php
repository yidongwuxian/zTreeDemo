<?php
namespace app\home\model;
use think\Model;
use think\Db;

class AppCar extends Model
{
    // 设置完整的数据表（包含前缀）
    protected $table = 'app_car';

    /**
     * 获取车辆
     * @param $id
     * @return bool|array
     */
    public function getCars($id=0){
        if($id){
            $result = Db::name('cars')->where('status',1)->where('id',$id)->select();
        }else{
            $result = Db::name('cars')->select();
        }
        return $result;
    }

    /**
     * 保存车辆信息
     * @param $data
     * @return int|string
     * @throws \think\Exception
     */
    public function carsSave($data){
        if(isset($data['id'])){
            $result = Db::name('cars')->where('id', $data['id'])->update($data);
        }else{
            $result = Db::name('cars')->insert($data);
        }
        return $result;
    }


}