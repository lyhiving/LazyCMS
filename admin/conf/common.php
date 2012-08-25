<?php
/**
 * common config
 * 
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-10-09 14:09
 */

// app autoload
$config['app_autoload'] = array(
    '^(.+?)Handler$' => APP_PATH . '/$1.php',
);
// app route
$config['app_routes'] = array(
    'loginHandler' => '^/login$',
    'proxyHandler' => '^/proxy$',
);
