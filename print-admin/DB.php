<?php
class DbConnection
{
    public function __construct()
    {
    }

    public function getConnection($servername, $username, $password, $dbname)
    {
        // Create connection
        /*$conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
        */

        $mysqli = mysqli_connect($servername, $username, $password, $dbname);


        if (mysqli_connect_errno($mysqli)) {
            echo "Failed to connect to MySQLi: " . mysqli_connect_error();
        }
        //mysqli_set_charset($mysqli, "utf8");
        return $mysqli;
    }
}
