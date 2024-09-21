<?php
	require 'PHPMailerAutoload.php';
	include 'dbh.php';
	session_start();

	if (isset($_POST['submitReset'])) {
		$email = $_POST['email'];

		// if user does not fill in the form
		if (empty($email)) {
			header("Location: ../forgetPassword.php?reset=empty&email=$email");
			exit();
		}

		// if input form email is not valide
		if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
			header("Location: ../forgetPassword.php?reset=invalidemail&email=$email");
			exit();
		}

		// if input user email does not exist
		$sql1 = "SELECT * FROM users WHERE user_email = '$email';";
		$result = mysqli_query($conn, $sql1);
		$result_check = mysqli_num_rows($result);
		if ($result_check <= 0){
			header("Location: ../forgetPassword.php?reset=noUser&email=$email");
			exit();
		}

		// selector is for selecting row in database, token for validation
		$selector = bin2hex(random_bytes(8));
		$token = random_bytes(32);

		// create a url for email content
		// we need to convert binary to hex for adding these to url
		$url = "http://localhost:8080/phplessons/transacfast1/createNewPassword.php?selector=$selector&validator=".bin2hex($token);
		$expires = date("U") + 1800;

		// using prepared statment for security reason
		$sql = "DELETE FROM passwordReset WHERE resetEmail=?";
		$stmt = mysqli_stmt_init($conn);
		if (! mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../forgetPassword.php?reset=error&email=$email");
			exit();
		}
		else{
			mysqli_stmt_bind_param($stmt, "s", $email);
			mysqli_stmt_execute($stmt);
		}
		$sql = "INSERT INTO passwordReset (resetEmail, resetSelector, resetToken, resetExpires) VALUES (?,?,?,?);";
		$stmt = mysqli_stmt_init($conn);
		if (! mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../forgetPassword.php?reset=error&email=$email");
			exit();
		}
		else{
			// hash the validation token before inserting database
			$hashedToken = password_hash($token, PASSWORD_DEFAULT);
			mysqli_stmt_bind_param($stmt, "ssss", $email, $selector, $hashedToken, $expires);
			mysqli_stmt_execute($stmt);
		}
		mysqli_stmt_close($stmt);


		/***************************** send email  *******************************/
		$mailTO = $email;
		$txt = '<b>The link to reset your password:</b><br><a href="'.$url.'">'.$url.'</a>';

		$mail = new PHPMailer;

		// $mail->SMTPDebug = 3;                        // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'robotcs1987@gmail.com';                 // SMTP username
		$mail->Password = 'robotcs123';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('robotcs1987@gmail.com');
		$mail->addAddress($mailTO);     // Add a recipient
													                 // Name is optional
		$mail->addReplyTo('robotcs1987@gmail.com');
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');

		// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                               // Set email format to HTML

		$mail->Subject = "Reset your password for transacfast";
		$mail->Body    = $txt;

		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent';
		}

		// mail($mailTO, $subject, $txt, $headers);
		header("Location: ../forgetPassword.php?reset=success");
		


	}


