
<?php
	include 'dbh.php';
	session_start();

	$id = $_SESSION['id'];

	// if user click the submit button
	if (isset($_POST['submit'])) {
		$title = mysqli_real_escape_string($conn, $_POST['title']);
		$price = mysqli_real_escape_string($conn, $_POST['price']);
		$phone = mysqli_real_escape_string($conn, $_POST['phone']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$address = mysqli_real_escape_string($conn, $_POST['address']);
		$description = mysqli_real_escape_string($conn, $_POST['description']);
		$tag = mysqli_real_escape_string($conn, $_POST['tag']);


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
	

		// if user does not fill in the form
		if ( empty($title) or empty($price) or empty($phone) or empty($email)or empty($address) or empty($description) or empty($tag) or $_FILES['myfile']['size'] === 0) {
			header("Location: ../sell_items.php?upload=empty&title=$title&price=$price&phone=$phone&email=$email&address=$address&description=$description");
			exit();
		}

		// if the input email is not legal
		if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
			header("Location: ../sell_items.php?upload=invalidemail&title=$title&price=$price&phone=$phone&email=$email&address=$address&description=$description");
			exit();
		}

		// if input price or phone is not number
		if (!is_numeric($price) or !is_numeric($phone)) {
			header("Location: ../sell_items.php?upload=notNumber&title=$title&price=$price&phone=$phone&email=$email&address=$address&description=$description");
			exit();
		}
				
		// if file type not allowed
		if ( !in_array($fileActualExt, $allowed) ) {
			header("Location: ../sell_items.php?upload=incorrectFileType&title=$title&price=$price&phone=$phone&email=$email&address=$address&description=$description");
			exit();
		}
					
		// if there is error inside file
		if ($fileError !== 0) {
			header("Location: ../sell_items.php?upload=errorFile&title=$title&price=$price&phone=$phone&email=$email&address=$address&description=$description");
			exit();
		}
						
		// if file size is greater 5000000kb
		if ($fileSize > 50000000) {
			header("Location: ../sell_items.php?upload=bigFile&title=$title&price=$price&phone=$phone&email=$email&address=$address&description=$description");
			exit();
		}
							
		// avoid uploading file with same name
		$fileNameNew = uniqid('', true).".".$fileActualExt;
		// $fileNameNew = "profile".$id.".".$fileActualExt;
		$fileDestination = '../itemImage/'.$fileNameNew;
		move_uploaded_file($fileTmpName, $fileDestination);

		// insert user to database
		$sql = "INSERT INTO products (title, description, price, phone, email, address, image_name, tag, userid) VALUES ('$title', '$description', '$price', '$phone', '$email', '$address', '$fileNameNew', '$tag', '$id');";
		mysqli_query($conn, $sql);
						

		$dimension = 700;
		$file_name = $fileDestination;

		$image_size = getimagesize($file_name);
		$width = $image_size[0];
		$height = $image_size[1];

		$ratio = $width / $height;
		if ($ratio > 1) {
			$new_width = $dimension;
			$new_height = $dimension / $ratio;
		}
		else{
			$new_height = $dimension;
			$new_width = $dimension * $ratio;
		}

		$src = imagecreatefromstring(file_get_contents($file_name));
		$destination = imagecreatetruecolor($new_width, $new_height);

		imagecopyresampled($destination, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagepng($destination, $file_name);

		imagedestroy($src);
		imagedestroy($destination);

		move_uploaded_file($file_name, $fileDestination);


		header("Location: ../sell_items.php?upload=success");
		
	}
?>

