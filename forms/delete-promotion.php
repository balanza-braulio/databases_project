<?php
require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}

$conn = db();

//// Show all materials to be deleted
//Query definition
$sql_s_promo = "SELECT DISTINCT promotion_name, promotion_id FROM promotion;";

// Variable definition
$promo_index = 0;

$result = $conn->query($sql_s_promo);

$products_keys = array();
$products_values = array();

if($result->num_rows > 0){

    while($row = $result->fetch_assoc()){
        array_push($products_values,$row["promotion_name"]);
        array_push($products_keys,$row["promotion_id"]);
    }
    $promotions = array_combine($products_keys, $products_values);

    ?>

    <div class="container pt-5">

        <form  action="../delete/delete-promotion.php" method="post" >
            <div class="form-group">
                <label for="promotion">Promotion To Be Deleted</label>
                <select multiple class="form-control" id="promotion" name="promoid" >
                    <?php foreach ($promotions as $id => $promo)
                    {
                        ?>
                        <option value="<?php echo $id; ?>"><?php echo $promo; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <?php
}
else{

    ?>

    <div class="jumbotron">
        <h1> Oops looks like there are no promotions to delete!</h1>
        <p>Try adding one first!</p>
        <a class="btn btn-primary" href="../forms/insert-promotion.php">Add Promotion!</a>
    </div>

    <?php

}



require_once "../footer.php";

?>