<?php
/**
 * 一些公用函数
 *
 * Author: yosolin
 * Date: 2016/01/13
 */

/**
 * 生成签名
 *
 * @param array  $sig_header    需要签名的HTTP头字段
 * @param array  $body          HTTP包体。可能为NULL，比如GET请求时
 * @param string $url           url
 * @param string $method        http method，比如 'GET', 'POST' 或 'PUT'
 * @param string $app_secret    签名密钥
 * @param string $accesstoken   accesstoken
 *
 * @return string 签名字符串
 */
function gen_signature($sig_header, $body, $url, $method, $app_secret, $accesstoken)
{
    // 合并header与body
    $sig_fields = $sig_header;
    foreach ($body as $key => $val)
        $sig_fields[$key] = $val;

    // 排序
    ksort($sig_fields);

    // 拼接成字符串
    $sig_plain = $method . '&' . urlencode($url);
    foreach($sig_fields as $key => &$val)
        $sig_plain .= '&' . $key . '=' . urlencode($val);

    // 签名KEY
    $sig_key = $app_secret . '&' . $accesstoken;

    // 使用HMAC-SHA1算法签名
    $sig = base64_encode( hash_hmac('sha1', $sig_plain, $sig_key, true) );

    return $sig;
}

/**
 * 将key-value形式的映射数组转换成冒号:分隔的索引数组
 * 比如 array('name'=>'jack', 'age'=>13) 会转换成 array('name: jack', 'age:13');
 *
 * @param array $arr key-value形式的数组
 *
 * @return array 冒号:分隔的索引数组
 */
function to_colon_style($arr)
{
    $colon_style_arr = array();
    foreach ($arr as $name => $value) {
        $item = $name . ": " . $value;
        array_push($colon_style_arr, $item);
    }

    return $colon_style_arr;
}

/**
 * ssl curl
 *
 * @param string $url       url
 * @param string $method    http method，比如 'GET', 'POST' 或 'PUT'
 * @param array  $header    http请求包头
 * @param array  $body      http请求包体
 * @param string $ssl_cert  ssl客户端证书文件路径
 * @param string $ssl_key   ssl客户端密钥文件路径
 *
 * @return string 请求返回
 */
function ssl_curl($url, $method, $header, $body, $ssl_cert, $ssl_key)
{
    echo "==========URL==========<br />";
    print_r($url);
    echo "<br /> <br />";
    echo "==========METHOD==========<br />";
    print_r($method);
    echo "<br /> <br />";
    echo "==========HEADER==========<br />";
    print_r($header);
    echo "<br /> <br />";
    echo "==========BODY==========<br />";
    print_r($body);
    echo "<br /> <br />";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, to_colon_style($header));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_SSLCERT, $ssl_cert);
    curl_setopt($ch, CURLOPT_SSLKEY, $ssl_key);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    $ret = curl_exec($ch);

    if (false == $ret) {
        return "REQUEST ERROR! error: " . curl_error($ch);
    } else {
        return $ret;
    }
}