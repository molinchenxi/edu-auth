<?php

if (!function_exists('url_origin')) {
    function url_origin($s, $use_forwarded_host = false)
    {
        $ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $sp       = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port     = $s['SERVER_PORT'];
        $port     = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host     = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host     = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }
}

/**
 * 获取完整 url
 */
if (!function_exists('full_url')) {
    function full_url($s, $use_forwarded_host = false)
    {
        return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
    }
}


/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
if (!function_exists('dump')) {
    function dump($vars)
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '<pre>';
        }

    }
}

/**
 * 友好输出变量并停止脚本
 */
if (!function_exists('dd')) {
    function dd()
    {
        call_user_func('dump', func_get_args());
        die;
    }
}