<?php
require_once('config.php');
$db = new DbConnection;
$conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if (isset($_POST['id'])) {
    $stmt = mysqli_query($conn, "SELECT * from translations where id = ".$_POST['id']);
    if (mysqli_num_rows($stmt) === 0) {
        exit('');
    }

    $row = $stmt->fetch_assoc();
    $file_path = pathinfo($row['img_path']);
    $row['filename'] = $file_path['basename'];
    $ServerURL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $row['img_path'] = $ServerURL.$_SERVER['HTTP_HOST'].ROOT_DIRECTORY.$row['img_path'];
    echo json_encode($row);
}
