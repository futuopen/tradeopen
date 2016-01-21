<?php
/**
 * HTTP请求头构造
 *
 * Author: yosolin
 * Date: 2016/01/13
 */

require_once("config.php");
require_once("functions.php");

/**
 * 构造返回http请求头部
 *
 * @param array  $http_body     HTTP包体。可能为NULL，比如GET请求时
 * @param string $http_url      请求的url
 * @param string $http_method   http method，比如 'GET', 'POST' 或 'PUT'
 * @param bool   $trade         是否交易请求操作，下单、改单、撤单操作需要为true，其他为false
 * @param string $lang          语言，sc:简体，tc:繁体, en:英文
 *
 * @return array http请求头部
 */
function make_header($http_body, $http_url, $http_method, $trade, $lang = 'sc')
{
    $http_header = array(
        'Accept'                        => 'application/vnd.futu5.openapi-v1+json',
        'Content-Type'                  => 'application/vnd.futu5.openapi-v1+json',
        'X-Futu-Oauth-Appid'            => Config::$appid,
        'X-Futu-Oauth-App-Account'      => Config::$app_account,
        'X-Futu-Oauth-Nonce'            => rand(1, 100000),
        'X-Futu-Oauth-Accesstoken'      => Config::$accesstoken,
        'X-Futu-Oauth-Signature-Method' => 'HMAC-SHA1',
        'X-Futu-Oauth-Timestamp'        => time(),
        'X-Futu-Oauth-Version'          => '1.0',
        'X-Futu-Oauth-Signature'        => '', // 后面生成签名后再赋值
        'X-Futu-Oauth-Lang'             => $lang,
    );

    if ($trade) {
        $http_header['X-Futu-Oauth-Tradetoken'] = Config::$tradetoken;
    }


    // ----------------------数字签名---------------------- //
    // 需要参与签名的头字段
    $sig_header = array(
        'X-Futu-Oauth-Appid'            => $http_header['X-Futu-Oauth-Appid'],
        'X-Futu-Oauth-App-Account'      => $http_header['X-Futu-Oauth-App-Account'],
        'X-Futu-Oauth-Nonce'            => $http_header['X-Futu-Oauth-Nonce'],
        'X-Futu-Oauth-Lang'             => $http_header['X-Futu-Oauth-Lang'],
        'X-Futu-Oauth-Accesstoken'      => $http_header['X-Futu-Oauth-Accesstoken'],
        'X-Futu-Oauth-Signature-Method' => $http_header['X-Futu-Oauth-Signature-Method'],
        'X-Futu-Oauth-Timestamp'        => $http_header['X-Futu-Oauth-Timestamp'],
        'X-Futu-Oauth-Version'          => $http_header['X-Futu-Oauth-Version'],
    );

    if ($trade) {
        $sig_header['X-Futu-Oauth-Tradetoken'] = $http_header['X-Futu-Oauth-Tradetoken'];
    }

    $http_header['X-Futu-Oauth-Signature'] = gen_signature($sig_header, $http_body, $http_url, $http_method,
        Config::$app_secret, Config::$accesstoken);

    return $http_header;
}