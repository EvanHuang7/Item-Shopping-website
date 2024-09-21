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

	<title>Sell Item</title>
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


<!---------------------------------- exsiting items ------------------------------->
<?php
    $id = $_SESSION['id'];
    $sql1 = "SELECT * FROM products WHERE userid='$id';";
    $result = mysqli_query($conn, $sql1);
    $result_check = mysqli_num_rows($result);

    if ($result_check > 0){
        echo "<div class='container'>
                <h3>Items You're selling</h3>
                <div class='row'>";

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $title = $row['title'];
            $pirce = $row['price'];
            $imageName = $row['image_name'];

            echo    "<div class='col-6 col-md-3 my-2'>
                         <div class= 'card h-100'>
                          <a href='product.php?id=".$id."'>
                            <img src='itemImage/".$imageName."' class= 'card-img-top'>
                          </a>
                          <div class='card-body'>
                            <div class='card-mydefine'>
                                <a href='product.php?id=".$id."'>
                                  <h5 class='card-title'>".$title."</h5>
                                </a>
                                <p class='card-text'>price: $".$pirce."</p>
                                <a href='product.php?id=".$id."' class='btn btn-primary'>Check product</a>
                            </div>
                          </div>
                        </div>
                    </div>";
        }
        echo "  </div>
              </div>";


    }
?>


<!---------------------------------- Upload item ------------------------------->
<div class="container mt-4">
    <form action="backendScript/sellItems_process.php" method="POST" enctype="multipart/form-data">
      <h3>Upload New Item</h3>
      <div class="form-group">
        <?php
            if ( isset($_GET['title']) ) {
                $title = $_GET['title'];
                echo '<textarea class="form-control" name="title" rows="1"  placeholder="Title">'.$title.'</textarea>';
            }
            else{
                echo '<textarea class="form-control" name="title" rows="1"  placeholder="Title"></textarea>';
            }
        ?>
      </div>


      <div class="form-group">
        <?php
            if ( isset($_GET['price']) ) {
                $price = $_GET['price'];
                echo '<input type="text" class= "form-control" name="price" placeholder="Price" value='.$price.'>';
            }
            else{
                echo '<input type="text" class= "form-control" name="price" placeholder="Price">';
            }
        ?>
      </div>

      <div class="form-group">
        <?php
            if ( isset($_GET['phone']) ) {
                $phone = $_GET['phone'];
                echo '<input type="phone" class= "form-control" name="phone" placeholder="Phone Number" value='.$phone.'>';
            }
            else{
                echo '<input type="phone" class= "form-control" name="phone" placeholder="Phone Number">';
            }
        ?>
      </div>


      <div class="form-group">
        <?php
            if ( isset($_GET['email']) ) {
                $email = $_GET['email'];
                echo '<input type="email" class= "form-control" name="email" placeholder="Email" value='.$email.'>';
            }
            else{
                echo '<input type="email" class= "form-control" name="email" placeholder="Email">';
            }
        ?>
      </div>


      <div class="form-group">
        <?php
            if ( isset($_GET['address']) ) {
                $address = $_GET['address'];
                echo '<textarea class="form-control" name="address" rows="1"  placeholder="Address">'.$address.'</textarea>';
            }
            else{
                echo '<textarea class="form-control" name="address" rows="1"  placeholder="Address"></textarea>';
            }
        ?>
      </div>

      <div class="form-group">
        <?php
            if ( isset($_GET['description']) ) {
                $description = $_GET['description'];
                echo '<textarea class="form-control" name="description" rows="3"  placeholder="Description">'.$description.'</textarea>';
            }
            else{
                echo '<textarea class="form-control" name="description" rows="3"  placeholder="Description"></textarea>';
            }
        ?>
      </div>

      <div class="form-group">
        <select class="form-control" name="tag">
          <option>game</option>
          <option>beauty</option>
          <option>book</option>
          <option>electronics</option>
          <option>grocery</option>
          <option>health</option>
          <option>home</option>
          <option>luxury</option>
          <option>music</option>
          <option>car</option>
          <option>software</option>
          <option>sport</option>
          <option>wathch</option>
          <option>pet</option>
        </select>
      </div>

      <input type="file" name="myfile">
      <br><br>


      <button type="submit" name="submit" class="btn btn-primary">Upload item</button>
    </form>




    <!-- sell item error handlers -->
    <?php
        if ( !isset($_GET['upload']) ) {
            
        }
        else{
            $uploadCheck = $_GET['upload'];
            switch ($uploadCheck) {
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
                case 'notNumber':
                    echo "<p style='color:red;'>Please enter a number for price and phone</p>";
                    break;
                case 'success':
                    echo "<p style='color:blue;'>Your item has been uploaded!!</p>";
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