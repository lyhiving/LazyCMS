<?php
/**
 * app functions
 *
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2012-08-25 11:31
 */

/**
 * 获取域名
 *
 * @return string
 */
function get_http_host() {
    $host = $_SERVER['HTTP_HOST'];
    return strncasecmp($host, 'www.', 4) === 0 ? substr($host, 4) : $host;
}
/**
 * 加载配置文件
 *
 * @param string $file
 * @param string $super
 * @return string
 */
function load_config_filter($file, $super=null) {
    $host = get_http_host();
    if ($super == '__super__') {
        $base = APP_PATH . '/../conf/';
    } else {
        $base = APP_PATH . '/conf/';
    }
    if (is_ifile($base . $host . '/' . $file . '.php')) {
        return $base . $host . '/' . $file . '.php';
    } else if(is_ifile($base . $file . '.php')){
        return $base . $file . '.php';
    } else {
        return $file;
    }
}
/**
 * 判断是否搜索蜘蛛
 *
 * @static
 * @return bool
 */
function is_spider() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (stripos($user_agent, 'Googlebot') !== false
        || stripos($user_agent, 'Sosospider') !== false
        || stripos($user_agent, 'Baiduspider') !== false
        || stripos($user_agent, 'Baidu-Transcoder') !== false
        || stripos($user_agent, 'Yahoo! Slurp') !== false
        || stripos($user_agent, 'iaskspider') !== false
        || stripos($user_agent, 'Sogou') !== false
        || stripos($user_agent, 'YodaoBot') !== false
        || stripos($user_agent, 'msnbot') !== false
        || stripos($user_agent, 'Sosoimagespider') !== false
    ) {
        return true;
    }
    return false;
}
/**
 * 全角转半角
 *
 * @param string $str
 * @return string
 */
function semiangle($str) {
    $arr = array(
        '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
        '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
        'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
        'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
        'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
        'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
        'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
        'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
        'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
        'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
        'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
        'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
        'ｙ' => 'y', 'ｚ' => 'z',

        'ā' => 'a', 'á' => 'a', 'ǎ' => 'a', 'à' => 'a','ō' => 'o',
        'ó' => 'o', 'ǒ' => 'o', 'ò' => 'o', 'ê' => 'e', 'ē' => 'e', 'é' => 'e',
        'ě' => 'e', 'è' => 'e', 'ī' => 'i', 'í' => 'i', 'ǐ' => 'i', 'ì' => 'i',
        'ū' => 'u', 'ú' => 'u', 'ǔ' => 'u', 'ù' => 'u', 'ǖ' => 'v', 'ǘ' => 'v',
        'ǚ' => 'v', 'ǜ' => 'v', 'ü' => 'v', 'ɡ' => 'g',


        '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[', '】' => ']',
        '〖' => '[', '〗' => ']', '“' => '"', '”' => '"', '‘' => "'", '’' => "'",
        '｛' => '{', '｝' => '}', '《' => '<', '》' => '>',

        '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
        '：' => ':', '。' => '.', '、' => ',', '，' => ',',
        '；' => ';', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
        '｜' => '|', '〃' => '"', '　' => ' ',

    );
    return strtr($str, $arr);
}