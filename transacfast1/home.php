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


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script>
		
	</script>

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

    <div class='container mb-4'>
		<div class="dropdown">
		  <a class="btn btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    Sort by
		  </a>
		  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
		    <a href='home.php?sort=timeOld' class='dropdown-item'>time(old to new)</a>
		    <a href='home.php?sort=timeNew' class='dropdown-item'>time(new to old)</a>
		    <a href='home.php?sort=priceHigh' class='dropdown-item'>price(high to low)</a>
		    <a href='home.php?sort=priceLow' class='dropdown-item'>price(low to High)</a>
		  </div>
		</div>
		<br><br>

    	<a href='home.php?sort=game' class='btn btn-outline-info m-2'>game</a>
    	<a href='home.php?sort=beauty' class='btn btn-outline-info m-2'>beauty</a>
    	<a href='home.php?sort=book' class='btn btn-outline-info m-2'>book</a>
    	<a href='home.php?sort=electronics' class='btn btn-outline-info m-2'>electronics</a>
    	<a href='home.php?sort=grocery' class='btn btn-outline-info m-2'>grocery</a>
    	<a href='home.php?sort=health' class='btn btn-outline-info m-2'>health</a>
    	<a href='home.php?sort=home' class='btn btn-outline-info m-2'>home</a>
    	<a href='home.php?sort=luxury' class='btn btn-outline-info m-2'>luxury</a>
    	<a href='home.php?sort=music' class='btn btn-outline-info m-2'>music</a>
    	<a href='home.php?sort=car' class='btn btn-outline-info m-2'>car</a>
    	<a href='home.php?sort=software' class='btn btn-outline-info m-2'>software</a>
    	<a href='home.php?sort=sport' class='btn btn-outline-info m-2'>sport</a>
    	<a href='home.php?sort=wathch' class='btn btn-outline-info m-2'>wathch</a>
    	<a href='home.php?sort=pet' class='btn btn-outline-info m-2'>pet</a>
    	<hr>
    </div>

<!---------------------------------- Display items------------------------------->

<?php
	/***************** check which page to display *****************/
	$result_per_page = 8;
	if (! isset($_GET['page'])) {
		$page = 1;
	}
	else{
		$page = $_GET['page'];
	}
	$this_page_first_result = ($page - 1) * $result_per_page;

	/***************** grab product info at that page *****************/
	if (isset($_GET['sort'])) {
		$sortCheck = $_GET['sort'];
		switch ($sortCheck) {
			case 'timeOld':
				$sql1 = "SELECT * FROM products ORDER BY id LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'timeNew':
				$sql1 = "SELECT * FROM products ORDER BY id DESC LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'priceHigh':
				$sql1 = "SELECT * FROM products ORDER BY price DESC LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'priceLow':
				$sql1 = "SELECT * FROM products ORDER BY price LIMIT ".$this_page_first_result.",".$result_per_page;
				break;


			case 'game':
				$sql1 = "SELECT * FROM products WHERE tag = 'game' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'beauty':
				$sql1 = "SELECT * FROM products WHERE tag = 'beauty' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;

			case 'book':
				$sql1 = "SELECT * FROM products WHERE tag = 'book' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'electronics':
				$sql1 = "SELECT * FROM products WHERE tag = 'electronics' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;

			case 'grocery':
				$sql1 = "SELECT * FROM products WHERE tag = 'grocery' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'health':
				$sql1 = "SELECT * FROM products WHERE tag = 'health' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;

			case 'home':
				$sql1 = "SELECT * FROM products WHERE tag = 'home' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'luxury':
				$sql1 = "SELECT * FROM products WHERE tag = 'luxury' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;

			case 'music':
				$sql1 = "SELECT * FROM products WHERE tag = 'music' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'car':
				$sql1 = "SELECT * FROM products WHERE tag = 'car' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'software':
				$sql1 = "SELECT * FROM products WHERE tag = 'software' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'sport':
				$sql1 = "SELECT * FROM products WHERE tag = 'sport' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'wathch':
				$sql1 = "SELECT * FROM products WHERE tag = 'wathch' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
			case 'pet':
				$sql1 = "SELECT * FROM products WHERE tag = 'pet' LIMIT ".$this_page_first_result.",".$result_per_page;
				break;
		}
	}

	else{
		$sql1 = "SELECT * FROM products LIMIT ".$this_page_first_result.",".$result_per_page;
	}

	$result = mysqli_query($conn, $sql1);
	$result_check = mysqli_num_rows($result);
	
	/***************** display products info at that page *****************/
	if ($result_check > 0){
		echo "<div class='container'>
				<h1>Selling items</h1>
				<div class='row'>";

		while ($row = mysqli_fetch_assoc($result)) {
			$id = $row['id'];
            $title = $row['title'];
            $pirce = $row['price'];
            $imageName = $row['image_name'];

			echo	"
					<div class='col-6 col-md-3 my-2'>
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
    						    <a href='product.php?id=".$id."' class='btn btn-primary '>Check product</a>
                            </div>
						  </div>
						</div>
					</div>";
		}
		echo "	</div>
			  </div>";


	}

