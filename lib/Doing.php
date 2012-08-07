<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2012-06-22 23:00
 */

class Doing {
    private $parent = null;
    public function __construct(&$parent) {
        $this->parent = $parent;
    }
    public function UserLogin() {
        $val = new Validate();
        $val->check(array(
            array('username', VALIDATE_EMPTY, __('请输入用户名！')),
            array('username', VALIDATE_LENGTH, __('用户名必须是%d-%d个字！'), 2, 30)
        ));
        $val->check('password', VALIDATE_EMPTY, __('请输入密码！'));
        if ($val->is_ok()) {
            $username = isset($_POST['username']) ? $_POST['username'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;
            $remember = isset($_POST['remember']) ? $_POST['remember'] : null;
            $userinfo = User::login($username, $password);
            if (is_array($userinfo)) {
                $expire = $remember == 'forever' ? 365 * 86400 : 0;
                Cookie::set('sessid', $userinfo['sessid'], $expire);
                redirect($this->parent->base_url.'/');
            } elseif ($userinfo == 'disable') {
                quit('alert', __('该帐户已被禁用，请联系管理员。'));
            } else {
                quit('alert', __('用户名或者密码错误！'));
            }
        }
        return array();
    }
}
