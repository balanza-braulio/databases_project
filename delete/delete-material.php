<?php
require_once "../config.php";

require_once "../head.php";
require_once "../sub-navbar.php";

$m_name = htmlspecialchars($_POST["mname"]);

function delete_mat($mat_name){

    // Connect to database
    $conn = db();
    $mat_id = NULL;
    // Query definitions
    $sql_s_matid = "SELECT DISTINCT material_id FROM material WHERE material_name = '" . $mat_name . "';";

    $result_name = $conn->query($sql_s_matid);

    // Begin delete transaction
    $conn->begin_transaction();

    if($result_name->num_rows > 0){

        $mat_id = $result_name->fetch_assoc()["material_id"];
        $sql_d_mat = "DELETE FROM material WHERE material_id = " . $mat_id . ";";

        $result = $conn->query($sql_d_mat);
        if ($result === TRUE) {
            ?>

            <div class="jumbotron">
                <h1> Congratulations! Material <?php echo $mat_name ?> with id <?php echo $mat_id; ?> deleted!</h1>
                <p>Would you want to delete another material?</p>
                <a class="btn btn-primary" href="../forms/delete-material.php">Delete Another Material!</a>
            </div>

            <?php

            $conn->commit();
        }
        else{
            ?>
            <div class="jumbotron">
                <h1> Oops! Material <?php echo $mat_name; ?> with id <?php echo $mat_id; ?> was not deleted!</h1>
                <p>Error: <?php echo $conn->error; ?> </p>
                <p>Make sure the material is not in use!</p>
                <a class="btn btn-primary" href="../forms/delete-material.php">Try again!</a>
            </div>
            <?php

            $conn->rollback();
        }

    }
    else{
        ?>
        <div class="jumbotron">
            <h1> Oops! Material <?php echo $mat_name; ?> was not deleted!</h1>
            <p>Error: no material found with that name! </p>
            <p>Make sure the material name is correct!</p>
            <a class="btn btn-primary" href="../forms/delete-material.php">Try again!</a>
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
    delete_mat($m_name);
}

require_once "../footer.php";




