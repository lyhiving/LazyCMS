<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-12-22 16:48
 */
// include global
include dirname(__FILE__) . '/global.php';
// define class
class indexHandler extends Handler{
    function get() {
        $r = get_config('app_autoload');
        print_r($r);
    }
}
// app run
App::instance()->run();