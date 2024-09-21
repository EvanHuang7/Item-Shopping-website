<?php
	include 'dbh.php';
	session_start();

	if (isset($_POST['resetPassword'])) {
		$selector = $_POST["selector"];
	    $validator = $_POST["validator"];
	    $password = $_POST["password"];
	    $confirmPassword = $_POST["confirmPassword"];

	    // if inputs are empty
	    if (empty($password) || empty($confirmPassword)) {
	    	header("Location: ../createNewPassword.php?reset=empty&selector=$selector&validator=$validator");
	    	exit();
	    }

	    // if password and confirm password are different
	    if ($password !== $confirmPassword) {
	    	header("Location: ../createNewPassword.php?reset=noSame&selector=$selector&validator=$validator");
	    	exit();
	    }

	    // if there is no token information in database
	    $sql = "SELECT * FROM passwordReset WHERE resetSelector = '$selector';";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) === 0) {
			header("Location: ../createNewPassword.php?reset=error&selector=$selector&validator=$validator");
			exit();
		}
		
		foreach ($result as $row) {
			// we need to convert token from hex to binary first
			$tokenBin = hex2bin($validator);
			$tokenCheck = password_verify($tokenBin, $row['resetToken']);
			$currentDate = date("U");
			// if token expired
			if ($currentDate >= $row['resetExpires']) {
				header("Location: ../createNewPassword.php?reset=expires&selector=$selector&validator=$validator");
				exit();
			}
			// if token is incorrect
			if ($tokenCheck === false) {
				header("Location: ../createNewPassword.php?reset=error&selector=$selector&validator=$validator");
				exit();
			}
			
			// update new password for user
			$tokenEmail = $row['resetEmail'];
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$sql = "UPDATE users SET user_pwd = '$hashedPassword' WHERE user_email='$tokenEmail';";
			mysqli_query($conn, $sql);

			// delete the token information in database
			$sql = "DELETE FROM passwordReset WHERE resetEmail='$tokenEmail';";
			mysqli_query($conn, $sql);
			header("Location: ../login.php?reset=resetSuccess");
					
				
		}
		

	}


