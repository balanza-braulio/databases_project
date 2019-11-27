<?php
require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}

$conn = db();

//Query definition
$sql_s_style_name = "SELECT DISTINCT style_name FROM style;";

// Variable definition
$index = 0;

$result = $conn->query($sql_s_style_name);

$styles = array_fill(0, $result->num_rows, 'value');

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $styles[$index++] = $row["style_name"];
    }
    ?>

    <div class="container pt-5">

        <form  action="../delete/delete-style.php" method="post" >
            <div class="form-group">
                <label for="styleName">Style To Be Deleted</label>
                <select multiple class="form-control" id="styleName" name="sname" >
                    <?php foreach ($styles as $style)
                    {
                        ?>
                        <option><?php echo $style ?></option>
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
        <h1> Oops looks like there are no styles to delete!</h1>
        <p>Try adding one first!</p>
        <a class="btn btn-primary" href="../forms/insert-style.php">Add style!</a>
    </div>

    <?php

}

require_once "../footer.php";

?>