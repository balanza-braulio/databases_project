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
$sql_s_matNames = "SELECT DISTINCT material_name FROM material;";

// Variable definition
$mat_index = 0;

$result = $conn->query($sql_s_matNames);

$promotions = array_fill(0, $result->num_rows, 'value');

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $promotions[$mat_index++] = $row["material_name"];
    }

    ?>

    <div class="container pt-5">

        <form  action="../delete/delete-material.php" method="post" >
            <div class="form-group">
                <label for="materialName">Material To Be Deleted</label>
                <select multiple class="form-control" id="materialName" name="mname" >
                    <?php foreach ($promotions as $mat)
                    {
                        ?>
                        <option><?php echo $mat ?></option>
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
        <h1> Oops looks like there are no materials to delete!</h1>
        <p>Try adding one first!</p>
        <a class="btn btn-primary" href="../forms/insert-material.php">Add material!</a>
    </div>

<?php

}



require_once "../footer.php";

?>