<?php
    header('content-type: text/html; charset=utf-8');
    //如果是分类
    $allowtype = array("mp4");
    $size = 1000000;
    $path = "../content/upload/";

    if ($_FILES["video"]["error"] > 0) {
        switch ($_FILES["video"]["error"]) {
            case 1 :
                $msg = "上传文件超出了PHP约定值";
            case 2 :
                $msg = "上传文件大小超出了表单中约定值";
            case 3 :
                $msg = "文件只能部分被上传";
            case 4 :
                $msg = "没有上传任何文件";

            default:
                $msg = "未知错误";
        }
        $result = array(
            'msg' => $msg,
            'status' => 'fail'
        );
    }
    $splitName = explode(".", $_FILES["video"]["name"]);
    $hz = array_pop($splitName);

    if (!in_array($hz, $allowtype)) {
        $result = array(
            'msg' => "这个后缀名不是允许的文件类型！",
            'status' => 'fail'
        );
    }

    if ($_FILES["video"]["size"] > $size) {
        $result = array(
            'msg' => "超过了文件允许的大小！",
            'status' => 'fail'
        );
    }

    $filename = date("YmdHis").rand(100,999);
    $fileImg = $filename.'.'.$hz;
    $dirAndImg = $path.$fileImg;
    if (is_uploaded_file($_FILES["video"]["tmp_name"])) {
        if (!move_uploaded_file($_FILES["video"]["tmp_name"], $dirAndImg)) {
            $result = array(
                'msg' => "文件不能移动到指定的目录！",
                'status' => 'fail'
            );
        }
    } else {
        $result = array(
            'msg' => "上传文件{$_FILES['video']['name']}不是一个合法的文件",
            'status' => 'fail'
        );
    }
    function fileext($file) {
        return pathinfo($file, PATHINFO_EXTENSION);
    }
    $result = array(
        'video' => array(
            'url' => 'http://'.$_SERVER['HTTP_HOST'].'/wht/content/upload/'.$fileImg,
            'size' => $_FILES['video']['size'],
            'status' => 'sucess'
        )
    );
    $json = json_encode($result);
    echo '<script>parent.uploadVideoOption('.$json.')</script>';
    exit;

?>