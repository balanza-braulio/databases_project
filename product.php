<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="CSS/bootstrap.min.css" rel="stylesheet">

    <title>J&C Concepts</title>
</head>

<?php
require_once "CRS/auth.php";
require_once "navbar.php";
require_once "config.php";
require_once "stats/product-popularity-update.php";

// Get request information
$p_id = htmlspecialchars($_GET["pid"]);
$p_mode = htmlspecialchars($_GET["pmode"]);

// Promotion variables if applicable
$promo_name = NULL;
$promo_pp = NULL;

// Begin connection
$conn = db();

//// Check what promotions apply to this product
//Query defintion
$sql_s_promo = "SELECT promotion_name, promotion_price_modifier FROM php NATURAL JOIN promotion WHERE product_id = " . $p_id . ";";

$result_promo = $conn->query($sql_s_promo);

// If there were promotions found
if($result_promo->num_rows > 0){
    $prow = $result_promo->fetch_assoc();
    $promo_name = $prow["promotion_name"];
    $promo_pp = $prow["promotion_price_modifier"];
}

//echo 'Product id: ' . $p_id . 'Product Mode: ' . $p_mode . "! <br>";

// If product is furniture
if ($p_mode == 0) {

    // Array for materials

    // Query definition to get all furniture variables!
    $sql_s_product = "SELECT DISTINCT product_name, product_description, product_price, product_pop, product_rating , product_featured, picture_album_id, furniture_type, location_name, physical_properties_id, length, width, height, geometry FROM product NATURAL JOIN furniture NATURAL JOIN physical_properties WHERE product_id = " . $p_id . ";";

    //// Update popularity

    $result = $conn->query($sql_s_product);

    if($result->num_rows > 0) {

        // Update product popularity!
        update_pop($p_id);

        $row = $result->fetch_assoc();

        //Get pictures
        // Query definitions
        $sql_s_cover = "SELECT * FROM picture_album WHERE picture_album_id = " . $row["picture_album_id"] .  " ; ";

        // Get the pictures
        $pic_select = $conn->query($sql_s_cover);
        $picture_row = $pic_select->fetch_assoc();
        $pic_cover = $picture_row["pic_cover"];
        $pic_front =$picture_row["pic_front"];
        $pic_side = $picture_row["pic_side"];
        $pic_back = $picture_row["pic_back"];

        // Defining attributes into nice variable names
        $p_name = $row["product_name"];
        $p_desc = $row["product_description"];
        $p_price = $row["product_price"];
        $p_pop = $row["product_pop"];
        $p_rating = $row["product_rating"];
        $p_featured = $row["product_featured"];
        $furniture_type = $row["furniture_type"];
        $location = $row["location_name"];
        $phy_id = $row["physical_properties_id"];
        $length = $row["length"];
        $width = $row["width"];
        $height = $row["height"];
        $geometry = $row["geometry"];

        $sql_s_phy = "SELECT DISTINCT material_name FROM pp_h_m NATURAL JOIN material WHERE physical_properties_id = " . $phy_id . ";";

        $result_mat = $conn->query($sql_s_phy);
        // Variable to keep track of rows
        $row_num = 0;

        if($result_mat->num_rows > 0) {
            $materials = array_fill(0, $result_mat->num_rows, 'value');

            while($row_mat = $result_mat->fetch_assoc()){
                $materials[$row_num] =  $row_mat["material_name"];
                $row_num++;
            }
        }
        ?>

        <!--Main layout-->
        <main class="mt-5 pt-4">
            <div class="container dark-grey-text mt-5">
                <div class="row justify-content-md-center text-center">
                    <?php
                    if($promo_name != NULL) {
                    ?>
                    <h1>Promo Applicable!: <?php echo $promo_name; ?> </h1>
                    <?php
                    }?>
                    <div class="col">
                        <h2><?php echo $p_name; ?> </h2><br>
                    </div>
                </div>
                <!--Grid row-->
                <div class="row wow fadeIn">

                    <!--Grid column-->
                    <div class="col-md-6 mb-4">

                        <img src="<?php echo $pic_cover;?>" class="img-fluid rounded-circle" alt="">

                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-md-6 mb-4">

                        <!--Content-->
                        <div class="p-4">

                            <div class="mb-3">
                                <h4>Price:</h4>

                                <p class="lead">
              <span class="mr-1"><?php
                   if($promo_name != NULL){
                       ?><del>$<?php echo $p_price; ?></del>
                       <span>$<?php echo $p_price *= $promo_pp / 100; ?></span><?php
                   }
                   else{
                       ?><span>$<?php echo $p_price; ?></span><?php
                   }?>
              </span>
                                </p>

                                <p class="lead font-weight-bold">Description</p>

                                <p><?php echo $p_desc; ?>
                                <form class="d-flex justify-content-left">
                                    <!-- Default input -->
                                    <?php if (isUser()){
                                        ?>
                                        <a href="associate/associate-cart-product-function.php?pid=<?php echo $p_id ?>" class="btn btn-primary btn-md my-0 p">Add to cart!
                                            <i class="fa fa-shopping-cart ml-1"></i>
                                        </a>
                                        <?php
                                    }
                                    ?>

                                </form>

                            </div>
                            <!--Content-->

                        </div>
                        <!--Grid column-->

                    </div>
                    <!--Grid row-->

                    <hr>

                    <!--Grid row-->
                    <div class="row d-flex justify-content-center wow fadeIn">

                        <!--Grid column-->
                        <div class="col-md-6 text-center">

                            <h4 class="my-4 h4">Additional information</h4>

                            <p><?php echo $p_desc?></p>

                            <h4 class="my-4 h4">Physical Properties</h4>

                            <p class="text-center">Length: <?php echo $length; ?></p>
                            <p class="text-center">Width: <?php echo $width; ?></p>
                            <p class="text-center">Height: <?php echo $height; ?></p>
                            <p class="text-center">Geometry: <?php echo $geometry; ?></p>

                            <h4 class="my-4 h4">Location and Type</h4>

                            <p class="text-center">Location: <?php echo $location; ?></p>
                            <p class="text-center">Furniture Type: <?php echo $furniture_type; ?></p>

                            <h4 class="my-4 h4">Materials:</h4>
                            <p>
                                <?php
                                foreach ($materials as $mat){

                                    echo $mat . " ";
                                }
                                ?>
                            </p>
                        </div>
                        <!--Grid column-->

                    </div>
                    <!--Grid row-->

                    <!--Grid row-->
                    <div class="row wow fadeIn">

                        <!--Grid column-->
                        <div class="col-lg-4 col-md-12 mb-4">

                            <img src="<?php echo $pic_front;?>" class="img-fluid rounded-circle" alt="">

                        </div>
                        <!--Grid column-->

                        <!--Grid column-->
                        <div class="col-lg-4 col-md-6 mb-4">

                            <img src="<?php echo $pic_side;?>" class="img-fluid rounded-circle" alt="">

                        </div>
                        <!--Grid column-->

                        <!--Grid column-->
                        <div class="col-lg-4 col-md-6 mb-4">

                            <img src="<?php echo $pic_back;?>" class="img-fluid rounded-circle" alt="">

                        </div>
                        <!--Grid column-->

                    </div>
                    <!--Grid row-->

                </div>
        </main>
        <!--Main layout-->

<?php

}
else{

    echo "Error looking for product!";
}
}
else if ($p_mode == 1) {

// Query definition
$sql_s_product = "SELECT DISTINCT * FROM (product NATURAL JOIN room NATURAL JOIN room_floor)WHERE product_id = " . $p_id . ";";
//echo $sql_s_product;

$result = $conn->query($sql_s_product);

if($result->num_rows > 0) {

// Update product popularity!
update_pop($p_id);

$row = $result->fetch_assoc();

    //Get pictures
    // Query definitions
    $sql_s_cover = "SELECT * FROM picture_album WHERE picture_album_id = " . $row["picture_album_id"] .  " ; ";

    // Get the pictures
    $pic_select = $conn->query($sql_s_cover);
    $picture_row = $pic_select->fetch_assoc();
    $pic_cover = $picture_row["pic_cover"];
    $pic_front =$picture_row["pic_front"];
    $pic_side = $picture_row["pic_side"];
    $pic_back = $picture_row["pic_back"];


// Defining attributes into nice variable names
$p_name = $row["product_name"];
$p_desc = $row["product_description"];
$p_price = $row["product_price"];
$p_pop = $row["product_pop"];
$p_rating = $row["product_rating"];
$p_featured = $row["product_featured"];
$r_ta = $row["room_total_area"];
$rf_id = $row["room_floor_id"];
$location = $row["location_name"];
$rf_description = $row["room_floor_description"];
$rf_style = $row["room_floor_style"];

// Select all room components' physical properties
$sql_s_rc = "SELECT room_component_name, physical_properties_id FROM (r_h_rc NATURAL JOIN room_component)WHERE product_id = " . $p_id . ";";

$result_rcphys = $conn->query($sql_s_rc);

if($result_rcphys->num_rows > 0) {

    // Array and counter for room components
    $room_components = array_fill(0, $result_rcphys->num_rows, 'value');
    $rc_row = 0;

    $row = $result_rcphys->fetch_assoc();

    // Initialize first room component
    $room_components[$rc_row++] = $row["room_component_name"];

    $phy_id = $row["physical_properties_id"];

    //// Select all the materials contained in the room!
    // Query definition
    $sql_s_room_mat = "SELECT DISTINCT material_name FROM rf_h_m NATURAL JOIN material WHERE room_floor_id = " . $rf_id . " UNION (SELECT DISTINCT material_name FROM pp_h_m NATURAL JOIN material WHERE physical_properties_id = " . $phy_id;

    while ($row = $result_rcphys->fetch_assoc()){

        $room_components[$rc_row++] = $row["room_component_name"];
        $phy_id = $row["physical_properties_id"];
        $sql_s_room_mat = $sql_s_room_mat . " OR physical_properties_id = " . $phy_id;

    }
    $sql_s_room_mat = $sql_s_room_mat . ");";

    // Save materials found to an array
    $result_mat = $conn->query($sql_s_room_mat);
    // Variable to keep track of rows
    $row_num = 0;

    if($result_mat->num_rows > 0) {
        $materials = array_fill(0, $result_mat->num_rows, 'value');

        while($row_mat = $result_mat->fetch_assoc()){
            $materials[$row_num] =  $row_mat["material_name"];
            $row_num++;
        }
    }
}

?>
    <!--Main layout-->
    <main class="mt-5 pt-4">
        <div class="container dark-grey-text mt-5">
            <div class="row justify-content-md-center text-center">
                <?php
                if($promo_name != NULL) {
                    ?>
                    <h1>Promo Applicable!: <?php echo $promo_name; ?> </h1>
                    <?php
                }?>
                <div class="col">
                    <h2><?php echo $p_name; ?> </h2><br>
                </div>
            </div>
            <!--Grid row-->
            <div class="row wow fadeIn">

                <!--Grid column-->
                <div class="col-md-6 mb-4">

                    <img src="<?php echo $pic_cover;?>" width="500px" height="500px" class="rounded-circle" alt="">

                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-md-6 mb-4">

                    <!--Content-->
                    <div class="p-4">

                        <div class="mb-3">
                            <h4>Price:</h4>

                            <p class="lead">
              <span class="mr-1"><?php
                  if($promo_name != NULL){
                      ?><del>$<?php echo $p_price; ?></del>
                      <span>$<?php echo $p_price *= $promo_pp / 100; ?></span><?php
                  }
                  else{
                      ?><span>$<?php echo $p_price; ?></span><?php
                  }?>
              </span>
                            </p>

                            <p class="lead font-weight-bold">Description</p>

                            <p><?php echo $p_desc; ?>
                            <form class="d-flex justify-content-left">
                                <!-- Default input -->
                                <?php if (isUser()){
                                    ?>
                                    <a href="associate/associate-cart-product-function.php?pid=<?php echo $p_id ?>" class="btn btn-primary btn-md my-0 p">Add to cart!
                                        <i class="fa fa-shopping-cart ml-1"></i>
                                    </a>
                                    <?php
                                }
                                ?>

                            </form>

                        </div>
                        <!--Content-->

                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

                <hr>

                <!--Grid row-->
                <div class="row d-flex justify-content-center wow fadeIn">

                    <!--Grid column-->
                    <div class="col-md-6 text-center">

                        <h4 class="my-4 h4">Additional information</h4>

                        <p><?php echo $p_desc?></p>

                        <h4 class="my-4 h4">Product Room Total Area: <?php echo $r_ta; ?> ft.</h4>

                        <h4 class="my-4 h4">Room Type</h4>

                        <p class="text-center">Location: <?php echo $location; ?></p>

                        <h4 class="my-4 h4">Room Floor Details</h4>

                        <p>Room Floor Style: <?php echo $rf_style; ?> </p>
                        <p><?php echo $rf_description; ?> </p>

                        <h4 class="my-4 h4">Room Components:</h4>
                        <p><?php
                            foreach ($room_components as $rc){

                                echo $rc . " ";
                            }
                            ?>
                        </p>

                        <h4 class="my-4 h4">Materials:</h4>
                        <p>
                            <?php
                            foreach ($materials as $mat){

                                echo $mat . " ";
                            }
                            ?>
                        </p>
                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row wow fadeIn">

                    <!--Grid column-->
                    <div class="col-lg-4 col-md-12 mb-4">

                        <img src="<?php echo $pic_front;?>" width="400px" height="400px" class="rounded-circle" alt="">

                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-lg-4 col-md-6 mb-4">

                        <img src="<?php echo $pic_side;?>" width="400px" height="400px" class="rounded-circle" alt="">

                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-lg-4 col-md-6 mb-4">

                        <img src="<?php echo $pic_back;?>" width="400px" height="400px" class="rounded-circle" alt="">

                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

            </div>
    </main>
    <!--Main layout-->
<?php
}
else{

    echo "Error looking for product!";
}
}
?>
<!-- SCRIPTS -->
<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="js/mdb.min.js"></script>
<!-- Initializations -->
<script type="text/javascript">
    // Animations initialization
    new WOW().init();
</script>
</body>

</html>