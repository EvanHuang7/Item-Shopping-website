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

    

    <script>
        var commentBox = () => {
          var x = document.getElementById("comment_box");
          var y = document.getElementById("comment_box_check");
          if (x.style.display === "none") {
            x.style.display = "block";
            y.innerHTML = "Close Comment";
          } else {
            x.style.display = "none";
            y.innerHTML = "Add a New Comment";
          }
        }
    </script>

	<title>Product</title>
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
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">Home<span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
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


<!-----------------------------------  product info --------------------------------->

<?php
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM products WHERE id='$id';";
    $result = mysqli_query($conn, $sql1);
    $result_check = mysqli_num_rows($result);

    if ($result_check > 0){
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $title = $row['title'];
            $pirce = $row['price'];
            $imageName = $row['image_name'];
            $description = $row['description'];
            $phone = $row['phone'];
            $email = $row['email'];
            $address = $row['address'];

            echo"
            <div class='container'>
                <img src='itemImage/".$imageName."'>
                <div class='row'>
                    <div class='col-6 col-md-3 my-2'>
                         <div class= 'card h-100'>
                          <a href='product.php?id=".$id."'>
                            <img src='itemImage/".$imageName."' class= 'card-img-top'>
                          </a>
                          <div class='card-body'>
                            <a href='product.php?id=".$id."'>
                              <h5 class='card-title'>".$title."</h5>
                            </a>
                            <p class='card-text'>Price:<br>$".$pirce."</p>
                            <p class='card-text'>Description:<br>".$description."</p>
                            <p class='card-text'>Phone:<br>".$phone."</p>
                            <p class='card-text'>Email:<br>".$email."</p>
                            <p class='card-text'>Address:<br>".$address."</p>
                            <a href='product.php?id=".$id."' class='btn btn-primary'>Check product</a>
                          </div>
                        </div>
                    </div>
                </div>
            </div>";
        }

    }
?>



<!-----------------------------------  comments --------------------------------->
<div class="container mb-4" id="comments">
    <h5>Comments</h5>
    <?php
        $product_id = $_GET['id'];
        $sql = "SELECT * FROM comments WHERE productId='$product_id';";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            foreach ($result as $row) {
                $content = $row['content'];
                $user_id = $row['userId'];
                echo'
                <div class="card mb-4">
                  <div class="card-body">
                    <span class="text-muted">username: '.$user_id.' </span>
                    <span class="comment_content">'.$content.'</span>
                  </div>
                </div>
                ';
            }
        }
        else{
            echo '<h6>Nobody leave a comment yet</h6>';
        }

    ?>
</div>

<!-- <div class="container mb-4">
    <button class="btn btn-outline-primary" id="moreComments">Show more Comments</button>
</div> -->

<!-----------------------------------  add comment --------------------------------->
<?php
if ( isset($_SESSION['id']) ) {
    $user_id = $_SESSION['id'];

?>

<div class="container mb-4">
    <button type="button" class="btn btn-outline-primary" id="comment_box_check" onclick="commentBox()">Add a New Comment</button>
</div>

<div class="container mt-4">
    <form id="comment_box" action="backendScript/comment_process.php" method="POST" style="display: none";>
      <input type="hidden" name="product_id" value="<?php echo($product_id); ?>">
      <input type="hidden" name="user_id" value="<?php echo($user_id); ?>">
      <div class="form-group">
        <textarea class="form-control" name="content" rows="4"></textarea>
      </div>
      <button type="submit" class="btn btn-primary" name="submitComment">Submit</button>
    </form>
</div>


<!-- <div class="container mt-4">
    <button type="button" class="btn btn-outline-primary" onclick="commentBox()">Add a New Comment</button>
</div> -->

<?php
}

?>




	













    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


	

</body>
</html>