<?php
require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

$rc_name = htmlspecialchars($_POST["rcname"]);

function delete_room_component($rc_name){

    // Database connection
    $conn = db();

    // Variable definition
    $RC_id = NULL;
    $PHY_id = NULL;

    // Query definition to look for room component
    $sql_s_rc = "SELECT room_component_id,physical_properties_id FROM room_component WHERE room_component_name = '" . $rc_name . "';";

    $s_result = $conn->query($sql_s_rc);

    // Begin delete transaction.
    $conn->begin_transaction();

    // If there was a result
    if($s_result->num_rows > 0){

        $row = $s_result->fetch_assoc();
        $RC_id = $row["room_component_id"];
        $PHY_id = $row["physical_properties_id"];

        // Deletion query definition
        $sql_d_rc = "DELETE FROM room_component WHERE room_component_id = " . $RC_id . ";";
        $sql_d_pp_h_m = "DELETE FROM pp_h_m WHERE physical_properties_id = " . $PHY_id . ";";
        $sql_d_pp = "DELETE FROM physical_properties WHERE physical_properties_id = " . $PHY_id . ";";

        //echo $sql_d_rc . "<br>" . $sql_d_pp_h_m . "<br>" . $sql_d_pp . "<br>";

        $d_rc_result = $conn->query($sql_d_rc);
        $d_pp_h_m_result = $conn->query($sql_d_pp_h_m);
        $d_pp_result = $conn->query($sql_d_pp);

        if($d_rc_result === TRUE && $d_pp_h_m_result === TRUE && $d_pp_result === TRUE){

            ?>

            <div class="jumbotron">
                <h1> Congratulations! Room component <?php echo $rc_name ?> with id <?php echo $RC_id; ?> deleted!</h1>
                <p>Would you want to delete another room component?</p>
                <a class="btn btn-primary" href="../forms/delete-room-component.php">Delete Another Room Component!</a>
            </div>
            <?php

            // Test Rollback
            //$conn->rollback();

            $conn->commit();
        }
        else{

            ?>
            <div class="jumbotron">
                <h1> Oops! Room Component <?php echo $rc_name; ?> with id <?php echo $RC_id; ?> was not deleted!</h1>
                <p>Error: <?php echo $conn->error; ?> </p>
                <p>Make sure the room component is not in use!</p>
                <a class="btn btn-primary" href="../forms/delete-room-component.php">Try again!</a>
            </div>
            <?php

            $conn->rollback();


        }
    }
    else{

        ?>
        <div class="jumbotron">
            <h1> Oops! Room component <?php echo $rc_name; ?> was not deleted!</h1>
            <p>Error: no room component found with that name! </p>
            <p>Make sure the room component's name is correct!</p>
            <a class="btn btn-primary" href="../forms/delete-room-component.php">Try again!</a>
        </div>
        <?php

        $conn->rollback();
    }
}

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}
else{
    delete_room_component($rc_name);
}

?>
<?php require_once "../footer.php"; ?>
