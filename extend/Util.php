<?php
/**
 * 文件的简短描述：无
 *
 * 文件的详细描述：无
 *
 * LICENSE:
 * @author wangzhen 2016/8/17
 * @copyright Copyright (c) 2016 DFJK
 * @version 1.0.0
 * @since File available since Release 1.0.0
**/
class Util {

    public static function echoJson($message = '参数错误！ ', $status = false, $extension = ''){
        $status = $status ? 1 : 0;
        $result = array(
            'result'   => $status,
            'message'  => $message,
            'extension'=> $extension
        );

        $msg = json_encode($result);
        
        header("Cache-Control: no-cache");
        header('Content-Length: ' . strlen($msg));

        die($msg);
    }

    //密码加密
    public static function encrypt($password, $verify_key){
        return md5($password.$verify_key);
    }

    //生成随机密钥
    public static function random( $length ) {
        $strChars = 'abcdefghijklmopqrstuvwxyzABCDEFGHIJKLMOPQRSTUVWXYZ0123456789';
        $max = strlen( $strChars ) - 1;
        mt_srand( ( double )microtime() * 1000000 );
        $strStartName = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $strStartName .= $strChars[mt_rand( 0, $max )];
        }
        return $strStartName;
    }

}