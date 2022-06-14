<?php 
session_start();
unset($_SESSION['active']);
header("Location:login.php");
?>