<?php
/**
 * app functions
 *
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2012-08-25 11:31
 */

/**
 * get app base
 *
 * @return null|string
 */
function app_base() {
    static $r = null;
    if ($r) return $r;
    if($s = Cookie::get('s')){
        $r = base64_decode($s);
    } else if ($s = $_GET['s']) {
        Cookie::set('s', $s);
        $r = base64_decode($s);
    }
    return $r;
}

/**
 * get app host
 *
 * @return string
 */
function app_host() {
    $app_base = app_base();
    if (preg_match('/\:\/\/([^\/]+)/', $app_base, $match)) {
        $host = $match[1];
        return strncasecmp($host, 'www.', 4) === 0 ? substr($host, 4) : $host;
    }
}

/**
 * get api url
 *
 * @param string $action
 * @param array $args
 * @return string
 */
function app_api($action = null, $args = array()) {
    $app_base = app_base();
    $app_host = app_host();
    if ($app_host == $_SERVER['HTTP_HOST']) {
        $path = preg_replace('/^(.+)\:\/\/([^\/]+)\//', '', $app_base).'/admin.php';
    } else {
        $path = $app_base.'admin.php';
    }

    if ($action || $args) {
        if (is_assoc($args)) {
            $query = $args;
        } else {
            $query = array();
            $query['do_args'] = $args;
        }
        $query['do'] = $action;
        $query['timestamp'] = time(); ksort($query);
        $query['sign'] = md5(implode('', array_values($query)).$app_base);
        $query = http_build_query($query);
        if (strpos($path, '?') !== false) {
            $path .= '&' . $query;
        } else {
            $path .= '?' . $query;
        }
    }
    return $path;
}