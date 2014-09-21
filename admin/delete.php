<?php
    header('content-type: application/json; charset=utf-8');
    $link = mysql_connect('localhost', 'ppms', 'ppms');
    mysql_query("SET NAMES 'UTF8'");
    if (!$link) {
        die('链接失败：' . mysql_error());
    } else {
        //echo '数据库连接成功.<br>';
    }
    //选择数据库
    mysql_select_db('ppms', $link) or die("失败" . mysql_error());

    if(isset($_GET["id"])) {
        $id = $_GET["id"];
        $delete = 'DELETE from wht_articles WHERE id ='.$id;
               mysql_query($delete);
        $result = array(
            'status' => 'sucess'
        );
        
    } else {
        $result = array(
            'status' => 'fail'
        );
    }
    $json = json_encode($result);
    echo isset($_GET['callback'])
        ? "{$_GET['callback']}($json)"
        : $json;
    exit;
    //关闭数据库
    mysql_close($link);
?>