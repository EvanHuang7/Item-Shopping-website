<?php
session_start();
include 'dbh.php';
$id = $_SESSION['id'];

if ( isset($_POST['submit']) ){
	$first = mysqli_real_escape_string($conn, $_POST['first']);
	$last = mysqli_real_escape_string($conn, $_POST['last']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$uid = mysqli_real_escape_string($conn, $_POST['uid']);


	// if user does not fill in the form
	if (empty($first) or empty($last) or empty($email) or empty($uid)) {
		header("Location: ../account.php?update=empty&first=$first&last=$last&email=$email&uid=$uid");
		exit();
	}


	// if the input email is not legal
	if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../account.php?update=invalidemail&first=$first&last=$last&email=$email&uid=$uid");
		exit();
	}
		
	// if do not update profile image
	if ( $_FILES['myfile']['size'] === 0 ) {
		$sql1 = "UPDATE users SET user_first='$first', user_last='$last', user_email='$email', user_uid='$uid' WHERE user_id = '$id';";
		mysqli_query($conn, $sql1);
		header("Location: ../account.php?update=success");
		exit();
	}
	// if update profile image
	else{
		$file = $_FILES['myfile'];

		// get information from file
		$fileName = $_FILES['myfile']['name'];
		$fileTmpName = $_FILES['myfile']['tmp_name'];
		$fileSize = $_FILES['myfile']['size'];
		$fileError = $_FILES['myfile']['error'];
		$fileType = $_FILES['myfile']['type'];

		// seperate the file name by .
		$fileExt = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));

		// check which types of files allowed for uploading
		$allowed = array('jpg', 'jpeg', 'png', 'gif', 'pdf');

		// if file type not allowed
		if ( !in_array($fileActualExt, $allowed) ) {
			header("Location: ../account.php?update=incorrectFileType&first=$first&last=$last&email=$email&uid=$uid");
			exit();
		}
		
		// if there is error inside file
		if ($fileError !== 0) {
			header("Location: ../account.php?update=errorFile&first=$first&last=$last&email=$email&uid=$uid");
			exit();
		}
			
		// if file size is greater 5000000kb
		if ($fileSize > 50000000) {
			header("Location: ../account.php?update=bigFile&first=$first&last=$last&email=$email&uid=$uid");
			exit();
		}
				
		// avoid uploading file with same name
		$fileNameNew = uniqid('', true).".".$fileActualExt;
		// $fileNameNew = "profile".$id.".".$fileActualExt;
		$fileDestination = '../profileImage/'.$fileNameNew;
		move_uploaded_file($fileTmpName, $fileDestination);

		// delete old profile image in folder
		$sql10 = "SELECT * FROM profileimg WHERE userid = '$id';";
		$result = mysqli_query($conn, $sql10);
		foreach ($result as $row) {
			// if the image is not default image, we delete it
			if ($row['name'] !== 'default.jpg'){
				$oldImgName = '../profileImage/'.$row['name'];
				unlink($oldImgName);
			}
		}
							
		// change the user's profileimg
		$sql2 = "UPDATE profileimg SET name = '$fileNameNew' WHERE userid = '$id';";
		mysqli_query($conn, $sql2);

		// change the user information
		$sql3 = "UPDATE users SET user_first='$first', user_last='$last', user_email='$email', user_uid='$uid' WHERE user_id = '$id';";
		mysqli_query($conn, $sql3);

		header("Location: ../account.php?update=success");
		
	}

}
