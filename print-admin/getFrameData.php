<?php
require_once('config.php');
ini_set("display_errors", 1);
error_reporting(E_ALL);
$db = new DbConnection;
$conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if (isset($_POST['id'])) {
    $stmt = mysqli_query($conn, "SELECT * from frames where id = ".$_POST['id']);
    if (mysqli_num_rows($stmt) === 0) {
        exit('');
    }

    $row = $stmt->fetch_assoc();
    $images = [];
    $uploads_dir = ROOT_DIRECTORY."/frames/images/".$_POST['id']."/";
    $uploaded_images = json_decode($row['img_path']);
    $ServerURL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    for($i=0;$i<count($uploaded_images);$i++){
        $file_path = pathinfo($uploaded_images[$i]);
        if(file_exists($uploads_dir."/".$file_path['basename'])){
            $images[$i]['filename'] = $file_path['basename'];
            $images[$i]['img_path'] = $ServerURL.$_SERVER['HTTP_HOST'].ROOT_PATH.$uploaded_images[$i];
            $image_size = getimagesize($uploads_dir."/".$file_path['basename']);
            $images[$i]['width'] = $image_size[0];
            $images[$i]['height'] = $image_size[1];
        }
    }
    $row['images'] = $images;
    echo json_encode($row);
}
