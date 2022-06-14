<?php include_once(dirname(__FILE__).'/../config.php');
$uploads_dir = plugin_dir_path(__FILE__). "/../uploading/";
if ($_FILES["upload_print"]["error"] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES["upload_print"]["tmp_name"];
    // basename() may prevent filesystem traversal attacks;
    // further validation/sanitation of the filename may be appropriate
    $name = basename($_FILES["upload_print"]["name"]);
    try {
        if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
            $image_size = getimagesize("$uploads_dir/$name");
            $width = $image_size[0];
            $height = $image_size[1];
            $proportion = $width/$height;
            $url = "";
            if (isset($name)) {
                $ServerURL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $url = plugins_url("/uploading/$name", dirname(__FILE__));
            }
            echo json_encode(
                array(
            "proportion" => $width/$height,
            "url" => $url,
            "filename" => $name,
            "width" => $width,
            "height" => $height
            )
            );
        } else {
            echo json_encode(array("error" => "File cannot be uploaded"));
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
} else {
    echo json_encode(array("error" => $_FILES["upload_print"]["error"]));
}
