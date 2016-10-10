<?php
use think\Cache;

use app\home\model\Department as DepartmentModel;
use app\home\model\Member as MemberModel;

class QychatLib{
	/**
	 * 根据企业号ID，查询部门信息
	 * 
	 * @param  integer $qychatId 企业号ID
	 * @return mix
     * success:
     * ['部门id' => 'pidName-name', '部门id' => 'pidName-pidName-name', ...] 
	 */
	public static function getDepartsName($qychatId, $isUpdate = false)
    {
        $qychatId = intval($qychatId);
        if (! $qychatId)
        {
            return false;
        }

        if (! $isUpdate)
        {
            $result  = Cache::get("departName{$qychatId}");

            if ($result)
            {
                return $result;
            }
        }

        $departs = DepartmentModel::all(['qychat_id' => $qychatId]);

        foreach ($departs as $key => $value) 
        {
            $departList[$value['parent_id']][$value['department_id']] = $value['department_name'];
        }

        $baseName = $departList[0][1];

        unset($departList[0]);
      
        $result = [];
        $departmentIds = [];

        foreach ($departList[1] as $key => $value)
        {
            $result[$key] = $baseName . "-" . $value;
            $departmentIds[$key] = "1-{$key}";
        }

        unset($departList[1]);

        foreach ($departList as $key => $value)
        {
            if (! isset($result[$key]))
            {
                continue;
            }

            $base = $result[$key];
            $pKey = $departmentIds[$key];
            foreach ($value as $k => $val)
            {
                $result[$k] = $base . "-" . $val;
                $departmentIds[$k] = "{$pKey}-{$k}";
            }
        }

        $res1 = Cache::set("departName{$qychatId}",  $result);
        $res2 = Cache::set("departIds{$qychatId}",  $departmentIds);
       
        if ($isUpdate)
        {
            return (! $res1 || ! $res2) ? false : true ;
        }

        return  $result;
    }

    /**
     * 根据企业号ID，查询部门直属关系
     * @param  integer $qychatId 企业号ID
     * @param  intger  $departId 部门ID
     * @return mix
     * success:
     * ['部门id' => '1pid-2pid-3pid-id', '部门id' => '1pid-2pid-id', ...]
     */
    public static function getDepartIds($qychatId, $departId = 0)
    {
        $qychatId = intval($qychatId);
        if (! $qychatId )
        {
            return 0;
        }

        $departIds = Cache::get("departIds{$qychatId}");
        if (! $departIds)
        {
            self::getDepartsName($qychatId, true);
        }

        $departIds = Cache::get("departIds{$qychatId}");

        if (! $departIds)
        {
            return 0;
        }

        if (! $departId)
        {
            return $departIds;
        }

        if (isset($departIds[$departId]))
        {
            return $departIds[$departId];
        }

        return 0;
    }

    /**
     * 获取用户的关系信息
     * @param  initeger $qychatId  企业号ID
     * @param  string   $userId    用户编号
     * @return mix
     * success：['departId'=>1, 'tags'=>[1,2], 'name'=>'']
     */
    public static function getUserRelations($qychatId, $userId = '', $isUpdate = false)
    {
        $qychatId = intval($qychatId);
        $userId   = trim($userId);
        if (! $qychatId)
        {
            return false;
        }

        $result = [];

        if ($isUpdate)
        {
            $where = [
                'm.qychat_id'  => $qychatId,
                'md.qychat_id' => $qychatId,
                'm.userid'     => $userId,
                'md.userid'    => $userId,
            ];

            $memberObj = new MemberModel();
            $members   = $memberObj->alias('m')->join('qy_member_depart md', 'm.userid = md.userid')->where($where)->field('m.userid, m.name, md.department_id')->select();

            foreach ($members as $key => $value)
            {
                $result[$value['userid']] = [
                    'name'      => $value['name'],
                    'departId'  => $value['department_id'],
                    'userId'    => $value['userid'],
                    'tags'      => [],
                ];
            }

            $res = Cache::set("qyUserRelations{$qychatId}", $result);

            if (! $userId)
            {
                return $res ? true : false;
            }
        }
        else
        {
            $result = Cache::get("qyUserRelations{$qychatId}");
        }

        if (! $userId)
        {
            return $result;
        }

        if ( isset($result[$userId]) )
        {
            return $result[$userId];
        }

        return [];
    }

