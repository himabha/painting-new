<?php

include_once('config.php');

$uploads_dir = ROOT_DIRECTORY."/frames/images/tmp/";

if (!file_exists($uploads_dir)) {
    mkdir($uploads_dir, 0777);
}
error_reporting(E_ALL);
ini_set("display_errors", 1);
$rand_folder = mt_rand();
if(count($_FILES["upload_frame"]['name']) > 0){
    if (!file_exists(ROOT_DIRECTORY."/frames/images/tmp/".$rand_folder)) {
        if (mkdir(ROOT_DIRECTORY."/frames/images/tmp/".$rand_folder, 0777)) {
            $images = $_FILES["upload_frame"]['name'];
            $needle = '-s';
            $ret = key(array_filter($images, function($var) use ($needle){
                return strpos($var, $needle) !== false;
            }));

            for($i = 0; $i < count($_FILES["upload_frame"]['name']); $i++){
                if ($_FILES["upload_frame"]["error"][$i] == UPLOAD_ERR_OK) {

                    $tmp_name = $_FILES["upload_frame"]["tmp_name"][$i];

                    $name = basename($_FILES["upload_frame"]["name"][$i]);

                    if (move_uploaded_file($tmp_name, "$uploads_dir/$rand_folder/$name")) {

                        $image_size = getimagesize("$uploads_dir/$rand_folder/$name");

                        $width = $image_size[0];
                        $height = $image_size[1];
                        if($ret === $i) {
                            $path = pathinfo("$uploads_dir/$rand_folder/$name");
                            $ext = strtolower($path['extension']);
                            $ext = ($ext === 'jpg') ? 'jpeg' : $ext;
                            $func_name = "imagecreatefrom".$ext;
                            $size = max($width, $height);
                            $rec = imagecreatetruecolor($size, $size);
                            $alpha_channel = imagecolorallocatealpha($rec, 0, 0, 0, 127);
                            imagefilledrectangle($rec, 0, 0, $size, $size, imagecolortransparent($rec, $alpha_channel));

                            $src = $func_name("$uploads_dir/$rand_folder/$name");

                            $sx = imagesx($src);
                            $sy = imagesy($src);
                            imagecopymerge($rec, $src, imagesx($rec) - $sx, imagesy($rec) - $sy, 0, 0, imagesx($src), imagesy($src), 100);

                            $im = imagerotate($src, 90, 0);
                            $sx = imagesx($im);
                            $sy = imagesy($im);

                            imagecopymerge($rec, $im, imagesx($rec) - $sx, imagesy($rec) - $sy, 0, 0, imagesx($im), imagesy($im), 100);

                            $im = imagerotate($src, -90, 0);
                            $sx = imagesx($im);
                            $sy = imagesy($im);

                            imagecopymerge($rec, $im, 0, 0, 0, 0, imagesx($im), imagesy($im), 100);

                            $im = imagerotate($src, -180, 0);
                            $sx = imagesx($im);
                            $sy = imagesy($im);

                            imagecopymerge($rec, $im, 0, 0, 0, 0, imagesx($im), imagesy($im), 100);

                            imagepng($rec, "$uploads_dir/$rand_folder/$name");
                        }

                        $proportion = $width/$height;

                        $url = "";

                        if (isset($name)) {

                            $ServerURL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

                            $url = $ServerURL.$_SERVER['HTTP_HOST'].ROOT_PATH."/frames/images/tmp/$rand_folder/$name";

                        }

                        $results[$i] = array(
                            "proportion" => $width/$height,
                            "url" => $url,
                            "filename" => $name,
                            "width" => $width,
                            "height" => $height,
                            "tmp_path" => $rand_folder
                        );
                    }

                } else {

                    echo json_encode(array("error" => $_FILES["upload_frame"]["error"][$i]));

                }

            }
            echo json_encode($results);
        } else {
            echo json_encode(array("error" => "Folder ".ROOT_DIRECTORY."/frames/images/tmp/".$rand_folder." is not having permission"));
        }
    } else {
        echo json_encode(array("error" => "Issue in uploading file."));
    }
}
