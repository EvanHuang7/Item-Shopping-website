<?php
	session_start();

	// unset all varaibles inside session, and destroy them
	session_unset();
	session_destroy();

	header("Location: ../home.php");
	
