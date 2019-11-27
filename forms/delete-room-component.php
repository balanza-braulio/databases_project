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
$sql_s_rcNames = "SELECT DISTINCT room_component_name FROM room_component;";

// Variable definition
$rc_index = 0;

$result = $conn->query($sql_s_rcNames);

$room_components = array_fill(0, $result->num_rows, 'value');

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $room_components[$rc_index++] = $row["room_component_name"];
    }

    ?>

    <div class="container pt-5">

        <form  action="../delete/delete-room-component.php" method="post" >
            <div class="form-group">
                <label for="rcName">Room Component To Be Deleted</label>
                <select multiple class="form-control" id="rcName" name="rcname" >
                    <?php foreach ($room_components as $rc)
                    {
                        ?>
                        <option><?php echo $rc ?></option>
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
        <h1> Oops looks like there are no room components to delete!</h1>
        <p>Try adding one first!</p>
        <a class="btn btn-primary" href="../forms/insert-room-component.php">Add room component!</a>
    </div>

    <?php

}
require_once "../footer.php";

?>