<?php
	include 'dbh.php';

	// if user click the submit button
	if (isset($_POST['submit'])) {
		$first = mysqli_real_escape_string($conn, $_POST['first']);
		$last = mysqli_real_escape_string($conn, $_POST['last']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$uid = mysqli_real_escape_string($conn, $_POST['uid']);
		$pwd = password_hash( mysqli_real_escape_string($conn, $_POST['pwd']), PASSWORD_DEFAULT);


		// if user does not fill in the form
		if (empty($first) or empty($last) or empty($email) or empty($uid)or empty($pwd)) {
			header("Location: ../signup.php?signup=empty&first=$first&last=$last&email=$email&uid=$uid");
			exit();
		}

		// if the input email is not legal
		if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
			header("Location: ../signup.php?signup=invalidemail&first=$first&last=$last&email=$email&uid=$uid");
			exit();
		}

	
		// insert user to database
		$sql2 = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd) VALUES ('$first', '$last', '$email', '$uid', '$pwd');";
		mysqli_query($conn, $sql2);

		// get user id
		$sql3 = "SELECT * FROM users WHERE user_email = '$email';";
		$result = mysqli_query($conn, $sql3);
		foreach ($result as $row) {
			$userid = $row['user_id'];
		}

		// insert profileimg to database according to the user id
		$sql4 = "INSERT INTO profileimg (userid,name) VALUES ('$userid','default.jpg');";
		mysqli_query($conn, $sql4);
				
		header("Location: ../login.php?signup=success");
		
	}


