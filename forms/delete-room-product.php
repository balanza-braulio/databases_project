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
$sql_s_pNames = "SELECT DISTINCT product_name FROM product WHERE product_mode = 1;";

// Variable definition
$p_index = 0;

$result = $conn->query($sql_s_pNames);

$products = array_fill(0, $result->num_rows, 'value');

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $products[$p_index++] = $row["product_name"];
    }

    ?>

    <div class="container pt-5">

        <form  action="../delete/delete-room-product.php" method="post" >
            <div class="form-group">
                <label for="pName">Room Product To Be Deleted</label>
                <select multiple class="form-control" id="pName" name="pname" >
                    <?php foreach ($products as $p)
                    {
                        ?>
                        <option><?php echo $p ?></option>
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
        <h1> Oops looks like there are no room products to delete!</h1>
        <p>Try adding one first!</p>
        <a class="btn btn-primary" href="../forms/insert-room-product.php">Add room product!</a>
    </div>

    <?php

}
require_once "../footer.php";

?>