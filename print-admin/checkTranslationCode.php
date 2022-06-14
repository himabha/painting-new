<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
include_once(dirname(dirname(__FILE__)).'/config.php');
if (isset($_POST['code'])) {
    $db = new DbConnection;
    $conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    $where = "";
    if (isset($_POST['id'])) {
        $where = ' and id <> '.$_POST['id'];
    }
    $code = addslashes(strtolower($_POST['code']));
    $stmt = mysqli_query($conn, "select id from translations where code = '".$code."'". $where);
    if (mysqli_num_rows($stmt) > 0) {
        echo json_encode(array(
            "status" => true,
            "message" => "Translation already exists"
        ));
    } else {
        echo json_encode(array(
            "status" => false,
            "message" => "Ok"
        ));
    }
}
