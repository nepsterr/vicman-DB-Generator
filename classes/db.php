<?php
class db {

	private static $connection = false; 
	public static function connect($host, $user, $pass, $bd) { 
		self::$connection = new mysqli($host,$user,$pass,$bd);
		/* Connection status */
		if (mysqli_connect_errno()) {
			printf("Connection error: %s\n", mysqli_connect_error());
			exit();
		}
	}
	
	public static function disconnect() {
		self::$connection->close();
	}
	public static function query($sql) {
		return self::$connection->query($sql);
	}
}