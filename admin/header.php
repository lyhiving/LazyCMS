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
<script src="<?php e(APP_ROOT.'res/jquery.js?ver=%s', APP_VER);?>"></script>
<script>document.crossdomain = '<?php e(get_config('crossdomain'))?>';</script>
</head>
<body>