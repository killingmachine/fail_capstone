<?php
	$mysql_host = '127.0.0.1';
	$mysql_user = 'root';
	$conn = new mysqli($mysql_host,$mysql_user,'','oop');
	if($conn->connect_errno){
		echo "Database Broken";
    	exit();
	}
 ?>