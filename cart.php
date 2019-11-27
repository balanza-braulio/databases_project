<?php
require_once "config.php";
require_once "head.php";
require_once "navbar.php";

// If not user redirect
if(!isUser()){
    header("Location: http://jc-concepts.local/error.php");
}
else{

    //Make connection to database
    $conn = db();

    $U_name = $_SESSION['username'];
    //Query definition
    $sql_s_c = "SELECT product_id, product_name, product_description, product_price FROM user NATURAL JOIN cart NATURAL JOIN product WHERE user_name = '" . $U_name . "';";
    $result = $conn->query($sql_s_c);
    if($result->num_rows > 0){


    $sql_s_t = "SELECT SUM(product_price) as T FROM user NATURAL JOIN cart NATURAL JOIN product WHERE user_name = '" . $U_name . "';";

    $result_t = $conn->query($sql_s_t);
    $row_t = $result_t->fetch_assoc();

    $total = $row_t["T"];
    ?>
    <div class="container mt-3 rounded">
    <?php while($row = $result->fetch_assoc()){
     $p_name = $row["product_name"];
     $p_des = $row["product_description"];
     $p_price = $row["product_price"];
     $p_id = $row["product_id"];

     ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <h4 class="card-title"><?php echo $p_name; ?></h4>
                    </div>
                    <div class="col-9">
                        <h4 class="card-title float-right"><?php echo $p_price; ?></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-10">
                        <p class="card-text"><?php echo $p_des; ?></p>
                    </div>
                    <div class="col-2">
                        <a class="btn btn-outline-danger float-right" href="delete/delete-from-cart-function.php?pid=<?php echo $p_id ?>">Delete!</a>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <div class="container mt-3 rounded border">
        <div class="row mt-2">
            <div class="col-4">
            <h4>Total: </h4>
            </div>
            <div class="col-8 ">
            <h4 class="float-right"><?php echo $total;?></h4>
            </div>
        </div>
    </div>
    <?php
    }
    else{

        ?>
        <div class="container">
            <div class="jumbotron">
                <h1> Looks like your cart is empty! Add something!</h1>
                <p>Browse Popular products!</p>
                <a class="btn btn-primary" href="popular.php">Go Go Go!</a>
            </div>
        </div>
        <?php

    }
}
require_once "footer.php"; ?>