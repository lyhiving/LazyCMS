<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2012-07-18 22:08
 */

class User {
    /**
     * 验证用户是否登录成功
     *
     * @static
     * @param string $url
     * @return User|void
     */
    public static function &current($url = null) {
        static $user;
        // 取得 sessid
        $sessid = Cookie::get('sessid');
        $is_login = $sessid ? true : false;
        // 执行用户验证
        if ($is_login) {
            if (!$user)
                if ($user = self::get_bysessid($sessid))
                    $user = array_merge($user, User::get_meta($user['userid']));

            $is_login = $user ? true : false;
        }
        // 未登录，且跳转
        if (!$is_login && $url) {
            // ajax request
            if (is_xhr_request()) {
                quit('not-logged-in', __('您现在已经退出，请重新登录！'), array(
                    'jseval'  => "redirect('" . $url . "');",
                ));
            }
            // 直接跳转
            else {
                redirect($url);
            }
        }
        return $user;
    }
    /**
     * 验证用户权限
     *
     * @static
     * @param string $action
     * @return bool
     */
    public static function cando($action) {
        $result = false;
        if ($user = self::current(false)) {
            // 创建者，拥有所有权限
            if ($user['creator'] == 'Y') {
                $result = true;
            }
            // 先检查管理员的个人权限
            elseif (isset($user['purview']) && instr($action, $user['purview'])) {
                $result = true;
            }
            // 再检查管理员所属组的权限
            elseif (isset($user['roleid'])) {
                // TODO 根据角色ID查找角色的权限
                $result = true;
            }
        }

        // 权限不足
        if (!$result) {
            $message = __('禁止访问，请联系管理员。');
            if (is_xhr_request()) {
                quit('no-permission', $message);
            } else {
                quit(apply_filters('user_cando_html', $message));
            }
        }
        return $result;
    }
    /**
     * 用户登录
     *
     * @static
     * @param string $username
     * @param string $password
     * @return array $user              用户信息
     *         null    null             没有此用户
     *         string  passwd-error     用户密码不正确
     *         string  status           用户的其它状态，可能是被锁定
     */
    public static function login($username, $password) {
        if ($user = self::get_byname($username)) {
            if ($user['status'] !== 'normal') {
                return $user['status'];
            }
            $md5pass = md5($password.$user['sessid']);
            if ($md5pass == $user['pass']) {
                // 不允许多用户同时登录
                $meta = self::get_meta($user['userid']);
                if (!isset($meta['mplogin']) || (isset($meta['mplogin']) && $meta['mplogin']=='N')) {
                    $sessid = self::sessid($user['userid']);
                    if ($sessid != $user['sessid']) {
                        // 生成需要更新的数据
                        $userinfo = array(
                            'pass'   => md5($password.$sessid),
                            'sessid' => $sessid,
                        );
                        // 更新数据
                        self::update($user['userid'], $userinfo);
                        // 合并新密码和key
                        $user = array_merge($user, $userinfo);
                    }
                }
                return $user;
            } else {
                // 密码不正确
                return 'passwd-error';
            }
        } else {
            // 没有此用户
            return null;
        }
    }
    /**
     * 生成sessid
     *
     * @static
     * @param int $userid
     * @return string
     */
    public static function sessid($userid) {
        return md5($_SERVER['HTTP_HOST'] . $userid . get_client_ip() . $_SERVER['HTTP_USER_AGENT'] . time());
    }
    /**
     * 通过用户ID查询用户信息
     *
     * @static
     * @param int $userid
     * @return array|null
     */
    public static function get_byid($userid) {
        return self::get($userid, 'userid');
    }
    /**
     * 通过用户名查询用户信息
     *
     * @static
     * @param string $name
     * @return array|null
     */
    public static function get_byname($name) {
        return self::get($name, 'name');
    }
    /**
     * 通过sessid查询用户信息
     *
     * @static
     * @param string $sessid
     * @return array|null
     */
    public static function get_bysessid($sessid) {
        return self::get($sessid, 'sessid');
    }
    /**
     * 查找用户
     *
     * @static
     * @param $data
     * @param string $type
     * @return null
     */
    private static function get($data, $type = 'userid') {
        $db = get_conn();
        switch($type){
            case 'userid':
                $where = sprintf("`userid`=%d", esc_sql($data));
                break;
            case 'name':
                $where = sprintf("`name`='%s'", esc_sql($data));
                break;
            case 'sessid':
                $where = sprintf("`sessid`='%s'", esc_sql($data));
                break;
            default:
                $where = '0=1';
                break;
        }
        $rs = $db->query("select * from `#@_user` where {$where} limit 1;");
        // 判断用户是否存在
        if ($user = $db->fetch($rs)) {
            return $user;
        }

        return null;
    }
    /**
     * 查找用户扩展信息
     *
     * @param int $userid
     * @return array
     */
    public static function get_meta($userid) {
        $db = get_conn(); $result = array();
        $rs = $db->query("select * from `#@_user_meta` where `userid`=?;", $userid);
        while ($row = $db->fetch($rs)) {
            $result[$row['key']] = is_serialized($row['value']) ? unserialize($row['value']) : $row['value'];
        }
        return $result;
    }

