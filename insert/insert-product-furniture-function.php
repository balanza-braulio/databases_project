<?php
require_once "../head.php";
require_once "../config.php";
require_once "insert-furniture-function.php";
require_once "insert-picture-album-function.php";
require_once "../sub-navbar.php";

// Product elements
$pn = htmlspecialchars($_POST["pname"]);
$pp = htmlspecialchars($_POST["pprice"]);
$pd = htmlspecialchars($_POST["pdes"]);
$ppop = htmlspecialchars($_POST["ppop"]);
$pr = htmlspecialchars($_POST["prate"]);
$pfeat = htmlspecialchars($_POST["pfeat"]);

if($pfeat == NULL)
    $pfeat = 0;

$pic_cover = htmlspecialchars($_POST["piccover"]);
$pic_front = htmlspecialchars($_POST["picfront"]);
$pic_side = htmlspecialchars($_POST["picside"]);
$pic_back = htmlspecialchars($_POST["picback"]);

// Product mode, furniture = 0, room = 1;
$mode = htmlspecialchars($_POST["pmode"]);

// Furniture Test elements
$loc = htmlspecialchars($_POST["furloc"]);
$ft = htmlspecialchars($_POST["furtype"]);

// Materials to be matched to furniture
$materials = $_POST["materials"];

// Style selected
$S_id = htmlspecialchars($_POST["pstyle"]);

// Physical Properties elements
$l = htmlspecialchars($_POST["l"]);
$w = htmlspecialchars($_POST["w"]);
$h = htmlspecialchars($_POST["h"]);
$g = htmlspecialchars($_POST["g"]);

//echo $pn . $pp . $pd . $ppop . $pr . "<br> Pfeatured value: " . $pfeat . "<br>" . $pic_cover . $pic_front . $pic_side . $pic_back . $mode . $loc . $ft . $S_id . $l . $w . $h . $g;

//foreach ($materials as $mat)
    //echo $mat;

//var_dump($_POST);

function insert_product_furniture ($pn, $pp, $pd, $ppop, $pr, $pfeat, $loc, $ft, $l, $w, $h, $g, $materials, $S_id, $pic_cover, $pic_front, $pic_side, $pic_back){

    // Product type id, either furniture
    $P_id = NULL;
    $PA_id = NULL;


    // This should go outside function
//    $pic_cover = '/test/test_cover.jpg';
//    $pic_front = '/test/test_front.jpg';
//    $pic_side = '/test/test_side.jpg';
//    $pic_back = '/test/test_back.jpg';

    $conn = db();
    $conn->begin_transaction();

    $PA_id = insert_picture_album($pic_cover, $pic_front, $pic_side, $pic_back);

    //// Use this in case you want to pass the style as style name instead of as style id
    // Query definition
    //$sql_s_s = "SELECT style_id FROM style WHERE style_name = '" . $style . "';";
    //$selected_style = $conn->query($sql_s_s);
    //$S_id = $selected_style->fetch_assoc()["style_id"];

    $sql_i_p = "INSERT INTO product (product_name, product_price, product_description, product_pop, product_rating, product_featured, product_mode, location_name, style_id, picture_album_id) VALUES ('" . $pn . "', " . $pp . ", '" . $pd . "', " . $ppop . ", " . $pr . ", " . $pfeat . ", 0 , '" . $loc . "' , " . $S_id . ", " . $PA_id . ");";
    $L_I_P = "SELECT product_id FROM product ORDER BY product_id DESC LIMIT 1";

    //// Begin inserting product
    ///
    // Begin transaction

    $product_insert = $conn->query($sql_i_p);



    if($product_insert === TRUE){

        $last_insert = $conn->query($L_I_P);
        $P_id = $last_insert->fetch_assoc()["product_id"];

        $F_id = insert_furniture($materials, $P_id, $ft, $l, $w, $h, $g);

        if ($F_id != NULL && $PA_id != NULL)
        {

            ?>
            <div class="jumbotron">
                <h1>Congratulations! New product added to the database!</h1>
                <p>ID: <?php echo $P_id?></p>
                <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Add another one?</a>
            </div>
            <?php

            //$conn->rollback();
            $conn->commit();
            return $P_id;
        }
        else{

            ?>
            <div class="jumbotron">
                <h1> Oops looks like there was an error inserting a child instance of the product!</h1>
                <p>Try again!</p>
                <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Try Again!</a>
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
            <a class="btn btn-primary" href="../forms/insert-furniture-product.php">Try Again!</a>
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
    insert_product_furniture ($pn, $pp, $pd, $ppop, $pr, $pfeat, $loc, $ft, $l, $w, $h, $g, $materials, $S_id, $pic_cover, $pic_front, $pic_side, $pic_back);
}

require_once "../footer.php";