?>
<div class='container'>
	<nav aria-label="Page navigation example">
	  <ul class="pagination">
	  	<?php
	  		/***************** Check total product numbers *****************/
			if (isset($_GET['sort'])) {
				$sortCheck = $_GET['sort'];
				switch ($sortCheck) {
					case 'timeOld':
						$sql1 = "SELECT * FROM products ORDER BY id;";
						break;
					case 'timeNew':
						$sql1 = "SELECT * FROM products ORDER BY id DESC;";
						break;
					case 'priceHigh':
						$sql1 = "SELECT * FROM products ORDER BY price DESC;";
						break;
					case 'priceLow':
						$sql1 = "SELECT * FROM products ORDER BY price;";
						break;


					case 'game':
						$sql1 = "SELECT * FROM products WHERE tag = 'game';";
						break;

					case 'beauty':
						$sql1 = "SELECT * FROM products WHERE tag = 'beauty';";
						break;

					case 'book':
						$sql1 = "SELECT * FROM products WHERE tag = 'book';";
						break;
					case 'electronics':
						$sql1 = "SELECT * FROM products WHERE tag = 'electronics';";
						break;

					case 'grocery':
						$sql1 = "SELECT * FROM products WHERE tag = 'grocery';";
						break;
					case 'health':
						$sql1 = "SELECT * FROM products WHERE tag = 'health';";
						break;

					case 'home':
						$sql1 = "SELECT * FROM products WHERE tag = 'home';";
						break;
					case 'luxury':
						$sql1 = "SELECT * FROM products WHERE tag = 'luxury';";
						break;

					case 'music':
						$sql1 = "SELECT * FROM products WHERE tag = 'music';";
						break;
					case 'car':
						$sql1 = "SELECT * FROM products WHERE tag = 'car';";
						break;
					case 'software':
						$sql1 = "SELECT * FROM products WHERE tag = 'software';";
						break;
					case 'sport':
						$sql1 = "SELECT * FROM products WHERE tag = 'sport';";
						break;
					case 'wathch':
						$sql1 = "SELECT * FROM products WHERE tag = 'wathch';";
						break;
					case 'pet':
						$sql1 = "SELECT * FROM products WHERE tag = 'pet';";
						break;
							
				}
			}
			else{
				$sql1 = "SELECT * FROM products";
			}
			$result = mysqli_query($conn, $sql1);
			$result_check = mysqli_num_rows($result);
			$number_of_pages = ceil($result_check / $result_per_page);


			/*************** display pagination accorting to the sort way ***************/
			// if there is a sort way
			if ( isset($_GET['sort']) ) {
				$sort = $_GET['sort'];

				// get current page
				if (! isset($_GET['page'])) {
					$current_page = 1;
				}
				else{
					$current_page = $_GET['page'];
				}

				// if page is not first page
		  		if ( isset($_GET['page']) && $_GET['page'] > 1) {
		  			$previous_page = $_GET['page']-1;
		  			echo '<li class="page-item"><a class="page-link" href="home.php?sort='.$sort.'&page='.$previous_page.'">Previous</a></li>';
		  		}

		  		// echo out every page
		  		for ($i=1; $i <= $number_of_pages; $i++) {
		  			if ($i == $current_page) {
		  				echo '<li class="page-item active"><a class="page-link" href="home.php?sort='.$sort.'&page='.$i.'">'.$i.'</a></li>';
		  			}
		  			else{
		  				echo '<li class="page-item"><a class="page-link" href="home.php?sort='.$sort.'&page='.$i.'">'.$i.'</a></li>';
		  			}
		  		}

		  		// if page is not last page
		  		if (isset($_GET['page']) && $_GET['page'] < $number_of_pages) {
		  			$next_page = $_GET['page']+1;
		  			echo '<li class="page-item"><a class="page-link" href="home.php?sort='.$sort.'&page='.$next_page.'">Next</a></li>';
		  		}



			}
			// if there is no a sort way
			else{

				// get current page
				if (! isset($_GET['page'])) {
					$current_page = 1;
				}
				else{
					$current_page = $_GET['page'];
				}

				// if page is not first page
		  		if (isset($_GET['page']) && $_GET['page'] > 1) {
		  			$previous_page = $_GET['page']-1;
		  			echo '<li class="page-item"><a class="page-link" href="home.php?page='.$previous_page.'">Previous</a></li>';
		  		}

		  		// echo out every page
		  		for ($i=1; $i <= $number_of_pages; $i++) { 
		  			if ($i == $current_page) {
		  				echo '<li class="page-item active"><a class="page-link" href="home.php?page='.$i.'">'.$i.'</a></li>';	
		  			}
		  			else{
		  				echo '<li class="page-item"><a class="page-link" href="home.php?page='.$i.'">'.$i.'</a></li>';
		  			}
		  		}

		  		// if page is not last page
		  		if (isset($_GET['page']) && $_GET['page'] < $number_of_pages) {
		  			$next_page = $_GET['page']+1;
		  			echo '<li class="page-item"><a class="page-link" href="home.php?page='.$next_page.'">Next</a></li>';
		  		}

	  		}

	  	?>
	  </ul>
	</nav>
</div>

	



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


	

</body>
</html>