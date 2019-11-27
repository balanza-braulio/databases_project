<!--Include header-->
<?php require_once "head.php"?>
  <!--  Navbar shit include -->
  <?php require_once "navbar.php"?>
  <!-- end of navigation -->

  <?php

  // Database query

  require_once "config.php";
  // Query definition
  $sql_s_feat = "SELECT product_id,product_name,product_description,picture_album_id,product_mode FROM product WHERE product_featured = 1 ORDER BY product_created_at DESC LIMIT 12;";

  $conn = db();

  if($conn) {

      $pop_products = $conn->query($sql_s_feat);

      $col_count = 0;



  ?>

    <div class="jumbotron">
      <h1 class="display-4">Featured Products</h1>
      <p class="lead">Don't miss out on the best offers on the best items.</p>
      <hr class="my-4">
    </div>

    <!-- start of carousel -->
    <!-- FEATURED card -->
    <div class="container w-50">
      <!-- Carousel/Slideshow -->
      <div id="carouselSlides" class="carousel slide" data-ride="carousel">
        <hr> <p class="text-center font-weight-bold"> Top Featured Products </p> <hr>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="d-block w-100" src="la.jpg" alt="First slide">
          </div>
          <div class="carousel-item">
            <img class="d-block w-100" src="chicago.jpg" alt="Second slide">
          </div>
          <div class="carousel-item">
            <img class="d-block w-100" src="la.jpg" alt="Third slide">
          </div>
        </div>
      </div> <!-- end of carousel -->
    </div> <!-- end of container -->


  <div id="featuredProducts" class="container text-center mt-5">
      <hr>
      <?php

      if ($pop_products->num_rows > 0) {
          while($row = $pop_products->fetch_assoc()){

              $p_id = $row["product_id"];
              $p_name = $row["product_name"];
              $p_description = $row["product_description"];
              $p_mode = $row["product_mode"];

              // Query definitions
              $sql_s_cover = "SELECT pic_cover FROM picture_album WHERE picture_album_id = " . $row["picture_album_id"] .  " ; ";

              $cover_select = $conn->query($sql_s_cover);
              $picture_cover = $cover_select->fetch_assoc()["pic_cover"];



              if ($col_count === 0) {

                  ?>  <div class="card-deck">
                  <?php

              }

              ?>
              <div class="col-auto mb-3">
                  <div class="card" style="width:20rem;margin:20px 0 24px 0">
                      <div class="card-body">
                          <img class="card-img-top" src="<?php echo $picture_cover; ?>" alt="test_cover" width="250px" height="250px">
                          <h4 class="card-title"><?php echo $p_name ?> </h4>
                          <p class="card-text"><?php echo $p_description ?> </p>
                          <a  href='product.php?pid=<?php echo $p_id;?>&pmode=<?php echo $p_mode;?>' class="btn btn-primary">View product!</a>
                      </div>
                  </div>
              </div>
              <?php

              if ($col_count === 2) {
                  ?>  </div> <?php
              }

              $col_count++;

              if ($col_count > 2){
                  $col_count = 0;
              }
          }
          ?>

          </div> <?php
      }
      else {
          ?>

          <div class="jumbotron">
              <h1> Oops looks like there are no featured products yet!</h1>
              <p>Please make another search!</p>
              <a class="btn btn-primary" href="index.php">Go Back to Home Page!</a>
          </div>

          <?php
      }
  }
  else{

      echo "Failure connecting to database";
  }

  require_once "footer.php";
  ?>