    /**
     * 添加用户
     *
     * @static
     * @param string $name
     * @param string $pass
     * @param string $email
     * @param array $data
     * @return array|null
     */
    public static function add($name,$pass,$email,$data=null) {
        // 插入用户
        $userid = get_conn()->insert('#@_user',array(
           'name' => $name,
           'pass' => $pass,
           'mail' => $email,
           'status' => 'normal',
           'registered' => date('Y-m-d H:i:s',time()),
        ));
        if ($userid) {
            // 生成sessid
            $sessid = self::sessid($userid);
            $info = array(
               'pass'     => md5($pass.$sessid),
               'sessid'   => $sessid,
            );
            if ($data && is_array($data)) {
                $info = array_merge($info, $data);
            }
            // 更新用户资料
            return self::update($userid, $info);
        }
        return null;
    }
    /**
     * 更新用户信息
     *
     * @static
     * @param int $userid
     * @param array $data
     * @return array|null
     */
    public static function update($userid, $data) {
        $db = get_conn(); $user_rows = $meta_rows = array();
        if ($user = self::get_byid($userid)) {
            $data = is_array($data) ? $data : array();
            foreach ($data as $field=>$value) {
                if ($db->is_field('#@_user',$field)) {
                    $user_rows[$field] = $value;
                } else {
                    $meta_rows[$field] = $value;
                }
            }
            // 更新数据
            if ($user_rows) {
                $db->update('#@_user', $user_rows, array('userid' => $userid));
            }
            // update meta
            if ($meta_rows) {
                self::update_meta($userid, $meta_rows);
            }
            return array_merge($user,$data);
        }
        return null;
    }
    /**
     * 填写用户扩展信息
     *
     * @param int $userid
     * @param array $data
     * @return bool
     */
    private static function update_meta($userid, $data) {
        $db = get_conn(); if (!is_array($data)) return false;
        foreach ($data as $key=>$value) {
            // 查询数据库里是否已经存在
            $length = (int) $db->result(vsprintf("select count(`userid`) from `#@_user_meta` where `userid`='%s' and `key`='%s';", array($userid, esc_sql($key))));
            // update and delete
            if ($length > 0) {
                // delete
                if ($value === null) {
                    $db->delete('#@_user_meta', array(
                        'userid' => $userid,
                        'key'    => $key,
                    ));
                }
                // update
                else {
                    $db->update('#@_user_meta',array(
                        'value' => $value,
                    ),array(
                        'userid' => $userid,
                        'key'    => $key,
                    ));
                }
            }
            // insert
            elseif($value !== null) {
                // 保存到数据库里
                $db->insert('#@_user_meta',array(
                    'userid' => $userid,
                    'key'    => $key,
                    'value'  => $value,
                ));
            }
        }
        return true;
    }
    /**
     * 删除用户
     *
     * @static
     * @param int $userid
     * @return bool
     */
    public static function delete($userid) {
        $db = get_conn(); if (!$userid) return false;
        if ($user = self::get_byid($userid)) {
            // 创建者不能删除
            if ($user['creator'] == 'Y')
                return false;
            // delete data
            $db->delete('#@_user', array('userid' => $userid));
            $db->delete('#@_user_meta', array('userid' => $userid));
            return true;
        }
        return false;
    }
}
