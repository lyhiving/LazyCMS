<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2012-08-02 14:44
 */

abstract class Handler {
    // 页面标题
    protected $title = '';

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
    protected function header() {
        $href_styles = $this->get_styles();
        $text_styles = ob_get_content('style');
        include APP_PATH.'/header.php';
    }

    protected function footer() {
        $href_scripts = $this->get_scripts();
        $text_scripts = ob_get_content('script');
        include APP_PATH.'/footer.php';
    }

}
