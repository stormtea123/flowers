<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台首页</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    echo '你好'.$_COOKIE["username"];
?>
<p><a href="login.php?action=logout">退出</a></p>
<p><a href="download.php">开始创建一个火花吧</a></p>
</body>

</html>