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
        ob_start();
        $this->header();
        ?>
        <?php ob_block_start()?>
        <style type="text/css">
        fieldset.closed .inside{ display:none; }
        fieldset { display:block; }
        fieldset .inside{ padding:0 10px; }
        fieldset .inside .empty{ padding:10px; }
        div.container{ width:49%; float:left; margin-right:0.5%; }
        div.server-env label, div.lazy-team label{ margin-right:5px; display:inline-block; text-align:right; color:blue; }
        div.server-env label{ width:115px; }
        div.server-env p{ line-height:20px !important; margin:8px 0 !important; }
        div.server-env .latest{ margin-left:20px; }
        div.server-env .latest img{ margin-top:-2px; }
        div.server-env .latest label{ width:auto; }
        div.lazy-team label{ width:70px; }

        div.container .comments { padding:0 !important; }
        div.container .comments .buttons{ text-align:right; padding:5px; }
        div.container .comment { padding:10px 15px; border-bottom:solid 1px #DFDFDF; }
        div.container .comment img{ float:left; margin-right:10px; }
        div.container .comment .comment-wrap { word-wrap:break-word; overflow:hidden; }
        div.container .comment .comment-wrap h4{ font-size:12px; font-weight:normal; }
        div.container .comment .comment-wrap em{ color:#D98500; font-size:11px; margin-right:5px; }
        div.container .comment .comment-wrap .author{ color:#999; }
        div.container .comment .comment-wrap .content{  }
        div.container .comment .comment-wrap .actions { visibility: hidden; }
        </style>
        <?php ob_block_end('style')?>
        dddd
        <?php ob_block_start()?>
        <script type="text/javascript">
            $('div.container').remember();
            // 表格背景变色
            $('div.container .comment').hover(function(){
                $(this).css({'background-color':'#FFFFCC'});
                $('.actions',this).css({'visibility': 'visible'});
            },function(){
                $(this).css({'background-color':'#FFFFFF'});
                $('.actions',this).css({'visibility': 'hidden'});
            });
            // 绑定展开事件
            $('div.container fieldset').each(function(i){
                var fieldset = $(this);
                $('a.toggle,h3', this).click(function(){
                    fieldset.toggleClass('closed');
                });
            });
            // 取得新版本
            $.ajax(url('http://lazycms.com/version.php?callback=?', {version:$('fieldset .server-env .version').text()}), {
                loading:false, cache:true, dataType:'jsonp', success: function(r){
                    $('fieldset .server-env .latest').html('<label>' + __('最新版本：') + '</label>' + r);
                }
            });
        </script>
        <?php ob_block_end('script')?>
        <?php
        $this->footer();
    }
}

// app run
App::instance()->run('IndexHandler');