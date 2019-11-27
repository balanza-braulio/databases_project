<!--Include header-->
<?php require_once "head.php"?>
<body>

<!--  Navbar shit include -->
  <?php require_once "navbar.php"?>


    <!-- Container for main carousels -->
    <div class="container">
      <!-- FEATURED card -->
      <div class="card mx-auto text-center mt-4" style="width: 30rem; border-style: none">
        <div class="card-body">
          <h5 class="card-title">Featured</h5>
          <p class="card-text">Check out some of our hottest items</p>
          <a href="featured.php" class="btn btn-primary">Go to Featured</a>
        </div>
      </div>

      <!-- Carousel/Slideshow.  Use php script to load pictures? -->
      <div id="carouselSlides" class="carousel slide" data-ride="carousel">
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
      </div>

      <hr> <!--visible break line -->

      <!-- NEW card -->
      <div class="card mx-auto text-center mt-4" style="width: 30rem; border-style: none">
        <div class="card-body">
          <h5 class="card-title">New</h5>
          <p class="card-text">See our newest products</p>
          <a href="new.php" class="btn btn-primary">Go to New</a>
        </div>
      </div>

      <!-- Carousel/Slideshow -->
      <div id="carouselSlides2" class="carousel slide" data-ride="carousel">
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
      </div>
    </div>


<?php require_once "footer.php"; ?>
