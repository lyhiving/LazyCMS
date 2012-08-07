<?php
/**
 * common config
 * 
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-10-09 14:09
 */

// admin url
$config['admin_baseurl'] = 'http://admin.lukin.cn/admin';
// app autoload
$config['app_autoload'] = array(
    '^(Doing|User)$' => APP_PATH . '/lib/$1.php',
);
// app route
$config['app_routes'] = array(

);
