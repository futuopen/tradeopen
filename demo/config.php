<?php
/**
 * 一些APP信息，账户信息等
 *
 * Author: yosolin
 * Date: 2016/01/13
 */

class Config {
    public static $appid = XXXXXXX; // 第三方appid
    public static $app_secret = 'XXXXXXX'; // APP签名密钥

    public static $ssl_cert = 'XXXXXXX'; // 客户端证书文件路径
    public static $ssl_key = 'XXXXXXX'; // 客户端证书密钥文件路径

    // 第三方账户信息。demo这里是先写死，实际生产环境是动态获取的，比如tradetoken是通过验证交易密码获取的。
    public static $app_account = 'XXXXXXX'; // 第三方账户
    public static $accesstoken = 'XXXXXXX'; // 第三方账户对应的accesstoken
    public static $tradetoken = 'XXXXXXX'; // 第三方账户对应的tradetoken
}