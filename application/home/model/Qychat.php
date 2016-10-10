<?php
/**
 * 文件的简短描述：Qychat模型类文件
 *
 * 文件的详细描述：Qychat模型类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/8
 * @copyright Copyright (c) 2016 DFJK
 * @version 2.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\model;

use think\Model;

class Qychat extends Model {

    // 设置完整的数据表（包含前缀）
    protected $table = 'qy_qychat';

    //根据id获取企业信息
    public function getQychatByIds($ids){
        if(is_array($ids)){
            $result = Qychat::all($ids);
        }else{
            $result = Qychat::get($ids);
        }
        return $result;
    }

}