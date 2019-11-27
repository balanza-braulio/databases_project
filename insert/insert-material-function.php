<?php
// Include config file
require_once "../config.php";

require_once "../head.php";
require_once "../sub-navbar.php";

$m_name = htmlspecialchars($_POST["mname"]);
$m_cost = htmlspecialchars($_POST["mcost"]);
$m_des = htmlspecialchars($_POST["mdes"]);


////Array definition
//$material_type = array("Oak", "Cedar", "Pine", "Red Oak", "Plywood");
//
//// Single material definition
//$material_name = "Aluminum";
//$material_description = "This is a dummy.";
//$material_price = 8;

function insert_material($material_name, $material_description, $material_price){

    $conn = db();
    $M_id = NULL;

    // Query definition
    $sql_i_mat_  = "INSERT INTO material (material_name, material_description, material_cost_per_unit) VALUES ('" . $material_name . "','" . $material_description . "', " . $material_price . ")";
    $L_I_M = "SELECT material_id FROM material ORDER BY material_id DESC LIMIT 1";

    // Begin transaction
    $conn->begin_transaction();

    $mat_insert = $conn->query($sql_i_mat_);
    if ($mat_insert === TRUE) {

        $material_last_insert = $conn->query($L_I_M);

        $M_id = $material_last_insert->fetch_assoc()["material_id"];
        ?>


    <div class="jumbotron">
        <h1> Congratulations! New material <?php echo $material_name; ?> created!</h1>
        <p>ID: <?php echo $M_id; ?> </p>
        <p>Would you want to create another material?</p>
        <a class="btn btn-primary" href="../forms/insert-material.php">Create Material!</a>
    </div>

<?php

        $conn->commit();

        return $M_id;
    }
    else { ?>

        <div class="jumbotron">
            <h1> Oops looks like there was a problem inserting the material <?php echo $material_name?>!</h1>
            <p><?php echo $conn->error; ?> </p>
            <p>Please try again!</p>
            <a class="btn btn-primary" href="../forms/insert-material.php">Try Again!</a>
        </div>

<?php
        $conn->rollback();
    }

    return $M_id;

}

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}
else{
    // Handle insert!
    insert_material($m_name,  $m_des, $m_cost);
}
require_once "../footer.php";
?>