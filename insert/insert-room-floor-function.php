<?php

require_once "../config.php";
//// Test elements
//$rfd = 'This is a dummy description!';
//$rft = 'Solid';
//
//// Materials to be matched to room floor
//$materials = array("Oak", "Red Oak", "Plywood");

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}

function insert_room_floor($materials, $rfd, $rft){

    // Connect to database and start transaction
    $conn = db();
    //$conn->begin_transaction();

    // Room floor id
    $RF_id = NULL;

    //Query definition
    $sql_i_rf = "INSERT INTO room_floor(room_floor_description, room_floor_style) VALUES ('" . $rfd . "', '" . $rft . "')";
    $L_I_RF = "SELECT room_floor_id FROM room_floor ORDER BY room_floor_id DESC LIMIT 1";

    //Insert query execution
    $room_floor_insert = $conn->query($sql_i_rf);

    if ($room_floor_insert === TRUE){

        // Get the id of the last inserted room floor
        $last_rf_insert = $conn->query($L_I_RF);
        $RF_id = $last_rf_insert->fetch_assoc()["room_floor_id"];

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

        // Execute query and associate in rf_h_m
        $materials_select = $conn->query($sql_s_m);

        // Test print rows of query
        //echo $materials_select->num_rows . "<br>";

        if ($materials_select->num_rows > 0 && !($materials_select->num_rows < sizeof($materials)) ) {

            /* fetch results */
            while ($row = $materials_select->fetch_assoc()) {

                // Query associating material to physical properties!
                $sql_a_rfhm = "INSERT INTO rf_h_m (room_floor_id, material_id) VALUES (". $RF_id . ", " . $row["material_id"] .");";

                // Associate values
                if($conn->query($sql_a_rfhm) === TRUE){

                    // Test print material ids to be associated to room component
                    //echo "Material ids found to be associated: " . $row["material_id"] . "<br>";
                }
                else{

                    ?>
                    <div class="jumbotron">
                        <h1> Oops looks like there was an error associating room floor to materials!</h1>
                        <p><?php echo  $conn->error; ?></p>
                        <p>Try again!</p>
                        <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
                    </div>

                    <?php

                    $conn->rollback();

                    return NULL;
                }
            }

        }
        else if ($materials_select->num_rows < sizeof($materials)) {

            ?>
            <div class="jumbotron">
                <h1> Oops looks like not all materials were found!</h1>
                <p>Try again!</p>
                <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
            </div>

            <?php

            $conn->rollback();

            return NULL;

        }
        else {

            ?>
            <div class="jumbotron">
                <h1> Oops looks like there was an error when looking for materials to add to room floor!</h1>
                <p><?php echo  $conn->error; ?></p>
                <p>Try again!</p>
                <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
            </div>

            <?php
            $conn->rollback();

            return NULL;
        }

        return $RF_id;
    }
    else{

        ?>
        <div class="jumbotron">
            <h1> Oops looks like there was an error when creating a room floor!</h1>
            <p><?php echo  $conn->error; ?></p>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
        </div>

        <?php

        $conn->rollback();

        return NULL;
    }
}

// Test
//insert_room_floor($materials, $rfd, $rft);
?>
