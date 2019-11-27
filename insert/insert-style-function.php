<?php
// Include config file
require_once "../config.php";

require_once "../head.php";
require_once "../sub-navbar.php";

// Elements
$sd = htmlspecialchars($_POST["sdes"]);
$sn = htmlspecialchars($_POST["sname"]);
$sy = htmlspecialchars($_POST["syear"]);

function insert_style($sd, $sn, $sy){

    $conn = db();
    $S_id = NULL;

// Start transaction
    $conn->begin_transaction();

// Query definition
    $sql_i_style = "INSERT INTO style (style_description, style_name, style_year) VALUES ('" . $sd . "', '" . $sn . "', " . $sy . ")";
    $L_I_S = "SELECT style_id FROM style ORDER BY style_id DESC LIMIT 1";

    $style_insert = $conn->query($sql_i_style);
    if ($style_insert === TRUE){

        $last_insert_style = $conn->query($L_I_S);

        $S_id = $last_insert_style->fetch_assoc()["style_id"];

        ?>


        <div class="jumbotron">
            <h1> Congratulations! New style <?php echo $sn; ?> created!</h1>
            <p>ID: <?php echo $S_id; ?> </p>
            <p>Would you want to create another style?</p>
            <a class="btn btn-primary" href="../forms/insert-style.php">Create Material!</a>
        </div>

        <?php

        $conn->commit();

        return $S_id;

    }
    else{ ?>

        <div class="jumbotron">
            <h1> Oops looks like there was a problem inserting the style <?php echo $sn ?>!</h1>
            <p><?php echo $conn->error; ?> </p>
            <p>Please try again!</p>
            <a class="btn btn-primary" href="../forms/insert-style.php">Try Again!</a>
        </div>

<?php

    }

    return $S_id;

}

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}
else{

    // Not test
    insert_style($sd, $sn, $sy);
}




require_once "../footer.php";