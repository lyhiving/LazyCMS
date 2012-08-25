<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-10-08 18:08
 */
// include global
include dirname(__FILE__) . '/global.php';
// app run
App::instance()->run('proxyHandler');
// define class
class proxyHandler {
    function get() {
        $callback = isset($_GET['c']) ? $_GET['c'] : null;
        $result   = isset($_GET['r']) ? json_decode($_GET['r']) : null;
        echo '<script type="text/javascript">';
        if ($callback) {
            echo 'parent && parent.parent && parent.parent.' . $callback . ' && parent.parent.' . $callback . '(' . json_encode($result) . ');';
        } else {
            echo $result;
        }
        echo '</script>';
    }
}
