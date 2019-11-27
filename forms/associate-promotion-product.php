<?php

require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

// Require admin status
if (!isAdmin()) {
    header("Location: http://jc-concepts.local/error.php");
}

//Make connection to database!
$conn = db();

// Define queries too look for promotions and products
$sql_s_promo = "SELECT promotion_id, promotion_name FROM promotion WHERE 1;";
$sql_s_product = "SELECT product_id,product_name FROM product WHERE 1;";

// Execute queries!
$prod_result = $conn->query($sql_s_product);
$promo_result = $conn->query($sql_s_promo);

// Find result number
$num_promos = $prod_result->num_rows;
$num_prod = $promo_result->num_rows;

// Arrays for results
$products_values = array();
$products_keys =array();

$promos_values = array();
$promos_keys = array();

if($num_prod > 0 && $num_promos > 0){

    // Arrange the results into nice arrays!
    while ($row = $prod_result->fetch_assoc()){
        array_push($products_values,$row["product_name"]);
        array_push($products_keys,$row["product_id"]);
    }
    while ($row = $promo_result->fetch_assoc()){
        array_push($promos_values, $row["promotion_name"]);
        array_push($promos_keys,$row["promotion_id"]);
    }

    // Combine arrays
    $products = array_combine($products_keys, $products_values);
    $promotions = array_combine($promos_keys, $promos_values);

    ?>
<div class="container pt-5">
    <form  action="../associate/associate-promotion-product-function.php" method="post" >
        <div class="form-group">
            <label>Please select the promotion and the products you want to associate!</label>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="promo">Promotion</label>
                        <select required multiple class="form-control" id="promo" name="promoid" class="selectpicker">
                            <?php
                            foreach ($promotions as $id => $name){
                                ?>
                                <option value="<?php echo $id ?>"><?php echo $name ;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-8">
                    <label>Products</label>
                    <div class="form-check rounded border" id="rcMaterials">
                        <div class="row">
                            <?php
                            $prod_index = 0;
                            foreach ($products as $id => $prod){
                            ?>
                            <div class="col-4">
                                <input name="products[<?php echo $id?>]" class="form-check-input" type="checkbox" value="<?php echo $id;?>" id="prod_<?php echo $id;?>">
                                <label class="form-check-label rounded text-center" for="prod_<?php echo $id;?>">
                                    <?php echo $prod ?>
                                </label>
                            </div>
                            <?php
                            $prod_index++;
                            if ($prod_index % 6 == 0)
                            {
                            ?>
                        </div>
                        <div class="row">
                            <?php
                            }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
    </form>
</div>
            <?php

}
else{

    ?>

    <div class="jumbotron">
        <h1> Oops looks like you are trying associate products to a promotion, but there are either no promotions or no products!</h1>
        <p>Try adding one first!</p>
        <a class="btn btn-primary" href="insert-promotion.php">Add Promotion!</a>
        <a class="btn btn-primary" href="insert-furniture-product.php">Add Furniture Product!</a>
        <a class="btn btn-primary" href="insert-room-product.php">Add Room Product!</a>
    </div>

    <?php

}

require_once "../footer.php";

?>
