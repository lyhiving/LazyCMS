<?php
/**
 * global file
 * 
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-10-08 23:49
 */
// Prevent repeated loading
if (defined('APP_PATH')) return 0;
// admin path
define('APP_PATH', dirname(__FILE__));
// include UPF
include APP_PATH . '/UPF/UPF.php';

add_filter('load_config', 'load_config_filter');

/**
 * 获取域名
 *
 * @return string
 */
function get_http_host() {
    $host = $_SERVER['HTTP_HOST'];
    return strncasecmp($host, 'www.', 4) === 0 ? substr($host, 4) : $host;
}
/**
 * 加载配置文件
 *
 * @param string $file
 * @return string
 */
function load_config_filter($file) {
    $host = get_http_host();
    $base = APP_PATH . '/conf/';
    if (is_ifile($base . $host . '/' . $file . '.php')) {
        return $base . $host . '/' . $file . '.php';
    } else if(is_ifile($base . $file . '.php')){
        return $base . $file . '.php';
    } else {
        return $file;
    }
}

