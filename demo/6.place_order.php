<?php
/**
 * 下订单
 *
 * Author: yosolin
 * Date: 2016/01/13
 */

require_once("config.php");
require_once("functions.php");
require_once("header.php");

// api url与method
$http_url = "https://sandbox-tradeopen.futu5.com/orders"; // 生产环境去掉 'sandbox-'
$http_method = "POST";

// http请求body
$http_body = array(
    'code' => '90008',
    'quantity' => 100,
    'price' => 1.5,
    'side' => 'BUY',
    'type' => 'E'
);

// 生成http请求头部
$http_header = make_header($http_body, $http_url, $http_method, true);

// http请求
$ret = ssl_curl($http_url, $http_method, $http_header, $http_body, Config::$ssl_cert, Config::$ssl_key);
echo $ret;