<?php
    header('content-type: application/json; charset=utf-8');
    require "connect.inc.php";
   
    if (isset($_GET["keyword"])&&isset($_GET["page"]) && isset($_GET["count"])) {
        $keyword = $_GET["keyword"];
        $startNum = ($_GET["page"] - 1) * $_GET["count"];
        $howNum = $_GET["count"];
        $result = array(
            'count' => $db->get_var('SELECT COUNT( * ) FROM wht_articles WHERE title LIKE "%'.$keyword.'%" OR tag LIKE "%'.$keyword.'%" ORDER BY id DESC'),
            'content' => array()
        );
        $stmt = $db->get_results('SELECT * FROM wht_articles WHERE title LIKE "%'.$keyword.'%" OR tag LIKE "%'.$keyword.'%" ORDER BY id DESC LIMIT ' . $startNum . ',' . $howNum);
        //'.$keyword.'
        //SELECT * FROM articles WHERE title LIKE "%test" ORDER BY id DESC
        //循环
        if (is_array($stmt)){
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
        }
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