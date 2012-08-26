</div>
<div class="footer">
    <div class="container-fluid">
        <p class="pull-left"><?php _e('技术支持：<a href="http://www.uteng.net/" target="_blank">上海游腾网络科技有限公司</a>');?></p>
        <p class="pull-right"><?php _e('Version')?> <span><?php e(APP_VER);?></span></p>
    </div>
</div>
<?php
if (is_array($href_scripts)) foreach ($href_scripts as $script) e('<script src="%s?ver=%s"></script>'."\r\n", str_replace('//', '/', $script), APP_VER);
if ($text_scripts) e($text_scripts);
?>
</body>
</html>