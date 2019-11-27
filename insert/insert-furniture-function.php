<?php
// Include config file
require_once "../config.php";
//// Materials to be matched to the furniture
//$materials = array("Oak", "Red Oak", "Plywood");
//$P_id = 1;
//
//// Furniture elements
//$ft = 'Desk';
//
//// Physical Properties elements
//$l = 8;
//$w = 8;
//$h = 8;
//$g = 'Hexagon';
//$P_id = 5;

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}


function insert_furniture($materials, $P_id, $ft, $l, $w, $h, $g)
{
    $conn = db();



    // General query definition
    $L_I_PHP = "SELECT physical_properties_id FROM physical_properties ORDER BY physical_properties_id DESC LIMIT 1";
    $L_I_F = "SELECT product_id FROM furniture ORDER BY product_id DESC LIMIT 1";

    $PHP_id = NULL;
    $F_id = NULL;

// Query definition
    $sql_i_phyp = "INSERT INTO physical_properties (length, width, height, geometry) VALUES (" . $l . ", " . $w . ", " . $h . ", '". $g . "');";

// Start transaction
    //$conn->begin_transaction();

// Insert room components physical properties!
    $phypInsert = $conn->query($sql_i_phyp);

    if ($phypInsert === TRUE) {
        //echo "Physical properties created successfully! <br>";

        $last_phy_insert = $conn->query($L_I_PHP);
        $PHP_id = $last_phy_insert->fetch_assoc()["physical_properties_id"];

    }
    else {

        ?>
        <div class="jumbotron">
            <h1> Oops looks like there was an error creating physical properties!</h1>
            <p><?php echo  $conn->error; ?></p>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Try Again!</a>
        </div>

        <?php

        $conn->rollback();
    }

// Query definition
    $sql_i_f = "INSERT INTO furniture (product_id ,furniture_type, physical_properties_id) VALUES (" . $P_id . ", '" . $ft . "', " . $PHP_id . ");";


// Test rollback
//$conn->rollback();

    if ($conn->query($sql_i_f) === TRUE) {
        //echo $ft . " created successfully! <br>";

        // Selects last inserted id
        $F_id = $conn->query($L_I_F)->fetch_assoc()["product_id"];

        // Test print room component id
        //echo "Furniture id: " . $F_id . "<br>";

        // Associate physical_properties to materials
        if (sizeof($materials) >= 0) {

            // Query definition to look for materials
            $sql_s_m = "SELECT material_id FROM material WHERE material_name ";

            $mat_index = 0;
            foreach ($materials as $mat) {
                if ($mat_index == 0){
                    $sql_s_m =  $sql_s_m . " = '" . $mat . "'";
                }
                else {
                    $sql_s_m = $sql_s_m . " OR material_name = '" . $mat . "'";
                }
                $mat_index++;
            }
            $sql_s_m = $sql_s_m . ";";

            // Test print query
            //echo $sql_s_m . "<br>";

            // Execute query and associate in pp_h_m
            $materials_id = $conn->query($sql_s_m);

            // Test print rows of query
           // echo $materials_id->num_rows . "<br>";

            if ($materials_id->num_rows > 0 && !($materials_id->num_rows < sizeof($materials)) ) {

                /* fetch results */
                while ($row = $materials_id->fetch_assoc()) {

                    // Query associating material to physical properties!
                    $sql_a_pphm = "INSERT INTO pp_h_m (physical_properties_id, material_id) VALUES (". $PHP_id . ", " . $row["material_id"] .");";

                    // Associate values
                    if($conn->query($sql_a_pphm) === TRUE){

                        // Test print material ids to be associated to room component
                        //echo "Material ids found to be associated: " . $row["material_id"] . "<br>";
                    }
                    else{

                        ?>
                        <div class="jumbotron">
                            <h1> Oops looks like there was an error associating physical properties to materials!</h1>
                            <p><?php echo  $conn->error; ?></p>
                            <p>Try again!</p>
                            <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Try Again!</a>
                        </div>

                        <?php

                        $conn->rollback();
                    }
                }

            } else if ($materials_id->num_rows < sizeof($materials)) {

                ?>

                <div class="jumbotron">
                    <h1> Oops looks like not all materials were found!</h1>
                    <p>Try again!</p>
                    <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Try Again!</a>
                </div>

                <?php

                $conn->rollback();

            } else {

                ?>

                <div class="jumbotron">
                    <h1> Oops looks like there are no materials defined for this furniture!</h1>
                    <p>Try again!</p>
                    <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Try Again!</a>
                </div>

                <?php

                $conn->rollback();
            }


            //$conn->commit();
            return $F_id;
        }
        // Error if there were no materials passed to function
        else{

            ?>
            <div class="jumbotron">
                <h1> Oops looks like there are no materials defined for this furniture!</h1>
                <p>Try again!</p>
                <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Try Again!</a>
            </div>

            <?php
            $conn->rollback();
        }
    }
    else {

        ?>
        <div class="jumbotron">
            <h1> Oops looks like there was an error creating this furniture!</h1>
            <p><?php echo  $conn->error; ?></p>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Try Again!</a>
        </div>

        <?php

        $conn->rollback();
    }

    return NULL;

}
// Test
//insert_furniture($materials, $P_id, $ft, $fl, $l, $w, $h, $g);

