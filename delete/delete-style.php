<?php
require_once "../config.php";

require_once "../head.php";
require_once "../sub-navbar.php";

$s_name = htmlspecialchars($_POST["sname"]);

function delete_style($style_name){

    // Connect to database
    $conn = db();
    $style_id = NULL;
    // Query definitions
    $sql_s_styleid = "SELECT DISTINCT style_id FROM style WHERE style_name = '" . $style_name . "';";


    $result_name = $conn->query($sql_s_styleid);

    // Begin delete transaction
    $conn->begin_transaction();

    if($result_name->num_rows > 0){

        $style_id = $result_name->fetch_assoc()["style_id"];
        $sql_d_style = "DELETE FROM style WHERE style_id = " . $style_id . ";";

        $result = $conn->query($sql_d_style);
        if ($result === TRUE) {
            ?>

            <div class="jumbotron">
                <h1> Congratulations! Style <?php echo $style_name ?> with id <?php echo $style_id; ?> deleted!</h1>
                <p>Would you want to delete another style?</p>
                <a class="btn btn-primary" href="../forms/delete-style.php">Delete Another Style!</a>
            </div>

            <?php

            $conn->commit();
        }
        else{
            ?>
            <div class="jumbotron">
                <h1> Oops! Style <?php echo $style_name; ?> with id <?php echo $style_id; ?> was not deleted!</h1>
                <p>Error: <?php echo $conn->error; ?> </p>
                <p>Make sure the style is not in use!</p>
                <a class="btn btn-primary" href="../forms/delete-style.php">Try again!</a>
            </div>
            <?php

            $conn->rollback();
        }

    }
    else{
        ?>
        <div class="jumbotron">
            <h1> Oops! Style <?php echo $style_name; ?> was not deleted!</h1>
            <p>Error: no style found with that name! </p>
            <p>Make sure the style name is correct!</p>
            <a class="btn btn-primary" href="../forms/delete-style.php">Try again!</a>
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
    delete_style($s_name);

}

require_once "../footer.php";



