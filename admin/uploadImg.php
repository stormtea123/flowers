<?php
    header('content-type: text/html; charset=utf-8');
    //如果是分类
    $allowtype = array("gif", "png", "jpg", "mp4");
    $size = 1000000;
    $path = "../content/upload/";

    if ($_FILES["url"]["error"] > 0) {
        switch ($_FILES["url"]["error"]) {
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
    $splitName = explode(".", $_FILES["url"]["name"]);
    $hz = array_pop($splitName);

    if (!in_array($hz, $allowtype)) {
        $result = array(
            'msg' => "这个后缀名不是允许的文件类型！",
            'status' => 'fail'
        );
    }

    if ($_FILES["url"]["size"] > $size) {
        $result = array(
            'msg' => "超过了文件允许的大小！",
            'status' => 'fail'
        );
    }

    $filename = date("YmdHis").rand(100,999);
    $fileImg = $filename.'.'.$hz;
    $dirAndImg = $path.$fileImg;
    if (is_uploaded_file($_FILES["url"]["tmp_name"])) {
        if (!move_uploaded_file($_FILES["url"]["tmp_name"], $dirAndImg)) {
            $result = array(
                'msg' => "文件不能移动到指定的目录！",
                'status' => 'fail'
            );
        }
    } else {
        $result = array(
            'msg' => "上传文件{$_FILES['url']['name']}不是一个合法的文件",
            'status' => 'fail'
        );
    }
    function fileext($file) {
        return pathinfo($file, PATHINFO_EXTENSION);
    }
    //设置缩略图
    function thumb($source_path, $target_path, $target_width, $target_height)
    {
        $source_info   = getimagesize($source_path);
        $source_width  = $source_info[0];
        $source_height = $source_info[1];
        $source_mime   = $source_info['mime'];
        $source_ratio  = $source_height / $source_width;
        $target_ratio  = $target_height / $target_width;
     
        // 源图过高
        if ($source_ratio > $target_ratio)
        {
            $cropped_width  = $source_width;
            $cropped_height = $source_width * $target_ratio;
            $source_x = 0;
            $source_y = ($source_height - $cropped_height) / 2;
        }
        // 源图过宽
        elseif ($source_ratio < $target_ratio)
        {
            $cropped_width  = $source_height / $target_ratio;
            $cropped_height = $source_height;
            $source_x = ($source_width - $cropped_width) / 2;
            $source_y = 0;
        }
        // 源图适中
        else
        {
            $cropped_width  = $source_width;
            $cropped_height = $source_height;
            $source_x = 0;
            $source_y = 0;
        }
     
        switch ($source_mime)
        {
            case 'image/gif':
                $source_image = imagecreatefromgif($source_path);
                break;
     
            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($source_path);
                break;
     
            case 'image/png':
                $source_image = imagecreatefrompng($source_path);
                break;
     
            default:
                return false;
                break;
        }
     
        $target_image  = imagecreatetruecolor($target_width, $target_height);
        $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);
     
        // 裁剪
        imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height); 
        // 缩放
        imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
     
        imagejpeg($target_image,$target_path);

        imagedestroy($source_image);
        imagedestroy($target_image);
        imagedestroy($cropped_image);
    }
    thumb($dirAndImg,$path.$filename.'_560x315.'.$hz,560,315);
    thumb($dirAndImg,$path.$filename.'_240x360.'.$hz,240,360);
    $result = array(
        'img' => array(
            'url' => 'http://'.$_SERVER['HTTP_HOST'].'/wht/content/upload/'.$fileImg,
            'size' => $_FILES['url']['size'],
            'status' => 'sucess'
        )
    );
    $json = json_encode($result);
    echo '<script>parent.uploadImgOption('.$json.')</script>';
    exit;

?>