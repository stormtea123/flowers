<?php
    header('content-type: application/json; charset=utf-8');
    require "connect.inc.php";
   
    if (isset($_GET["page"]) && isset($_GET["count"]) && isset($_GET["category"])) {
        $startNum = ($_GET["page"] - 1) * $_GET["count"];
        $howNum = $_GET["count"];

        $categoryNum = $_GET["category"];
        $result = array(
            'count' => $db->get_var('SELECT COUNT( * ) FROM wht_articles WHERE category=' . $categoryNum . ' ORDER BY id DESC'),
            'content' => array()
        );
        $stmt = $db->get_results('SELECT * FROM wht_articles WHERE category=' . $categoryNum . ' ORDER BY id DESC LIMIT ' . $startNum . ',' . $howNum);
        //循环
        foreach ( $stmt as $article ) {
            $result['content'][] = array(
                'id' => $article->id,
                'title' => $article->title,
                'summary' => $article->summary,
                'category' => $article->category,
                'tag' => $article->tag,
                'videoUrl' => $article->videoUrl,
                'videoSize' => $article->videoSize,
                'imgUrl' => $article->imgUrl,
                'imgSize' => $article->imgSize,
                'demoAddress' => $article->demoAddress,
                'time' => $article->time
            );
        }
    } else if (isset($_GET["page"]) && isset($_GET["count"])) {

        $startNum = ($_GET["page"] - 1) * $_GET["count"];
        $howNum = $_GET["count"];

        $result = array(
            'count' => $db->get_var('SELECT COUNT( * ) FROM wht_articles'),
            'content' => array()
        );
        $stmt = $db->get_results('SELECT * FROM wht_articles ORDER BY id DESC LIMIT ' . $startNum . ',' . $howNum);
        //循环
        foreach ( $stmt as $article ) {
            $result['content'][] = array(
                'id' => $article->id,
                'title' => $article->title,
                'summary' => $article->summary,
                'category' => $article->category,
                'tag' => $article->tag,
                'videoUrl' => $article->videoUrl,
                'videoSize' => $article->videoSize,
                'imgUrl' => $article->imgUrl,
                'imgSize' => $article->imgSize,
                'demoAddress' => $article->demoAddress,
                'time' => $article->time
            );
        }
    } else if (isset($_GET["id"])) {
        $idNum = $_GET["id"];
        $stmt = $db->get_results('SELECT * FROM wht_articles WHERE id=' . $idNum);
        foreach ( $stmt as $article ) {
           $result['content'][] = array(
               'id' => $article->id,
               'title' => $article->title,
               'summary' => $article->summary,
               'category' => $article->category,
               'tag' => $article->tag,
               'videoUrl' => $article->videoUrl,
               'videoSize' => $article->videoSize,
               'imgUrl' => $article->imgUrl,
               'imgSize' => $article->imgSize,
               'demoAddress' => $article->demoAddress,
               'time' => $article->time
           );
        }
    } else {
        $result = array(
            'count' => $db->get_var('SELECT COUNT( * ) FROM wht_articles'),
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