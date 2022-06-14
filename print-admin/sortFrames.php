<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
include_once('config.php');
if (isset($_POST['idsOrder'])) {
    $db = new DbConnection;
    $conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);


    $data = array();
    $count = count($_POST['idsOrder']);
    for ($i = count($_POST['idsOrder'])-1; $i >= 0; $i--) {
        $order = $count - $i;
        $data[] = "(".$_POST['idsOrder'][$i].", '".$order."')";
    }
    $query = "INSERT INTO `frames` (id, sort) VALUES " . implode(', ', $data) . " ON DUPLICATE KEY UPDATE sort = VALUES(sort)";

    $stmt = mysqli_query($conn, $query);
    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(
            array(
            "success" => true,
            "message" => "Frames are ordered successfully.",
            )
        );
    } else {
        echo json_encode(
                array(
                "success" => false,
                "message" => "Something went wrong while ordering frames."
            )
            );
    }
}
