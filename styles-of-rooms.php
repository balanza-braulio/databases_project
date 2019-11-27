<?php

// Connection to database
require_once "config.php";
$conn = db();

require_once "head.php";
require_once "navbar.php";

// Query definition
$sql_s_mat = "SELECT style_id, style_name, style_description, style_year FROM style;";

$result = $conn->query($sql_s_mat);

if($result->num_rows > 0){

    while($row = $result->fetch_assoc()){

        $style_name = $row["style_name"];
        $style_id = $row["style_id"];
        $style_des = $row["style_description"];
        $style_year = $row["style_year"];

        ?>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><?php echo $style_name; ?></h4>
                    <h5 class="card-title">Year: <?php echo $style_year; ?></h5>
                    <p class="card-text"><?php echo $style_des; ?></p>
                    <a href="products-by-style.php?pstyle_id=<?php echo $style_id;?>&pmode=1" class="btn btn-primary">See all furniture with <?php echo $style_name; ?> style!</a>
                </div>
            </div>
        </div>
        <?php
    }

}
else{

    ?>

    <div class="jumbotron">
        <h1> Oops looks like there are no styles available!</h1>
        <p>Please try again later!</p>
        <a class="btn btn-primary" href="index.php">Go Back!</a>
    </div>

    <?php
}


require_once "footer.php";
?>

