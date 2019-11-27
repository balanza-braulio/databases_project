<?php

require_once "../config.php";
require_once "insert-room-floor-function.php";


//// Test elements
//$rta = 100;
//$rl = 'Kitchen';
//$P_id = 34;
//
//$rc = array("Red Wood Shelf", "Plywood Bar");
//$rfm = array("Oak", "Red Oak", "Plywood");
//
//$rfd = "This is a dummy description";
//$rft = "Solid";
//
////$RF_id = insert_room_floor($rfm, $rfd, $rft);
//$RF_id = 10;

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}

function insert_room($P_id, $rta, $rc, $RF_id){

    // Initialization
    $R_id = NULL;

    if($RF_id !== NULL)
    {
        $conn = db();

        //$conn->begin_transaction();

        // Query definition
        $sql_i_r = "INSERT INTO room (product_id, room_total_area, room_floor_id) VALUES (" . $P_id . ", " . $rta . " , " . $RF_id . ")";
        $L_I_R = "SELECT product_id FROM room ORDER BY product_id DESC LIMIT 1";

        $room_insert = $conn->query($sql_i_r);

        if($room_insert === TRUE){

            // Fetch last inserted id!
            $last_r_insert = $conn->query($L_I_R);
            $R_id = $last_r_insert->fetch_assoc()["product_id"];


            //// Associate room with corresponding room components!
            ///
            // Query definition to look for room components
            $sql_s_rc = "SELECT room_component_id FROM room_component WHERE room_component_name ";

            $rc_index = 0;

            foreach ($rc as $c) {
                if ($rc_index == 0){
                    $sql_s_rc =  $sql_s_rc . " = '" . $c . "'";
                }
                else {
                    $sql_s_rc = $sql_s_rc . " OR room_component_name = '" . $c . "'";
                }
                $rc_index++;
            }
            $sql_s_rc = $sql_s_rc . ";";

            //echo $sql_s_rc . "<br>";

            // Execute query and associate in pp_h_m
            $rc_searched = $conn->query($sql_s_rc);

            // Test print rows of query
            //echo $rc_searched->num_rows . "<br>";
            //echo $rc_searched->fetch_assoc()["room_component_id"];

            if ($rc_searched->num_rows > 0 && !($rc_searched->num_rows < sizeof($rc)) ) {

                /* fetch results */
                while ($row = $rc_searched->fetch_assoc()) {

                    // Query associating material to physical properties!
                    $sql_a_rc = "INSERT INTO r_h_rc (product_id, room_component_id) VALUES (". $P_id . ", " . $row["room_component_id"] . ");";

                    // Test print query
                    //echo $sql_a_rc;

                    // Associate values
                    if($conn->query($sql_a_rc) === TRUE){

                        // Test print material ids to be associated to room component
                        //echo "Room component ids found to be associated: " . $row["room_component_id"] . "<br>";
                    }
                    else{

                        ?>
                        <div class="jumbotron">
                            <h1> Oops looks like there was an error when associating room to room components!</h1>
                            <p><?php echo  $conn->error; ?></p>
                            <p>Try again!</p>
                            <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
                        </div>

                        <?php

                        $conn->rollback();
                    }
                }
            }
            else if ($rc_searched->num_rows < sizeof($rc)) {

                ?>
                <div class="jumbotron">
                    <h1> Oops looks like not all room components were found!</h1>
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
                    <h1> Oops looks like there was an error when looking for room components to add!</h1>
                    <p>Try again!</p>
                    <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
                </div>

                <?php

                $conn->rollback();

                return NULL;
            }

            // Success!
            return $R_id;
        }
        else{

            ?>
            <div class="jumbotron">
                <h1> Oops looks like there was an error inserting room!</h1>
                <p><?php echo  $conn->error; ?></p>
                <p>Try again!</p>
                <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
            </div>

            <?php

            $conn->rollback();
            return NULL;
        }
    }
    else{

        ?>
        <div class="jumbotron">
            <h1> Oops looks like the room floor id given is NULL!</h1>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
        </div>

        <?php

        return NULL;
    }
}

//insert_room($P_id, $rta, $rl, $rc, $RF_id);

