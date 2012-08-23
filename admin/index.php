<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-10-08 18:08
 */
// include global
include dirname(__FILE__) . '/global.php';

class IndexHandler extends Handler {
    function __before() {
        $this->title = __('控制面板');
    }
    /**
     * 检查状态
     *
     * @param bool $state
     * @return string
     */
    function test($state) {
        return $state ? '<strong style="color:#009900;">&radic;</strong>' : '<strong style="color:#FF0000;">&times;</strong>';
    }
    function get() {

    }
}

// app run
App::instance()->run('IndexHandler');