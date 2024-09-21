<?php
	session_start();
	include 'dbh.php';

	if (isset($_POST['submitLogin'])) {
		$inputEmail = $_POST['email'];
		$inputPassword = $_POST['pwd'];

		// if inputs are empty
		if (empty($inputEmail) or empty($inputPassword)) {
			header("Location: ../login.php?login=empty&email=$inputEmail");
			exit();
		}

		// if no that user
		$sql1 = "SELECT * FROM users WHERE user_email = '$inputEmail';";
		$result = mysqli_query($conn, $sql1);
		$result_check = mysqli_num_rows($result);
		if ($result_check <= 0){
			header("Location: ../login.php?login=noThisUser&email=$inputEmail");
			exit();
		}


		// if incorrect password
		foreach ($result as $row) {
			$realPassword = $row['user_pwd'];
		}
		if ( ! password_verify($inputPassword, $realPassword)) {
			header("Location: ../login.php?login=incorrectPassword&email=$inputEmail");
			exit();
		}


		$_SESSION['id'] = $row['user_id'];
		$_SESSION['uid'] = $row['user_uid'];
		header("Location: ../home.php");

	}
