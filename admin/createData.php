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
    //创建分类表
    if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . "wht_category" . "'")) > 0) {
        //echo "数据表已经存在，不需要重新创建<br>";
    } else {
        $categorySql = "CREATE TABLE wht_category
        (
            id int(11) NOT NULL auto_increment,
            name VARCHAR(100) default '',
            PRIMARY KEY(id)
        ) ENGINE=innodb DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

        mysql_query($categorySql);
        $defaultCategory = 'INSERT INTO wht_category(name) VALUES ("pc"),("mobile")';
        mysql_query($defaultCategory);
        //echo "创建数据成功";
    }

    //判断表是否存在
    if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . "wht_articles" . "'")) > 0) {
        //echo "articles数据表已经存在，不需要重新创建<br>";
    } else {
        $articlesSql = "CREATE TABLE wht_articles(
            id INT NOT NULL AUTO_INCREMENT,
            title VARCHAR(100) default '',
            summary TEXT default '',
            category int(11) default NULL,
            tag VARCHAR(100),
            videoUrl VARCHAR(200) default '',
            videoSize VARCHAR(200) default '',
            imgUrl VARCHAR(200) default '',
            imgSize VARCHAR(200) default '',
            demoAddress VARCHAR(200) default '',
            time DATETIME,
            PRIMARY KEY(id),
            FOREIGN KEY (category) REFERENCES wht_category (id)
        ) ENGINE=innodb DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
        mysql_query($articlesSql);
        //echo "articles创建数据成功";
    }


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

    $insert = "INSERT INTO wht_articles(title,summary,category,tag,videoUrl,videoSize,imgUrl,imgSize,demoAddress,time) VALUES ('$data[title]','$data[summary]','$data[category]','$data[tag]','$data[videoUrl]','$data[videoSize]','$data[imgUrl]','$data[imgSize]','$data[demoAddress]','$data[time]')";

    $insertData = mysql_query($insert);
    if ($insertData && mysql_affected_rows() > 0) {
        //echo "数据记录插入成功，最后一条插入的数据记录ID为:".mysql_insert_id()."<br>";
        $result = array(
            'status' => 'sucess'
        );
        $json = json_encode($result);
    } else {
        //echo "插入失败，错误号为：".mysql_error()."错误原因：".mysql_error()."<br>";
        $result = array(
            'status' => 'fail'
        );
        $json = json_encode($result);
    }
    echo isset($_GET['callback'])
        ? "{$_GET['callback']}($json)"
        : $json;
    exit;

    //关闭数据库
    mysql_close($link);
?>
