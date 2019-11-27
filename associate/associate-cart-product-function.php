<?php

require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

$P_id = htmlspecialchars($_GET['pid']);

function associate_to_cart($P_id){

    // Error!
    if(!isUser()){
        header("Location: http://jc-concepts.local/error.php");
    }
    else{

        $conn = db();

        //// Find user id
        // Query definition

        $sql_s_u = "SELECT user_id FROM user WHERE user_name = '" . $_SESSION['username'] . "';";
        $result_uid = $conn->query($sql_s_u);

        if($result_uid > 0){

            $U_id = $result_uid->fetch_assoc()["user_id"];

            //Query definition
            $sql_a_c = "INSERT INTO cart (user_id, product_id) VALUES (" . $U_id . ", " . $P_id .");";

            $conn->begin_transaction();

            $result = $conn->query($sql_a_c);
            if($result === TRUE){
                ?>
                <div class="container">
                    <div class="jumbotron">
                        <h1> Congratulations! Product added to cart!</h1>
                        <p>Go back Home!</p>
                        <a class="btn btn-primary" href="../index.php">Go back home!</a>
                        <a type="button" class="btn btn-outline-success mr-2" href="../cart.php">Cart</a>
                    </div>
                </div>
                <?php

                //Test rollback
                //$conn->rollback();

                $conn->commit();
                return $P_id;
            }
            else{
                ?>
                <div class="container">
                    <div class="jumbotron">
                        <h1> Oops looks like there was an error inserting into cart!</h1>
                        <p><?php echo $conn->error?></p>
                        <p>Go back Home!</p>
                        <a class="btn btn-primary" href="../index.php">Go back home!</a>
                    </div>
                </div>
                <?php

                $conn->rollback();
                return NULL;
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
}

associate_to_cart($P_id);
require_once "../footer.php";