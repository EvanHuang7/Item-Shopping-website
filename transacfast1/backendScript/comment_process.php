<?php
	include 'dbh.php';
	session_start();

	// if user click the submit button
	if (isset($_POST['submitComment'])) {
		$content = $_POST['content'];
		$product_id = $_POST['product_id'];
		$user_id = $_POST['user_id'];

		
		// insert user to database
		$sql2 = "INSERT INTO comments (content, productId, userId) VALUES ('$content', '$product_id', '$user_id');";
		mysqli_query($conn, $sql2);
				
		header("Location: ../product.php?id=$product_id");
		
	}



