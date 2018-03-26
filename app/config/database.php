<?php
	function db () {
		static $conn;

		$servername	= 'localhost';
		$dbname		= 'blackholeframe';
		$username	= 'root';
		$password	= '';
			
		$conn = new PDO("mysql:host=".$servername.";dbname=".$dbname, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $conn;
	}
?>