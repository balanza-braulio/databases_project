<?php
require_once "../config.php";

require_once "../head.php";
require_once "../sub-navbar.php";

$PRO_id = htmlspecialchars($_POST["promoid"]);

function delete_style($PRO_id){

    // Connect to database
    $conn = db();
    // Query definitions

    // Begin delete transaction
    $conn->begin_transaction();

    // Delete promotion associations
    $sql_d_php = "DELETE FROM php WHERE promotion_id = " . $PRO_id . ";";

    $php_delete = $conn->query($sql_d_php);

    if($php_delete === TRUE){

        $sql_d_prom = "DELETE FROM promotion WHERE promotion_id = " . $PRO_id . ";";

        $result = $conn->query($sql_d_prom);
        if ($result === TRUE) {

            ?>

            <div class="jumbotron">
                <h1> Congratulations! Promotion with id <?php echo $PRO_id; ?> deleted!</h1>
                <p>Would you want to delete another promotion?</p>
                <a class="btn btn-primary" href="../forms/delete-promotion.php">Delete Another Promotion!</a>
            </div>

            <?php

            //Test Rollback
            //$conn->rollback();
            $conn->commit();
            return $PRO_id;
        }
        else{
            ?>
            <div class="jumbotron">
                <h1> Oops! Promotion with id <?php echo $PRO_id; ?> was not deleted!</h1>
                <p>Error: <?php echo $conn->error; ?> </p>
                <a class="btn btn-primary" href="../forms/delete-promotion.php">Try again!</a>
            </div>
            <?php

            $conn->rollback();
            return NULL;
        }
    }
    else{
        ?>
        <div class="jumbotron">
            <h1> Oops! Promotion was not deleted!</h1>
            <p>Error: could not delete the promotion associations! </p>
            <p><?php echo $conn->error; ?></p>
            <a class="btn btn-primary" href="../forms/delete-promotion.php">Try again!</a>
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
    delete_style($PRO_id);
}

require_once "../footer.php";
