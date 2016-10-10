<?php
namespace app\home\model;
use think\Model;
use think\Session;

class User extends Model
{
    public function login($data){
        $user = User::get(['user_name' => $data['user_name']]);
        if($user){
            if($user['status'] == 1) {
                // 再判断用户输入的密码是否正确,密钥+密码验证
                $encryptData = \Util::encrypt($data['password'],$user['verify_key']);
                if ($user['password'] == $encryptData) {
                    if($user['is_super'])
                        Session::set('is_super', 1);
                    Session::set('user_id', $user['id']);
                    Session::set('user_name', $user['user_name']);
                    return true;
                }
                return false;
            }else
                \Util::echoJson('此账号已被禁用，请联系客服');
        }else
            return false;
    }


    /**
     * 检测用户名或邮箱是否存在
     * @param $data
     * @return bool|string
     */
    public function checkRegister($data){
        $email = $data['email'];
        $userName = $data['user_name'];
        if(empty($email)){  //如果邮箱为空，检测用户名是否存在
            $existUserName = User::get(['user_name' => $userName]);
            if(empty($existUserName))
                return true;
            return $message = '此用户名已被注册';
        }else{  //检测用户名和邮箱是否存在
            $existEmail = User::get(['email' => $email]);
            if(empty($existEmail)){
                $existUserName = User::get(['user_name' => $userName]);
                if(empty($existUserName))
                    return true;
                return $message = '此用户名已被注册';
            }else{
                return $message = '此邮箱已被注册';
            }
        }
    }

}