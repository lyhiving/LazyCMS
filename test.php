<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-12-22 16:48
 */
// include global
include dirname(__FILE__) . '/global.php';

// define class
class testHandler extends Handler{
    function get() {
        var_dump(__CLASS__);
        $r = get_config('app_autoload');
        print_r($r);

    }
}
// app run
App::instance()->run();