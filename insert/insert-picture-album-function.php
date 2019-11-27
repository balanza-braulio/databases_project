<?php
// Include config file
require_once "../config.php";

//// Test picture album elements
///
// Paths:
//$pic_cover = '/test/test_cover.jpg';
//$pic_front = '/test/test_front.jpg';
//$pic_side = '/test/test_side.jpg';
//$pic_back = '/test/test_back.jpg';

////End of album elements
///
//

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}

function insert_picture_album($pic_cover, $pic_front, $pic_side, $pic_back){

    $conn = db();
    $PA_id = NULL;

    // Query definition
    $sql_i_pa  = "INSERT INTO picture_album (pic_cover, pic_front, pic_side, pic_back) VALUES ('" . $pic_cover . "', '" . $pic_front . "', '" . $pic_side . "', '" . $pic_back . "')";
    $L_I_PA = "SELECT picture_album_id FROM picture_album ORDER BY picture_album_id DESC LIMIT 1";

    // Begin transaction
    //$conn->begin_transaction();

    $pa_insert = $conn->query($sql_i_pa);
    if ($pa_insert === TRUE) {

        $picture_album_last_insert = $conn->query($L_I_PA);

        $PA_id = $picture_album_last_insert->fetch_assoc()["picture_album_id"];

        //echo "New picture album, id: " . $PA_id . " created successfully <br>";

        //$conn->commit();

        return $PA_id;
    }
    else {

        ?>
        <div class="jumbotron">
            <h1> Oops looks like there was an error creating this picture album!</h1>
            <p><?php echo  $conn->error; ?></p>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Try Again!</a>
        </div>

        <?php

        $conn->rollback();
    }

    return $PA_id;

}

// Example
//insert_picture_album($pic_cover, $pic_front, $pic_side, $pic_back);

?>