<?php
/**
 * 验证交易密码，获取TradeToken
 *
 * Author: yosolin
 * Date: 2016/01/13
 */

require_once("config.php");
require_once("functions.php");
require_once("header.php");

// api url与method
$http_url = "https://sandbox-openapi.futu5.com/auth_trade_pswd"; // 生产环境去掉 'sandbox-'
$http_method = "POST";

// http请求body
$http_body = array(
    'trade_pswd' => 'asdasd'
);

// 生成http请求头部
$http_header = make_header($http_body, $http_url, $http_method, false);

// http请求
$ret = ssl_curl($http_url, $http_method, $http_header, $http_body, Config::$ssl_cert, Config::$ssl_key);
echo $ret;