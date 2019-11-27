<?php
require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

$p_name = htmlspecialchars($_POST["pname"]);

function delete_furniture_product($p_name){

    // Database connection
    $conn = db();

    // Variable definition
    $P_id = NULL;
    $PHY_id = NULL;

    // Query definition to look for room component
    $sql_s_p = "SELECT product_id, physical_properties_id, picture_album_id FROM product NATURAL JOIN furniture WHERE product_name = '" . $p_name . "';";

    $s_result = $conn->query($sql_s_p);

    // Begin delete transaction.
    $conn->begin_transaction();

    // If there was a result
    if($s_result->num_rows > 0){

        $row = $s_result->fetch_assoc();
        $P_id = $row["product_id"];
        $PA_id = $row["picture_album_id"];
        $PHY_id = $row["physical_properties_id"];

        // Deletion query definition
        $sql_d_p = "DELETE FROM product WHERE product_id = " . $P_id . ";";
        $sql_d_pa = "DELETE FROM picture_album WHERE picture_album_id = " . $PA_id . ";";
        $sql_d_pp_h_m = "DELETE FROM pp_h_m WHERE physical_properties_id = " . $PHY_id . ";";
        $sql_d_f = "DELETE FROM furniture WHERE product_id = " . $P_id . ";";
        $sql_d_pp = "DELETE FROM physical_properties WHERE physical_properties_id = " . $PHY_id . ";";

        //echo $sql_d_p . "<br>" . $sql_d_pp_h_m . "<br>" . $sql_d_pp . "<br>";

        $d_f_result = $conn->query($sql_d_f);
        $d_p_result = $conn->query($sql_d_p);
        $d_pa_result = $conn->query($sql_d_pa);
        $d_pp_h_m_result = $conn->query($sql_d_pp_h_m);
        $d_pp_result = $conn->query($sql_d_pp);

        //echo $sql_d_p . "<br>" . $conn->error;
        if($d_p_result === TRUE && $d_pa_result === TRUE && $d_pp_h_m_result === TRUE && $d_pp_result === TRUE && $d_f_result === TRUE){

            ?>

            <div class="jumbotron">
                <h1> Congratulations! Furniture Product <?php echo $p_name ?> with id <?php echo $P_id; ?> deleted!</h1>
                <p>Would you want to delete another furniture product?</p>
                <a class="btn btn-primary" href="../forms/delete-furniture-product.php">Delete Another Furniture Product!</a>
            </div>
            <?php

            // Test Rollback
            //$conn->rollback();

            $conn->commit();
        }
        else{

            ?>
            <div class="jumbotron">
                <h1> Oops! Furniture <?php echo $p_name; ?> with id <?php echo $P_id; ?> was not deleted!</h1>
                <p>Error: <?php echo $conn->error; ?> </p>
                <a class="btn btn-primary" href="../forms/delete-furniture-product.php">Try again!</a>
            </div>
            <?php

            $conn->rollback();
        }
    }
    else{

        ?>
        <div class="jumbotron">
            <h1> Oops! Furniture Product <?php echo $p_name; ?> was not deleted!</h1>
            <p>Error: no furniture product found with that name! </p>
            <p>Make sure the furniture product's name is correct!</p>
            <a class="btn btn-primary" href="../forms/delete-furniture-product.php">Try again!</a>
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
    delete_furniture_product($p_name);
}


?>
<?php require_once "../footer.php"; ?>
