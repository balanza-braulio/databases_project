<?php

require_once "head.php";
require_once "navbar.php";

// Connection to database
require_once "config.php";
$conn = db();

// Query definition
$sql_s_mat = "SELECT material_id, material_name, material_description FROM material;";

$result = $conn->query($sql_s_mat);

if($result->num_rows > 0){

    while($row = $result->fetch_assoc()){

        $mat_name = $row["material_name"];
        $mat_id = $row["material_id"];
        $mat_des = $row["material_description"];

        ?>

        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><?php echo $mat_name; ?></h4>
                    <p class="card-text"><?php echo $mat_des; ?></p>
                    <a href="products-by-mat.php?pmat=<?php echo $mat_name;?>&pmode=1"" class="btn btn-primary">See all rooms with <?php echo $mat_name; ?>!</a>
                </div>
            </div>
        </div>
        <?php
    }
}
else{


    ?>

    <div class="jumbotron">
        <h1> Oops looks like there are no materials available!</h1>
        <p>Please try again later!</p>
        <a class="btn btn-primary" href="index.php">Go Back!</a>
    </div>

    <?php
}

require_once "footer.php";
?>
