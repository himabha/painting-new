<?php
class DbConnection
{
	public function __construct()
	{
		
	}
	
	public function getConnection($servername, $username, $password, $dbname)
	{
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		return $conn;
	}
}
?>