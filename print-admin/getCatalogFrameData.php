<?php
require_once('config.php');
ini_set("display_errors", 1);
error_reporting(E_ALL);
$db = new DbConnection;
$conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if (isset($_POST['id'])) {
    $stmt = mysqli_query($conn, "SELECT * from catalog_frames where id = ".$_POST['id']);
    if (mysqli_num_rows($stmt) === 0) {
        exit('');
    }

    $row = $stmt->fetch_assoc();
    echo json_encode($row);
}
