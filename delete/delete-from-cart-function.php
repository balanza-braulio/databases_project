<?php
require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

if(isUser()){

    $P_id = htmlspecialchars($_GET["pid"]);
    delete_from_cart($P_id, $_SESSION["username"]);
}
else{
        header("Location: http://jc-concepts.local/error.php");
}

function delete_from_cart($P_id, $U_name){

    // Make connection to database
    $conn = db();
    //Search for user id
    $U_id = NULL;
    // Query definition
    $sql_s_u = "SELECT user_id FROM user WHERE user_name = '" . $U_name . "';";
    $result_u = $conn->query($sql_s_u);

    // If no results, error
    if ($result_u->num_rows > 0){

        $U_id = $result_u->fetch_assoc()["user_id"];

        //Query definition to delete from cart
        $sql_d_c = "DELETE FROM cart WHERE user_id = " . $U_id ." AND product_id = " . $P_id . " ;";

        // Begin transaction

        $conn->begin_transaction();

        $result_d = $conn->query($sql_d_c);

        // If delete unsuccessful, error
        if($result_d === TRUE){

            // Test rollback
            //$conn->rollback();
            $conn->commit();
            header("Location: http://jc-concepts.local/cart.php");
            return TRUE;
        }
        else{

            ?>
            <div class="container">
                <div class="jumbotron">
                    <h1> Oops looks like there was an error deleting from cart!</h1>
                    <p>Go back Home!</p>
                    <a class="btn btn-primary" href="../index.php">Go back home!</a>
                </div>
            </div>
            <?php

            $conn->rollback();
            return FALSE;
        }

    }
    else{
        ?>
        <div class="container">
            <div class="jumbotron">
                <h1> Oops looks like there was an error getting the user id!</h1>
                <p>Go back Home!</p>
                <a class="btn btn-primary" href="../index.php">Go back home!</a>
            </div>
        </div>
        <?php
    }
}
require_once "../footer.php"; ?>