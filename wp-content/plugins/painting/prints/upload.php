<?php include_once(dirname(__FILE__) . '/../config.php');
$uploads_dir = plugin_dir_path(__FILE__) . "/../uploading/";
if ($_FILES["upload_print"]["error"] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES["upload_print"]["tmp_name"];
    // basename() may prevent filesystem traversal attacks;
    // further validation/sanitation of the filename may be appropriate
    $name = basename($_FILES["upload_print"]["name"]);
    try {
        if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
            $image_info = getimagesize("$uploads_dir/$name");
            $image_size = filesize("$uploads_dir/$name");
            $file_size = formatSizeUnits($image_size);

            $width = $image_info[0];
            $height = $image_info[1];
            $proportion = $width / $height;
            $url = "";
            if (isset($name)) {
                $ServerURL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $url = plugins_url("/uploading/$name", dirname(__FILE__));
            }
            echo json_encode(
                array(
                    "proportion" => $width / $height,
                    "url" => $url,
                    "filename" => $name,
                    "width" => $width,
                    "height" => $height,
                    "size" => $file_size
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

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
