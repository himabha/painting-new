<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
include_once('config.php');
if (isset($_POST)) {
    $db = new DbConnection;
    $conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $stmt = mysqli_query($conn, "Delete from frames where id = ".$_POST['id']);
    if (mysqli_affected_rows($conn) > 0) {
        $delete_dir = ROOT_DIRECTORY."/frames/images/".$_POST['id'];
        if (file_exists($delete_dir)) {
            $directory = new \RecursiveDirectoryIterator($delete_dir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $iterator = new \RecursiveIteratorIterator($directory);
            foreach ($iterator as $info) {
                unlink($info->getPathname());
            }
            if (rmdir($delete_dir)) {
                echo json_encode(
                    array(
                    "success" => true,
                    "message" => "Frame with id ".$_POST['id']." is deleted",
                    )
                );
            } else {
                echo json_encode(array("success" => true, "message" => "File can not be deleted."));
            }
        } else {
            echo json_encode(array("success" => true, "message" => "File does not exist."));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Frame is not found with id ".$_POST['id']));
    }
}
