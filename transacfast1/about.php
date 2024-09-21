<?php
	session_start();
	include 'backendScript/dbh.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="https://use.fontawesome.com/releases/v5.0.13/js/all.js"></script>

    <link rel="stylesheet" type="text/css" href="static/index.css">

	<title>About</title>
</head>
<body>

<!-----------------------------------  Navigation --------------------------------->
    <nav class="navbar navbar-expand-md navbar-light">
        <a class="navbar-brand" href="#">
            <img src="static/img/logo.png" width="75" height="60" class="d-inline-block align-top" alt="">
            <p class="logo">Transacfast</p>
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home<span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="about.php">About<span class="sr-only">(current)</span></a>
                </li>

                <?php
                // if user login
                if (isset($_SESSION['id'])) {
                	echo
                   '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Account
                        </a>

                        <div class="dropdown-menu text-center" aria-labelledby="navbarDropdown">
                            <a class="nav-link text-muted" href="sell_items.php">Sell Items</a>
                            <a class="nav-link text-muted" href="account.php">Account</a>
                            <div class="dropdown-divider text-muted"></div>

                            <a class="nav-link text-muted" name = "submitLogout" 
                            href="backendScript/logout_process.php">Logout</a>
                        </div>
                    </li>';
                }
                else{
                	echo
                   '<li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
            
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Signup</a>
                    </li>';
                }
           		?>

            </ul>
            <form class="form-inline mt-4" action="search.php" method="POST">
                <div class="input-group mb-4 border rounded-pill p-1">
                    <input type="search" placeholder="Search" name="searching"  class="form-control bg-none border-0">
                    <div class="input-group-append border-0">
                      <button type="submit" class="btn btn-link text-success"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

        </div>

    </nav>



<!---------------------------------- Sending email ------------------------------->
<div class="container mt-3">
	<h3>Contact</h3>
	<form action="backendScript/contact_process.php" method="POST">
	  <div class="form-group">
	  	<?php
	  		if ( isset($_GET['name']) ) {
	  			$name = $_GET['name'];
	  			echo '<input type="text" class= "form-control" name="name" placeholder="Full Name" value='.$name.'>';
	  		}
	  		else{
	  			echo '<input type="text" class= "form-control" name="name" placeholder="Full Name">';
	  		}
	  	?>
	  </div>
	  <div class="form-group">
	  	<?php
	  		if ( isset($_GET['email']) ) {
	  			$email = $_GET['email'];
	  			echo '<input type="email" class= "form-control" name="email" placeholder="Your Email" value='.$email.'>';
	  		}
	  		else{
	  			echo '<input type="email" class= "form-control" name="email" placeholder="Your Email">';
	  		}
	  	?>
	  </div>
	  <div class="form-group">
	  	<?php
	  		if ( isset($_GET['subject']) ) {
	  			$subject = $_GET['subject'];
	  			echo '<textarea class="form-control" name="subject" rows="1"  placeholder="Subject">'.$subject.'</textarea>';
	  		}
	  		else{
	  			echo '<textarea class="form-control" name="subject" rows="1"  placeholder="Subject"></textarea>';
	  		}
	  	?>
	  </div>

	  <div class="form-group">
	  	<?php
	  		if ( isset($_GET['message']) ) {
	  			$message = $_GET['message'];
	  			echo '<textarea class="form-control" name="message" rows="3"  placeholder="Message">'.$message.'</textarea>';
	  		}
	  		else{
	  			echo '<textarea class="form-control" name="message" rows="3"  placeholder="Message"></textarea>';
	  		}
	  	?>
	  </div>
	  <button type="submit" name = "submit" class="btn btn-primary">Send Email</button>
	</form>
	<!-- send email error handlers -->
	<?php
		if ( !isset($_GET['mailsend']) ) {
		}
		else{
			$emailCheck = $_GET['mailsend'];
			switch ($emailCheck) {
				case 'success':
					echo "<p style='color:blue;'>Email has been sent, thanks for your contact!</p>";
					break;
				case 'empty':
					echo "<p style='color:red;'>Please fill in all fields!</p>";
					break;
				case 'invalidemail':
					echo "<p style='color:red;'>Please enter a correct email!</p>";
					break;
			}
		}
	?>
</div>




    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


	

</body>
</html>