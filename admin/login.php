<?php
/**
 * @author Lukin <my@lukin.cn>
 * @version $Id$
 * @datetime 2011-10-08 18:08
 */
// include global
include dirname(__FILE__) . '/global.php';
// app run
App::instance()->run();
// define class
class loginHandler {
    function get() {
        $app_base = app_base();
        ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0" />
<title><?php _e('管理员登录');?></title>
<link href="<?php e(APP_ROOT.'res/bootstrap.css?ver=%s', APP_VER);?>" rel="stylesheet" />
<!--[if lt IE 9]>
<script src="<?php e(APP_ROOT.'res/html5.js?ver=%s', APP_VER);?>"></script>
<![endif]-->
<script src="<?php e(APP_ROOT.'res/jquery.js?ver=%s', APP_VER);?>"></script>
<script src="<?php e(APP_ROOT.'res/bootstrap.js?ver=%s', APP_VER);?>"></script>
<script src="<?php e(APP_ROOT.'res/common.js?ver=%s', APP_VER);?>"></script>
<script>
    $(function(){
        $('#login').ajaxSubmit({
            init: function(){
                var self = this;
                $('input[name=username][data-original-title]', self).live('keyup', function(){
                    if (this.value) {
                        $('input[name=username][data-original-title]', self).removeAttr('data-original-title').parents('.control-group').removeClass('error');
                    }
                });
                $('input[name=password][data-original-title]', self).live('keyup', function(){
                    if (this.value) {
                        $('input[name=password][data-original-title]', self).removeAttr('data-original-title').parents('.control-group').removeClass('error');
                    }
                });
            },
            before: function(){
                $('.alert').remove();
            },
            callback:function(data, status, xhr){
                if (data.status == 'alert') {
                    var alert = $([
                        '<div class="alert alert-error fade in">',
                            '<a class="close" data-dismiss="alert" href="#">&times;</a>',
                            '<strong>Warning:</strong>' + data.message,
                        '</div>'
                    ].join('')).appendTo('body').align('tc', [0, 20]);

                    setTimeout(function(){
                        alert.alert('close');
                    }, 3000);
                }
            }
        });

    });
</script>
<style type="text/css">
    #login { width: 545px; height: 300px; margin: 10% auto 0 auto; background: url(res/bg-line.gif) no-repeat 220px top; text-align:left; }
    #login .col1 { width:260px; padding-top:100px; margin:50px auto; float:left; background:url(res/login-logo.png) no-repeat left top; }
    #login .col1 p { color:#999999; margin:8px 0; padding:0; }
    #login .col2 { width:230px; margin:25px 0 0 35px; padding:15px 0px 15px 20px; float:left; }
    #login .col2 dt { font-weight:bold; color:#666666; font-size:14px; }
    #login .col2 dd { margin:10px 0 5px 0; padding:0; }
    #login .col2 select { width:205px; }
    #login .col2 label{ line-height:2; margin-left:1px; }
    #login .col2 input{ margin-bottom: 0; }
    #login .col2 .username,
    #login .col2 .password{ width:200px; margin-left:1px; }
    #login .col2 .remember{ margin-top:15px; float:left; }
	#login .col2 .remember input{ margin-right:3px; }
	#login .col2 .remember label{ display: inline-block; }
    #login .col2 .submit{ width: 75px; text-align: right; margin-right:19px; float:right; _margin-right:10px; _height:25px; }
    .alert{ width: 350px; }
</style>
</head>
<body>
<form id="login" name="login" method="post" action="<?php e(app_api('UserLogin'));?>">
    <div class="col1">
        <p><?php _e('LazyCMS是游腾网络开发的网站内容管理系统。');?></p>
        <p><?php _e('运行环境：PHP 5.0+、MySQL 4.1+');?></p>
        <p><a href="<?php e($app_base);?>"><?php _e('返回首页');?></a></p>
    </div>
    <dl class="col2">
        <dt><?php _e('管理员登录');?></dt>
        <dd><label for="username"><?php _e('用户名');?></label><div class="control-group"><input class="username" type="text" name="username" id="username" tabindex="1" /></div></dd>
        <dd><label for="password"><?php _e('密码');?></label><div class="control-group"><input class="password" type="password" name="password" id="password" tabindex="2" /></div></dd>
        <dd class="remember"><input name="remember" type="checkbox" id="remember" value="forever" tabindex="3" /><label for="remember"><?php _e('记住我的登录信息');?></label></dd>
        <dd class="submit"><button type="submit" class="btn" tabindex="4"><i class="icon-circle-arrow-right"></i> <?php _e('登录');?></button></dd>
    </dl>
</form>
</body>
</html>
        <?php
    }
}
