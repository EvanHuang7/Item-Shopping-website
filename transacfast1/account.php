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

	<title>Home</title>
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

                <li class="nav-item">
                    <a class="nav-link" href="about.php">About<span class="sr-only">(current)</span></a>
                </li>

                <?php
                // if user login
                if (isset($_SESSION['id'])) {
                	echo
                   '<li class="nav-item dropdown active">
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




<!-----------------------------------  Account Info --------------------------------->
<?php
	// if use log in
	if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $sql1 = "SELECT * FROM users WHERE user_id = '$id';";
        $result = mysqli_query($conn, $sql1);

        /****************** using while loop to display *******************/
        while ($row = mysqli_fetch_assoc($result)) {
            $first = $row['user_first'];
            $last = $row['user_last'];
            $email = $row['user_email'];
            $uid = $row['user_uid'];

            $sql2 = "SELECT * FROM profileimg WHERE userid = '$id';";
            $resultImg = mysqli_query($conn, $sql2);
            foreach ($resultImg as $rowImg) {
                $nameImg = $rowImg['name'];
            }

            echo
                '<img class="rounded-circle account-img mx-auto d-block mr-4" src="profileImage/'.$nameImg.'">
        
                <div class="container mt-4">
                    <form action="backendScript/updateAccount_process.php" method="POST" enctype="multipart/form-data">
                        <fieldset class="form-group">
                          <legend class="border-bottom mb-4 text-center">Account Information</legend>
                        
                          <div class="form-group mt-4">
                            <label>First Name</label>
                            <input type="text" class= "form-control" name="first" placeholder="Firstname" value='.$first.'>
                          </div>

                          <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class= "form-control" name="last" placeholder="Lastname" value='.$last.'>
                          </div>

                          <div class="form-group">
                            <label>Email</label>
                            <input type="email" class= "form-control" name="email" placeholder="Email" value='.$email.'>
                          </div>

                          <div class="form-group">
                            <label>Username</label>
                            <input type="text" class= "form-control" name="uid" placeholder="Username" value='.$uid.'>
                          </div>

                          <span>Upload User Profile Image</span>
                          <input type="file" name="myfile">
                          <br><br>
                          <button type="submit" name = "submit" class="btn btn-primary">Update</button>
                    </form>

                </div>';
        }

	}
?>
    <!-- login error handlers -->
    <div class="container">
    <?php
        if ( !isset($_GET['update']) ) {
        }
        else{
            $updateCheck = $_GET['update'];
            switch ($updateCheck) {
                case 'empty':
                    echo "<p style='color:red;'>Please fill in all fields!</p>";
                    break;
                case 'invalidemail':
                    echo "<p style='color:red;'>Please enter a valide email!</p>";
                    break;
                case 'incorrectFileType':
                    echo "<p style='color:red;'>This file type is not allowed!</p>";
                    break;
                case 'errorFile':
                    echo "<p style='color:red;'>There was an error for uploading file!</p>";
                    break;
                case 'bigFile':
                    echo "<p style='color:red;'>File is too big!</p>";
                    break;
                case 'success':
                    echo "<p style='color:blue;'>Your account has been updated!!</p>";
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