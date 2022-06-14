<?php
require_once('config.php');
class Helper
{
    private $conn;
    public function __construct()
    {
        $db = new DbConnection;
        $this->conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    }

    public function getHebrewText($code)
    {
        $stmt = mysqli_query($this->conn, "select lang_hb from translations where code = '".addslashes($code)."' and active = 1");
        if (mysqli_num_rows($stmt) === 0) {
            return "";
        }

        $row = $stmt->fetch_assoc();
        return $row['lang_hb'];
    }
}
