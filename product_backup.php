<?php

require_once "head.php";
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
    $sql_s_product = "SELECT DISTINCT product_name, product_description, product_price, product_pop, product_rating , product_featured, furniture_type, location_name, physical_properties_id, length, width, height, geometry FROM product NATURAL JOIN furniture NATURAL JOIN physical_properties WHERE product_id = " . $p_id . ";";

    //// Update popularity

    $result = $conn->query($sql_s_product);

    if($result->num_rows > 0) {

        // Update product popularity!
        update_pop($p_id);

        $row = $result->fetch_assoc();

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

        if($promo_name != NULL) {
            ?>
            <h4>Promo Applicable!: <?php echo $promo_name; ?> </h4>
            <?php
        }?>
        <h4>Product Name: <?php echo $p_name; ?> </h4>
        <p>Product Description: <?php echo $p_desc; ?> <br></p>
        <h5>Product Price: <?php echo $p_price; ?> </h5>
        <?php if($promo_name != NULL) {
            ?>
            <h4>New Price!: <?php echo $p_price *= $promo_pp / 100; ?> </h4>
            <?php
        }?>
        <h5>Product Popularity: <?php echo $p_pop; ?> </h5>
        <h5>Product Rating: <?php echo $p_rating; ?> </h5>
        <h5>Product Location: <?php echo $location; ?> </h5>
        <h5>Product Featured: <?php echo $p_featured; ?> </h5>
        <h5>Product Furniture Type: <?php echo $furniture_type; ?> </h5>
        <h5>Product Physical Properties: Length: <?php echo $length; ?> Width: <?php echo $width; ?>  Height: <?php echo $height; ?> Geomtry: <?php echo $geometry; ?>  </h5>
        <h5>Materials: <?php
            foreach ($materials as $mat){

                echo $mat . " ";
            }
            ?></h5>

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
        <h4>Product Name: <?php echo $p_name; ?> </h4>
        <p>Product Description: <?php echo $p_desc; ?> <br></p>
        <h5>Product Price: <?php echo $p_price; ?> </h5>
        <h5>Product Popularity: <?php echo $p_pop; ?> </h5>
        <h5>Product Rating: <?php echo $p_rating; ?> </h5>
        <h5>Product Featured: <?php echo $p_featured; ?> </h5>
        <h5>Product Room Total Area: <?php echo $r_ta; ?> </h5>
        <h5>Product Room Floor Id: <?php echo $rf_id; ?> </h5>
        <h5>Product Location: <?php echo $location; ?> </h5>
        <h5>Product Room Floor Description: <?php echo $rf_description; ?> </h5>
        <h5>Product Room Floor Style: <?php echo $rf_style; ?> </h5>
        <h5>Product Room Components: <?php
            foreach ($room_components as $rc){

                echo $rc . " ";
            }
            ?>
        </h5>

        <h5>Materials: <?php
            foreach ($materials as $mat){

                echo $mat . " ";
            }
            ?></h5>
        <?php
    }
    else{
        echo "Error looking for product!";
    }
}

if (isUser()){
    ?>
    <a href="associate/associate-cart-product-function.php?pid=<?php echo $p_id ?>" class="btn btn-primary">Add to cart!</a>
    <?php
}

require_once "footer.php"; ?>