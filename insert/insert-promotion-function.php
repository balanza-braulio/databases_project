<?php
// Include config file
require_once "../config.php";

require_once "../head.php";
require_once "../sub-navbar.php";

// Elements
$pron = htmlspecialchars($_POST["proname"]);
$prodes = htmlspecialchars($_POST["prodes"]);
$promod = htmlspecialchars($_POST["promod"]);
$propath = htmlspecialchars($_POST["propic"]);


function insert_promo($pron, $prodes, $promod, $propath){

    $conn = db();
    $PRO_id = NULL;

// Start transaction
    $conn->begin_transaction();

// Query definition
    $sql_i_pro = "INSERT INTO promotion (promotion_name, promotion_description, promotion_picture, promotion_price_modifier) VALUES ('" . $pron . "', '" . $prodes . "', '" . $propath . "', " . $promod . ")";
    $L_I_PRO = "SELECT promotion_id FROM promotion ORDER BY promotion_id DESC LIMIT 1";

    $promo_insert = $conn->query($sql_i_pro);
    if ($promo_insert === TRUE){

        $last_insert_promo = $conn->query($L_I_PRO);

        $PRO_id = $last_insert_promo->fetch_assoc()["promotion_id"];

        ?>


        <div class="jumbotron">
            <h1> Congratulations! New promotion <?php echo $pron; ?> created!</h1>
            <p>ID: <?php echo $PRO_id; ?> </p>
            <p>Would you want to create another promotion?</p>
            <a class="btn btn-primary" href="../forms/insert-promotion.php">Create Promotion!</a>
            <a href="../forms/associate-promotion-product.php" class="btn btn-primary">Associate Products to Promotion!</a>
        </div>

        <?php

        $conn->commit();

        //$conn->rollback();
        return $PRO_id;

    }
    else{ ?>

        <div class="jumbotron">
            <h1> Oops looks like there was a problem inserting the promotion <?php echo $pron ?>!</h1>
            <p><?php echo $conn->error; ?> </p>
            <p>Please try again!</p>
            <a class="btn btn-primary" href="../forms/insert-promotion.php">Try Again!</a>
        </div>

<?php
        return NULL;
    }
}

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}
else{

    // Not test
    insert_promo($pron, $prodes, $promod, $propath);
}




require_once "../footer.php";