    /**
     * 上传excel文件格式
     * @return [type] [description]
     */
    public static function getExcelExt()
    {
        $ext = ["xls", "xla", "xlc", "xlm", "xlt", "xlw", "xlsx", "xlsm", "xltx", "xltm", "xlsb", "xlam", "csv"];

        $mime = ["application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel.sheet.macroEnabled.12",
        "application/vnd.ms-excel.sheet.macroEnabled.12", "application/vnd.ms-excel.template.macroEnabled.12", "application/vnd.ms-excel.template.macroEnabled.12",
        "application/vnd.ms-excel.addin.macroEnabled.12", "text/comma-separated-values"];

        return ['ext' => $ext, 'mime' => $mime];
    }

    public static function getImgExt()
    {
        $ext = ["jpg", "jpg", "png"];

        $mime = ["image/jpeg", "image/gif", "image/png"];

        return ['ext' => $ext, 'mime' => $mime];
    }

    /**
     * 整理菜单数据
     * 添加菜单，一级菜单最多3个，每个一级菜单最多可以有5个二级菜单
     * @param $menuList
     *          array(
     *              array('id'=>'', 'pid'=>'', 'name'=>'', 'type'=>'', 'code'=>''),
     *              array('id'=>'', 'pid'=>'', 'name'=>'', 'type'=>'', 'code'=>''),
     *              array('id'=>'', 'pid'=>'', 'name'=>'', 'type'=>'', 'code'=>''),
     *          );
     *          'code'是view类型的URL或者其他类型的key
     *          'type'是菜单类型，如下:
     *              1、click：点击推事件，用户点击click类型按钮后，微信服务器会通过消息接口推送消息类型为event的结构给开发者（参考消息接口指南），并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互；
     *              2、view：跳转URL，用户点击view类型按钮后，微信客户端将会打开开发者在按钮中填写的网页URL，可与网页授权获取用户基本信息接口结合，获得用户基本信息。
     *              3、scancode_push：扫码推事件，用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL），且会将扫码的结果传给开发者，开发者可以下发消息。
     *              4、scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框，用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者，同时收起扫一扫工具，然后弹出“消息接收中”提示框，随后可能会收到开发者下发的消息。
     *              5、pic_sysphoto：弹出系统拍照发图，用户点击按钮后，微信客户端将调起系统相机，完成拍照操作后，会将拍摄的相片发送给开发者，并推送事件给开发者，同时收起系统相机，随后可能会收到开发者下发的消息。
     *              6、pic_photo_or_album：弹出拍照或者相册发图，用户点击按钮后，微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”。用户选择后即走其他两种流程。
     *              7、pic_weixin：弹出微信相册发图器，用户点击按钮后，微信客户端将调起微信相册，完成选择操作后，将选择的相片发送给开发者的服务器，并推送事件给开发者，同时收起相册，随后可能会收到开发者下发的消息。
     *              8、location_select：弹出地理位置选择器，用户点击按钮后，微信客户端将调起地理位置选择工具，完成选择操作后，将选择的地理位置发送给开发者的服务器，同时收起位置选择工具，随后可能会收到开发者下发的消息。
     *
     * @return array
     * * example:
     *  array (
     *      'button' => array (
     *        0 => array (
     *          'name' => '扫码',
     *          'sub_button' => array (
     *              0 => array (
     *                'type' => 'scancode_waitmsg',
     *                'name' => '扫码带提示',
     *                'key' => 'rselfmenu_0_0',
     *              ),
     *              1 => array (
     *                'type' => 'scancode_push',
     *                'name' => '扫码推事件',
     *                'key' => 'rselfmenu_0_1',
     *              ),
     *          ),
     *        ),
     *        1 => array (
     *          'name' => '发图',
     *          'sub_button' => array (
     *              0 => array (
     *                'type' => 'pic_sysphoto',
     *                'name' => '系统拍照发图',
     *                'key' => 'rselfmenu_1_0',
     *              ),
     *              1 => array (
     *                'type' => 'pic_photo_or_album',
     *                'name' => '拍照或者相册发图',
     *                'key' => 'rselfmenu_1_1',
     *              )
     *          ),
     *        ),
     *        2 => array (
     *          'type' => 'location_select',
     *          'name' => '发送位置',
     *          'key' => 'rselfmenu_2_0'
     *        ),
     *      ),
     *  )
     */
    public static function madeMenuList($menuList)
    {
        //整理菜单
        $menuList2 = $menuList;
        foreach ($menuList as $key => $menu)
        {
            foreach ($menuList2 as $k2 => $menu2)
            {
                if ($menu['id'] == $menu2['pid'])
                {
                    $menuList[$key]['sub_button'][] = $menu2;
                    unset($menuList[$k2]);
                }
            }
        }
        unset($menuList2);
        //处理数据
        foreach ($menuList as $key => $menu)
        {
            if ($menu['type'] == 'view')
            {
                //view 跳转url
                $menuList[$key]['url'] = urlencode($menu['code']);
            } 
            elseif ($menu['type'] == 'click')
            {
                $menuList[$key]['key'] = $menu['code'];
            }
            elseif ($menu['type'] == 'media_id' || $menu['type'] == 'view_limited')
            {
                $menuList[$key]['media_id'] = $menu['code'];
            }
            else
            {
                $menuList[$key]['key'] = $menu['code'];
                /* if (! isset($menu['sub_button']))
                {
                    $menuList[$key]['sub_button'] = array();
                } */
            }
            
            unset($menuList[$key]['code']);
            unset($menuList[$key]['id']);
            unset($menuList[$key]['pid']);
            
            //处理菜单名称，用urlencode处理
            $menuList[$key]['name'] = urlencode($menu['name']);
            //处理子菜单
            if (isset($menu['sub_button']))
            {
                //有子菜单的主菜单没有type类型，需要unset掉
                unset($menuList[$key]['type']);
                foreach ($menu['sub_button'] as $sonKey => $sonMenu)
                {
                    //处理type和code
                    if ($sonMenu['type'] == 'view')
                    {
                        $menuList[$key]['sub_button'][$sonKey]['url'] = urlencode($sonMenu['code']);
                    }
                    elseif ($sonMenu['type'] == 'click')
                    {
                        $menuList[$key]['sub_button'][$sonKey]['key'] = $sonMenu['code'];
                    }
                    elseif ($sonMenu['type'] == 'media_id' || $sonMenu['type'] == 'view_limited')
                    {
                        $menuList[$key]['sub_button'][$sonKey]['media_id'] = $sonMenu['code'];
                    }
                    else 
                    {
                        $menuList[$key]['sub_button'][$sonKey]['key'] = $sonMenu['code'];
                    }
                    
                    unset($menuList[$key]['sub_button'][$sonKey]['id']);
                    unset($menuList[$key]['sub_button'][$sonKey]['pid']);
                    unset($menuList[$key]['sub_button'][$sonKey]['code']);
                    
                    $menuList[$key]['sub_button'][$sonKey]['name'] = urlencode($sonMenu['name']);
                }
            }
        }

        $data['button'] = array_values($menuList);

        return $data;
    }


    // 生成随机长度字符串
    public static function appAeskey($length)
    {
        $hash   = '';
        $chars  = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max    = strlen($chars) - 1;
        mt_srand((double)microtime() * 1000000);
        for($i = 0; $i < $length; $i++) 
        {
             $hash .= $chars[mt_rand(0, $max)];
        }

        return $hash;
    }

}


