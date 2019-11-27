<?php

require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

$PRO_id = htmlspecialchars($_POST["promoid"]);
$products = $_POST["products"];

function associate_promo($products, $PRO_id){
    if(sizeof($products) > 0){

        // Initialize connection to database
        $conn = db();

        // Initialize transaction!
        $conn->begin_transaction();

        // Associate each product with the promo
        foreach ($products as $p){

            // Define query for insertion
            $sql_i_php = "INSERT INTO php (product_id, promotion_id) VALUES (" . $p . ", " . $PRO_id . ");";
            $result = $conn->query($sql_i_php);
            if ($result !== TRUE){
                ?>
                <div class="container">
                    <div class="jumbotron">
                        <h1> Oops looks like there was a problem associating products to the promotion!</h1>
                        <p><?php $conn->error;?></p>
                        <p>Try again!</p>
                        <a class="btn btn-primary" href="../forms/associate-promotion-product.php">Try Again!</a>
                    </div>
                </div>
                <?php

                $conn->rollback();
                return NULL;
            }
        }

        ?>
        <div class="container">
            <div class="jumbotron">
                <h1> Congratulations! You have associated the products to the promotion successfully!</h1>
                <a class="btn btn-primary" href="../aStuff.php">Go back to Admin Stuff!</a>
            </div>
        </div>
        <?php
        // Test rollback
        //$conn->rollback();

        $conn->commit();
        return $PRO_id;
    }
    else{
        ?>
        <div class="container">
            <div class="jumbotron">
                <h1> Oops looks like you forgot to add products to associate!</h1>
                <p>Try again!</p>
                <a class="btn btn-primary" href="../forms/associate-promotion-product.php">Try Again!</a>
            </div>
        </div>
        <?php
        return NULL;
    }
}

if(!isAdmin()) {
    header("Location: http://jc-concepts.local/error.php");
}
else{
    associate_promo($products, $PRO_id);
}