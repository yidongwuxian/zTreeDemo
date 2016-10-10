<?php
/**
 * 文件的简短描述：企业成员模型类文件
 *
 * 文件的详细描述：企业成员模型类文件
 *
 * LICENSE:
 * @author wangzhen 2016/8/22
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
namespace app\home\model;

use think\Model;

class Member extends Model {

    // 设置完整的数据表（包含前缀）
    protected $table = 'qy_member';

    public function getStatusTextAttr($value,$data){
        $status = [1=>'已关注',2=>'禁用',4=>'未关注'];
        return $status[$data['status']];
    }

    public function getStatusClassAttr($value,$data){
        $status = [1=>'label-success',2=>'label-danger',4=>'label-warning'];
        return $status[$data['status']];
    }


}