<?php
/**
 * admin global file
 * 
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-10-08 17:40
 */
// Prevent repeated loading
if (defined('APP_PATH')) return 0;
// admin path
define('APP_PATH', dirname(__FILE__));
// include UPF
include APP_PATH . '/../UPF/UPF.php';
// include function
include APP_PATH . '/inc/function.php';
// include handler
include APP_PATH . '/inc/handler.php';