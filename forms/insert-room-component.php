<?php
require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}

// Initialize connection!
$conn = db();


// Some initial variables
$products = array('Shelf', 'Cabinet', 'Door', 'Bar', 'Counter', 'Microwave Cabinet', 'Fridge Cabinet', 'Pantry');

//Query definition
$sql_s_matNames = "SELECT DISTINCT material_name FROM material;";

// Variable definition
$mat_index = 0;

$result = $conn->query($sql_s_matNames);

$materials = array_fill(0, $result->num_rows, 'value');

if($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materials[$mat_index++] = $row["material_name"];
    }

    $mat_index = 0;
    ?>

    <div class="container pt-5">

        <form  action="../insert/insert-room-component-function.php" method="post" >

            <div class="form-group">
                <div class="row">
                    <div class="col-6">
                        <label for="rcName">Room Component Name</label>
                        <input name="rcname" required type="text" class="form-control" id="rcName" aria-describedby="rcHelp" placeholder="Enter Room Component Name" >
                        <small id="rcHelp" class="form-text text-muted">Please enter the name of the new room component you want to create!</small><br>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="rcType">Room Component Type</label>
                            <select required multiple class="form-control" id="rcType" name="rctype">
                                <?php
                                foreach ($products as $type){
                                    ?>
                                    <option><?php echo $type;?></option>
                                    <?php
                                }
                                    ?>
                            </select>
                        </div>
                    </div>
                </div>
                <label>Physical Properties!</label>
                <div class="row">
                    <div class="col-3">
                        <label for="rcLength">Room Component Length<br></label>
                        <input id="rcLength" type="number" required name="l" min="0" max="20" placeholder="Max 20" step="any">
                    </div>
                    <div class="col-3">
                        <label for="rcWidth">Room Component Width<br></label>
                        <input id="rcWidth" type="number" required name="w" min="0" max="20" placeholder="Max 20" step="any">
                    </div>
                    <div class="col-3">
                        <label for="rcHeight">Room Component Height</label>
                        <input id="rcHeight" type="number" required name="h" min="0" max="20" placeholder="Max 20" step="any">
                    </div>
                    <div class="col-3">
                        <label for="rcGeo">Room Component Geometry</label>
                        <input name="g" required type="text" class="form-control" id="rcGeo" aria-describedby="geoHelp" placeholder="Enter Geometry!" >
                    </div>
                </div>
                <label for="rcMaterials">Materials the Room Component will be made of:</label>
                <div class="form-check" id="rcMaterials">
                    <div class="row">
                        <?php foreach ($materials as $mat){
                            ?>
                        <div class="col-2">
                            <input name="materials[<?php echo $mat_index?>]" class="form-check-input" type="checkbox" value="<?php echo $mat;?>" id="material<?php echo $mat;?>">
                            <label class="form-check-label" for="material<?php echo $mat;?>">
                                <?php echo $mat ?>
                            </label>
                        </div>
                            <?php
                            $mat_index++;
                            if ($mat_index % 6 == 0)
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
            <div class="form-group">
                <label for="rcDescription">Enter the room component description!</label>
                <textarea required name="rcdes" class="form-control" id="rcDescription" rows="3"></textarea>
            </div>
            <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php

}
else{

    ?>

    <div class="jumbotron">
        <h1> Oops looks like you are trying to add a room component, but there are no materials!</h1>
        <p>Try adding one first!</p>
        <a class="btn btn-primary" href="../forms/insert-material.php">Add material!</a>
    </div>

    <?php

}
require_once "../footer.php";

?>

