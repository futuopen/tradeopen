<?php
/**
 * 撤单
 *
 * Author: yosolin
 * Date: 2016/01/13
 */

require_once("config.php");
require_once("functions.php");
require_once("header.php");

// api url与method
$http_url = "https://sandbox-tradeopen.futu5.com/orders/47"; // 生产环境去掉 'sandbox-'，12为订单ID
$http_method = "PUT";

// http请求body
$http_body = array(
    'action' => 0,
);

// 生成http请求头部
$http_header = make_header($http_body, $http_url, $http_method, true);

// http请求
$ret = ssl_curl($http_url, $http_method, $http_header, $http_body, Config::$ssl_cert, Config::$ssl_key);
echo $ret;