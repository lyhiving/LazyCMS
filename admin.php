<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-12-22 16:48
 */
// include global
include dirname(__FILE__) . '/global.php';
// app run
App::instance()->run('RunHandler');

class RunHandler {
    public $sign = null;
    public $app_base = null;
    public $base_url = null;
    public $basehost = null;
    public $callback = null;
    public function __construct() {
        $this->sign = isset($_GET['sign']) ? $_GET['sign'] : null;
        if (!$this->sign){
            $this->sign = isset($_POST['sign']) ? $_POST['sign'] : null;
        }
        // 当前域名
        $this->app_base = HTTP_SCHEME.'://'.get_http_host().APP_ROOT;
        // 管理后台地址
        $this->base_url = get_config('admin_baseurl');
        $this->base_url = substr($this->base_url, -1) == '/' ? substr($this->base_url, 0, -1) : $this->base_url;
        // 管理后台host
        if (preg_match('@://([^/]+)@', $this->base_url, $match)) {
            $this->basehost = $match[1];
        } else {
            $this->basehost = get_http_host();
        }
        // callback
        $this->callback = isset($_GET['callback']) ? $_GET['callback'] : null;

        // 添加一个方法过滤器，改变框架的默认执行动作
        add_filter('apprun_method', array(&$this, 'set_method'));

        add_filter('upf_handler_error', array(&$this, 'handler_error'), 10, 2);
    }
    public function set_method() {
        return 'exec';
    }
    public function handler_error($type, $data) {
        if ($this->callback) {
            $data = $this->result($data);
        }
        return $data;
    }
    public function exec() {
        if ($this->verify()) {
            $act_do = isset($_GET['do']) ? $_GET['do'] : null;
            $params = isset($_GET['do_args']) ? $_GET['do_args'] : null;

            if ($act_do && $this->callback) {
                $do = new Doing($this);
                if ($params) {
                    $result = call_user_func_array(array(&$do, $act_do), $params);
                } else {
                    $result = call_user_func(array(&$do, $act_do));
                }
                echo $this->result($result);
            }
        } else {
            redirect($this->base_url.'/index.php?q=login&s='.base64_encode($this->app_base));
        }
    }

    /**
     * 生成输出结果
     *
     * @param mixed $data
     * @return string
     */
    private function result($data) {
        if (!is_scalar($data)) $data = json_encode($data);
        $html = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        if ($this->basehost == get_http_host()) {
            $html.= '<script type="text/javascript">';
            $html.= 'parent && parent.' . $this->callback . ' && parent.' . $this->callback . '(' . $data . ');';
            $html.= '</script>';
            $html.= '</head><body>';
        }
        // 解决iframe跨域不能执行脚本
        else {
            $html.= '</head><body>';
            $html.= '<iframe src="' . $this->base_url . '/index.php?q=proxy&c=' . $this->callback . '&r=' . rawurlencode($data) . '"></iframe>';
        }
        $html.= '</body></html>';
        return $html;
    }
    /**
     * 验证签名是否正确
     *
     * @return bool
     */
    private function verify() {
        if ($this->sign) {
            $args = $_GET; unset($args['sign'], $args['callback']);
            ksort($args); $sign = md5(implode('', array_values($args)).$this->app_base);
            return $sign == $this->sign;
        }
        return false;
    }
}
