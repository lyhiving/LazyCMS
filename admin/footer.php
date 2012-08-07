<?php 
if (is_array($href_scripts)) foreach ($href_scripts as $script) e('<script src="%s?ver=%s"></script>'."\r\n", str_replace('//', '/', $script), APP_VER);
if ($text_scripts) e($text_scripts);
?>
</body>
</html>