<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
include_once('config.php');
if (isset($_POST)) {
    $db = new DbConnection;
    $conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $stmt = mysqli_query($conn, "Delete from translations where id = ".$_POST['id']);
    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(
            array(
            "success" => true,
            "message" => "Translation with id ".$_POST['id']." is deleted",
            )
        );
    } else {
        echo json_encode(array("success" => false, "message" => "Translation is not found with id ".$_POST['id']));
    }
}
