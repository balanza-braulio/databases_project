<?php
// Include config file
require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";
//// Materials to be matched to the room component
//$materials = array("Oak", "Red Oak", "Plywood");
//
//// Room component elements
//$rcn = 'Oak Cabinet';
//$rcd = 'This is a dummy description';
//$rct = 'Cabinet';
//
//// Physical Properties elements
//$l = 8;
//$w = 8;
//$h = 8;
//$g = 'Hexagon';

//var_dump($_POST['materials']);
$materials = $_POST['materials'];

$rcn = htmlspecialchars($_POST["rcname"]);
$rcd = htmlspecialchars($_POST["rcdes"]);
$rct = htmlspecialchars($_POST["rctype"]);

$l = htmlspecialchars($_POST["l"]);
$w = htmlspecialchars($_POST["w"]);
$h = htmlspecialchars($_POST["h"]);
$g = htmlspecialchars($_POST["g"]);


//// Test
//echo $rcn . $rcd . $rct . $l . $w . $h . $g;
//
//foreach ($materials as $mat)
//    echo $mat;

function insert_room_component($materials, $rcn, $rcd, $rct, $l, $w, $h, $g){

    $conn = db();
    $RC_id = NULL;

// This php code takes in a room component type, a room component description, physical properties length, width, height,
// and geometry and and array of materials and creates the appropriate instance!

// General query definition
    $L_I_PHY = "SELECT physical_properties_id FROM physical_properties ORDER BY physical_properties_id DESC LIMIT 1";
    $L_I_RC = "SELECT room_component_id FROM room_component ORDER BY room_component_id DESC LIMIT 1";

    $PHP_id = NULL;

// Query definition
    $sql_i_phyp = "INSERT INTO physical_properties (length, width, height, geometry) VALUES (" . $l . ", " . $w . ", " . $h . ", '". $g . "');";

// Start transaction
    $conn->begin_transaction();

// Insert room components physical properties!
    $phypInsert = $conn->query($sql_i_phyp);

    if ($phypInsert === TRUE) {

        //echo "Physical properties created successfully! <br>";

        $last_phy_insert = $conn->query($L_I_PHY);
        $PHP_id = $last_phy_insert->fetch_assoc()["physical_properties_id"];

    }
    else {
        //echo "Error: Physical Properties <br>" . $conn->error;
        $conn->rollback();
?>
        <div class="jumbotron">
            <h1> Oops looks like there was an error inserting the physical properties of this room component!</h1>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/insert-room-component.php">Try Again!</a>
        </div>

<?php
        return NULL;
    }

// Test print physical properties id
   // echo "Physical Properties id: " . $PHP_id . "<br>";

// Query definition
    $sql_i_rc = "INSERT INTO room_component (room_component_name, room_component_description, room_component_type, physical_properties_id) VALUES ('" . $rcn . "', '" . $rcd . "', '" . $rct . "' ," . $PHP_id . ");";

    if ($conn->query($sql_i_rc) === TRUE) {
        //echo $rct . " created successfully! <br>";

        $RC_id = $conn->query($L_I_RC)->fetch_assoc()["room_component_id"];

        // Test print room component id
        //echo "Room component id: " . $RC_id . "<br>";

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
            //echo $materials_id->num_rows . "<br>";

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
                            <h1> Oops looks like there was an error associating the physical properties of this room component to the materials!</h1>
                            <p><?php echo  $conn->error; ?></p>
                            <p>Try again!</p>
                            <a class="btn btn-primary" href="../forms/insert-room-component.php">Try Again!</a>
                        </div>

                        <?php

                        $conn->rollback();
                        return NULL;
                    }
                }

            } else if ($materials_id->num_rows < sizeof($materials)) {


                ?>
                <div class="jumbotron">
                    <h1> Oops looks like there was an error associating the physical properties of this room component to the materials, not all materials selected were found!</h1>
                    <p>Try again!</p>
                    <a class="btn btn-primary" href="../forms/insert-room-component.php">Try Again!</a>
                </div>

                <?php
                $conn->rollback();

                return NULL;

            } else {

                ?>
                <div class="jumbotron">
                    <h1> Oops looks like there was an error associating the physical properties of this room component to the materials, error when looking for materials to add</h1>
                    <p>Try again!</p>
                    <a class="btn btn-primary" href="../forms/insert-room-component.php">Try Again!</a>
                </div>
                <?php
                $conn->rollback();

                return NULL;
            }

            ?>
            <div class="jumbotron">
                <h1>Congratulations! New room component added to the database!</h1>
                <p>ID: <?php echo $RC_id?></p>
                <a class="btn btn-primary" href="../forms/insert-room-component.php">Add another one?</a>
            </div>
            <?php

            $conn->commit();
            return $RC_id;
        }
        // Error if there were no materials passed to function
        else{
            ?>
            <div class="jumbotron">
                <h1> Oops looks like you are trying to add a material with no materials!</h1>
                <p>Try again!</p>
                <a class="btn btn-primary" href="../forms/insert-room-component.php">Try Again!</a>
            </div>
            <?php

            $conn->rollback();

            return NULL;
        }
    }
    else {

        ?>
        <div class="jumbotron">
            <h1> Oops looks like error creating the room component!</h1>
            <p><?php echo $conn->error; ?></p>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/insert-room-component.php">Try Again!</a>
        </div>
        <?php
        $conn->rollback();

        return NULL;
    }

}

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}
else{

    // Not test
    insert_room_component($materials, $rcn, $rcd, $rct, $l, $w, $h, $g);
}

require_once "../footer.php";
