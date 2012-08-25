<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2012-08-02 14:44
 */

abstract class Handler {
    // user info
    protected $USER;
    // 页面标题
    protected $title = '';
    // 装饰器
    protected $wrap  = true;

    /**
     * Javascript
     *
     * @return array
     */
    public function get_scripts() {
        return array(
            APP_ROOT.'res/bootstrap.js',
            APP_ROOT.'res/common.js',
        );
    }
    /**
     * CSS 样式
     *
     * @return array
     */
    public function get_styles() {
        return array(
            APP_ROOT.'/res/bootstrap.css',
        );
    }
    /**
     * get方法前置事件
     *
     * @return void
     */
    final public function get_prev() {
        ob_start();
    }

    /**
     * get方法后置事件
     *
     * @return void
     */
    final public function get_next() {
        $the_body = ob_block_end('body');
        if ($this->wrap) {
            $href_styles  = $this->get_styles();
            $href_scripts = $this->get_scripts();
            $text_styles  = ob_get_content('style');
            include APP_PATH.'/header.php';
            echo $the_body;
            $text_scripts = ob_get_content('script');
            include APP_PATH.'/footer.php';
        } else {
            echo $the_body;
        }
    }


}


