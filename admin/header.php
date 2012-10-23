<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0" />
<title><?php e($this->title);?></title>
<!--[if lt IE 9]>
<script src="<?php e(APP_ROOT.'res/html5.js?ver=%s', APP_VER);?>"></script>
<![endif]-->
<?php
// css
if (is_array($href_styles)) foreach ($href_styles as $style) e('<link href="%s?ver=%s" rel="stylesheet" />'."\r\n", str_replace('//', '/', $style), APP_VER);
if ($text_styles) e($text_styles);
?>
<style type="text/css">
html,body { height:100%; }
/* Wrapper
------------------------------------------------------------------------------*/
#wrapper { height: auto; min-height: 100%; }
    #wrapper .wrapper{ width: 100%; padding-top: 40px; padding-bottom: 41px; }
/* Footer
------------------------------------------------------------------------------*/
.footer { width:100%; height:30px; overflow: hidden; display:block; padding:5px 0; background:#F5F5F5; border-top:1px solid #E5E5E5; clear:both; position:relative; margin-top:-41px; }
    .footer p { margin:0; padding:7px 0; display:inline-block; color:#666666; }
    .footer p a{ color:#666666; text-decoration: none; }

</style>
<script src="<?php e(APP_ROOT.'res/jquery.js?ver=%s', APP_VER);?>"></script>
<script>document.crossdomain = '<?php e(get_config('crossdomain'))?>';</script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a href="<?php e('http://%s/', app_host())?>" target="_blank" class="brand">LazyCMS</a>
            <ul class="nav">
                <li><a href="<?php e(APP_ROOT.'index.php');?>"><?php _e('控制面板');?></a></li>
                <li><a href="#"><?php _e('内容管理');?></a></li>
                <li><a href="#"><?php _e('模型管理');?></a></li>
                <li class="dropdown">
                    <a href="#userlist.php" class="dropdown-toggle" data-toggle="dropdown"><?php _e('页面管理');?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li><a href="#">用户列表</a></li>
                      <li><a href="#">添加用户</a></li>
                      <li class="divider"></li>
                      <li class="nav-header">Nav header</li>
                      <li><a href="#">我的资料</a></li>
                    </ul>
                </li>
                <li><a href="#"><?php _e('评论管理');?></a></li>
                <li class="dropdown">
                    <a href="#userlist.php" class="dropdown-toggle" data-toggle="dropdown">用户管理<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li><a href="#">用户列表</a></li>
                      <li><a href="#">添加用户</a></li>
                      <li class="divider"></li>
                      <li class="nav-header">Nav header</li>
                      <li><a href="#">我的资料</a></li>
                    </ul>
                </li>
            </ul>
            <p class="navbar-text pull-right">
              Logged in as <a class="navbar-link" href="#">Lukin</a>
            </p>
        </div>
    </div>
</div>
<div id="wrapper" class="container-fluid">
    <div class="wrapper">