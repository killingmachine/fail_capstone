<?php
	session_start();
	// unset($_SESSION['admin']);
	//unset($_SESSION['client']);
	session_destroy();
	header("Location: log_in.php");

?>