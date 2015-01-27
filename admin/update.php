<?php
    header('content-type: application/json; charset=utf-8');
    $link = mysql_connect('localhost', 'name', 'password');
    mysql_query("SET NAMES 'UTF8'");
    if (!$link) {
        die('链接失败：' . mysql_error());
    } else {
        //echo '数据库连接成功.<br>';
    }
    //选择数据库
    mysql_select_db('ppms', $link) or die("失败" . mysql_error());

    $data = array(
        'title' => $_POST["title"],
        'summary' => $_POST["summary"],
        'category' => $_POST["category"],
        'tag' => $_POST["tag"],
        'videoUrl' => $_POST["videoUrl"],
        'videoSize' => $_POST["videoSize"],
        'imgUrl' => $_POST["imgUrl"],
        'imgSize' => $_POST["imgSize"],
        'demoAddress' => $_POST["demoAddress"],
        'time' => date("Y-m-d H:i:s"),
    );
    if(isset($_GET["id"])) {
        $id = $_GET["id"];
        $update = 'UPDATE wht_articles SET title="'.$data["title"].'",summary="'.$data["summary"].'",category="'.$data["category"].'",tag="'.$data["tag"].'",videoUrl="'.$data["videoUrl"].'",videoSize="'.$data["videoSize"].'",imgUrl="'.$data["imgUrl"].'",imgSize="'.$data["imgSize"].'",demoAddress="'.$data["demoAddress"].'",time="'.$data["time"].'" WHERE id ='.$id;
        mysql_query($update);
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
