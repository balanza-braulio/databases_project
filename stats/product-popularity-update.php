<?php

function update_pop($P_id){


    // Make connection to database
    $conn = db();

    //Query definition
    $sql_u_pop = "UPDATE product SET product_pop = product_pop + 1 WHERE product_id = " . $P_id .";";

    // Begin transaction
    $conn->begin_transaction();

    $result = $conn->query($sql_u_pop);

    if($result !== TRUE){
        ?>
        <div class="container">
            <div class="jumbotron">
                <h1> Oops looks like there was an error viewing product. Code: 1!</h1>
                <p>Go back home!</p>
                <a class="btn btn-primary" href="../index.php">Home!</a>
            </div>
        </div>
        <?php

        return NULL;
    }
    else{
        //Test rollback
        //$conn->rollback();

        $conn->commit();
        return TRUE;
    }
}