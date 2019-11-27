<?php
require_once "../head.php";
require_once "../config.php";
require_once "insert-picture-album-function.php";
require_once "insert-room-floor-function.php";
require_once "insert-room-function.php";
require_once "../sub-navbar.php";

//// Function for inserting furniture product
///
//

// Product elements
$pn = htmlspecialchars($_POST["pname"]);
$pp = htmlspecialchars($_POST["pprice"]);
$pd = htmlspecialchars($_POST["pdes"]);
$ppop = htmlspecialchars($_POST["ppop"]);
$pr = htmlspecialchars($_POST["prate"]);
$pfeat = htmlspecialchars($_POST["pfeat"]);
$S_id = htmlspecialchars($_POST["pstyle"]);

if($pfeat == NULL)
    $pfeat = 0;

$pic_cover = htmlspecialchars($_POST["piccover"]);
$pic_front = htmlspecialchars($_POST["picfront"]);
$pic_side = htmlspecialchars($_POST["picside"]);
$pic_back = htmlspecialchars($_POST["picback"]);

// Product mode, furniture = 0, room = 1;
$mode = htmlspecialchars($_POST["pmode"]);

// Room elements
$loc = htmlspecialchars($_POST["ploc"]);
$rft = htmlspecialchars($_POST["rfstyle"]);
$rfd = htmlspecialchars($_POST["rfdes"]);
$rta = htmlspecialchars($_POST["rta"]);

// Materials to be matched to furniture
$rfm = $_POST["rfmaterials"];
$rc = $_POST["rc"];


//echo $pn . "<br> Price:" . $pp . "<br>" . $pd . "<br>" . $ppop . "<br>" . $pr . "<br>" . $pfeat . "<br>" . $rfd . "<br>" . $rta . "<br>" . $pic_cover . "<br>" . $pic_front . "<br>" . $pic_side . "<br>" . $pic_back . "<br>" . $mode . "<br>" . $loc . "<br>" . $rft . "<br>" . $S_id . "<br>";

//foreach ($rfm as $mat)
   //echo $mat;

//foreach ($rc as $c)
    //echo $c;

//var_dump($_POST);

function insert_product_room($pn, $pp, $pd, $ppop, $pr, $pfeat, $rta, $loc, $rc, $rfm, $rfd, $rft, $S_id, $pic_cover, $pic_front, $pic_side, $pic_back){

    // Product type id, either furniture
    $P_id = NULL;
    $PA_id = NULL;

    //// Begin inserting product
    ///
    // Begin transaction
    $conn = db();
    $conn->begin_transaction();

    $PA_id = insert_picture_album($pic_cover, $pic_front, $pic_side, $pic_back);

    // Query definition
    $sql_i_p = "INSERT INTO product (product_name, product_price, product_description, product_pop, product_rating, product_featured, product_mode, location_name, style_id, picture_album_id) VALUES ('" . $pn . "', " . $pp . ", '" . $pd . "', " . $ppop . ", " . $pr . ", " . $pfeat . ", 1 , '" . $loc . "' , " . $S_id . ", " . $PA_id . ");";
    $L_I_P = "SELECT product_id FROM product ORDER BY product_id DESC LIMIT 1";


    //echo $sql_i_p;
    $product_insert = $conn->query($sql_i_p);

    if($product_insert === TRUE){

        $last_insert = $conn->query($L_I_P);

        $P_id = $last_insert->fetch_assoc()["product_id"];

        //echo "Product id: " . $P_id . "<br>";

        $RF_id = insert_room_floor($rfm, $rfd, $rft);

        //echo "Room floor id: " . $RF_id . "<br>";
        $R_id = insert_room($P_id, $rta, $rc, $RF_id);

        if ($RF_id != NULL && $R_id != NULL && $PA_id != NULL){

            ?>
            <div class="jumbotron">
                <h1>Congratulations! New product added to the database!</h1>
                <p>ID: <?php echo $P_id?></p>
                <a class="btn btn-primary" href="../forms/insert-room-product.php">Add another one?</a>
            </div>
            <?php

            //Test rollback
            //$conn->rollback();
            $conn->commit();
            return $P_id;
        }
        else{

            ?>
            <div class="jumbotron">
                <h1> Oops looks like there was an error inserting a child instance of the room product!</h1>
                <p>Try again!</p>
                <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
            </div>

            <?php

            $conn->rollback();
            return NULL;
        }

    }
    else{

        ?>
        <div class="jumbotron">
            <h1> Oops looks like there was an error creating this product!</h1>
            <p><?php echo  $conn->error; ?></p>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/insert-room-product.php">Try Again!</a>
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

    // Not a test
    insert_product_room($pn, $pp, $pd, $ppop, $pr, $pfeat, $rta, $loc, $rc, $rfm, $rfd, $rft, $S_id, $pic_cover, $pic_front, $pic_side, $pic_back);

}

// Test
//insert_product_room($pn, $pp, $pd, $ppop, $pr, $pfeat, $rta, $loc, $rc, $rfm, $rfd, $rft, $style);

require_once "../footer.php";


