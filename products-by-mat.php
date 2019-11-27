<?php

require_once "config.php";
require_once "head.php";
require_once "navbar.php";

$p_mat = htmlspecialchars($_GET["pmat"]);
$p_mode = htmlspecialchars($_GET["pmode"]);

$conn = db();

// If product is furniture, show only furniture
if ($p_mode == 0) {

    //Query definition
    $sql_s_prod = "SELECT DISTINCT product_id, product_name, product_description, picture_album_id, product_mode FROM product NATURAL JOIN furniture NATURAL JOIN (SELECT DISTINCT physical_properties_id FROM pp_h_m NATURAL JOIN material WHERE material_name = '" . $p_mat . "') AS E;";

    //echo $sql_s_prod;

    $products = $conn->query($sql_s_prod);

    $col_count = 0;

    if ($products->num_rows > 0) {

        ?>          <div id="popularProducts" class="container text-center mt-5">

        <?php

        while ($row = $products->fetch_assoc()) {

            $p_id = $row["product_id"];
            $p_name = $row["product_name"];
            $p_description = $row["product_description"];
            $p_mode = $row["product_mode"];
            $pa_id = $row["picture_album_id"];

            // Query definitions
            $sql_s_cover = "SELECT pic_cover FROM picture_album WHERE picture_album_id = " . $pa_id . " ; ";
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
                        <img class="card-img-top" src="<?php echo $picture_cover; ?>" alt="test_cover" style="width:100%">
                        <h4 class="card-title"><?php echo $p_name ?> </h4>
                        <p class="card-text"><?php echo $p_description ?> </p>
                        <a href='product.php?pid=<?php echo $p_id; ?>&pmode=<?php echo $p_mode; ?>' class="btn btn-primary">View
                            product!</a>
                    </div>
                </div>
            </div>
            <?php

            if ($col_count === 2) {
                ?>  </div> <?php
            }

            $col_count++;

            if ($col_count > 2) {
                $col_count = 0;
            }
        }
        ?></div>
        <?php
    }
    else{ ?>

        <div class="jumbotron">
            <h1> Oops looks like there are no furniture products with that material</h1>
            <p>Please make another search!</p>
            <a class="btn btn-primary" href="materials-of-furniture.php">Go Back to Materials!</a>
        </div>

        <?php
    }
}

// If product is room, show only rooms
else if($p_mode == 1){

    //// Select all the materials contained in the room!
    // Horrible, inefficient query definition to find all products that contain a specific material
    $sql_s_prod = "(SELECT DISTINCT product_id, product_name, product_description, product_mode, picture_album_id FROM (product NATURAL JOIN room NATURAL JOIN r_h_rc NATURAL JOIN room_component NATURAL JOIN pp_h_m NATURAL JOIN material) WHERE material_name = '" . $p_mat . "') UNION (SELECT DISTINCT product_id, product_name, product_description, product_mode, picture_album_id FROM (product NATURAL JOIN room NATURAL JOIN room_floor JOIN rf_h_m USING (room_floor_id) NATURAL JOIN material) WHERE material_name = '" . $p_mat . "');";

    $products = $conn->query($sql_s_prod);

    if ($products->num_rows > 0) {

        ?>          <div id="popularProducts" class="container text-center mt-5">

            <?php

            $col_count = 0;

            while ($row = $products->fetch_assoc()) {

                $p_id = $row["product_id"];
                $p_name = $row["product_name"];
                $p_description = $row["product_description"];
                $p_mode = $row["product_mode"];
                $pa_id = $row["picture_album_id"];

                // Query definitions
                $sql_s_cover = "SELECT pic_cover FROM picture_album WHERE picture_album_id = " . $pa_id . " ; ";
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
                            <img class="card-img-top" src="<?php echo $picture_cover; ?>" alt="test_cover" style="width:100%">
                            <h4 class="card-title"><?php echo $p_name ?> </h4>
                            <p class="card-text"><?php echo $p_description ?> </p>
                            <a href='product.php?pid=<?php echo $p_id; ?>&pmode=<?php echo $p_mode; ?>' class="btn btn-primary">View
                                product!</a>
                        </div>
                    </div>
                </div>
                <?php

                if ($col_count === 2) {
                    ?>  </div> <?php
                }

                $col_count++;

                if ($col_count > 2) {
                    $col_count = 0;
                }
            }
            ?></div>
        <?php
    }
    else{ ?>

        <div class="jumbotron">
            <h1> Oops looks like there are no room products with that material</h1>
            <p>Please make another search!</p>
            <a class="btn btn-primary" href="materials-of-rooms.php">Go Back to Materials!</a>
        </div>

        <?php
    }


}

?>

<?php require_once "footer.php";?>