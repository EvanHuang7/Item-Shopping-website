<?php
	require 'PHPMailerAutoload.php';
	include 'dbh.php';
	session_start();

	if (isset($_POST['submit'])) {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$subject = mysqli_real_escape_string($conn, $_POST['subject']);
		$message = $_POST['message'];
		$mailTO = "mrxiaohui204@gmail.com";
		$txt = '<b>You have received an e-mail from user ('.$name.') :</b><br><br>'.$message;

		// if user does not fill in the form
		if ( empty($name) or empty($email) or empty($subject) or empty($message ) ) {
			header("Location: ../about.php?mailsend=empty&name=$name&email=$email&subject=$subject&message=$message");
			exit();
		}

		// if input form email is not valide
		if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
			header("Location: ../about.php?mailsend=invalidemail&name=$name&email=$email&subject=$subject&message=$message");
			exit();
		}
			
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
													                
		$mail->addReplyTo($email);
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');

		// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                               // Set email format to HTML

		$mail->Subject = $subject;
		$mail->Body    = $txt;

		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent';
		}

		// mail($mailTO, $subject, $txt, $headers);
		header("Location: ../about.php?mailsend=success");
		
	}


