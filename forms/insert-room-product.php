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
$rfTypes = array('Mosaic', 'Solid', 'Planks');
$roomLoc = array('Kitchen', 'Living Room', 'Bedroom', 'Patio', 'Backyard', 'TV Room', 'Garage');


//Query definition
$sql_s_matNames = "SELECT DISTINCT material_name FROM material;";
$sql_s_style_name = "SELECT DISTINCT style_name, style_id FROM style;";
$sql_s_rc = "SELECT DISTINCT room_component_name FROM room_component";

$conn = db();

// Variable definition
$style_index = 0;
$mat_index = 0;
$rc_index = 0;

$style_result = $conn->query($sql_s_style_name);
$rc_result = $conn->query($sql_s_rc);
$result = $conn->query($sql_s_matNames);

$styles = array_fill(0, $style_result->num_rows, 'value');
$styles_id = array_fill(0, $style_result->num_rows, '0');
$materials = array_fill(0, $result->num_rows, 'value');
$rc = array_fill(0, $rc_result->num_rows, 'value');

if($result->num_rows > 0 && $style_result->num_rows > 0 && $rc_result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materials[$mat_index++] = $row["material_name"];
    }
    while($row = $style_result->fetch_assoc()){
        $styles[$style_index] = $row["style_name"];
        $styles_id[$style_index++] = $row["style_id"];
    }

    while ($row = $rc_result->fetch_assoc()){
        $rc[$rc_index++] = $row["room_component_name"];
    }

    $rc_index = 0;
    $style_index = 0;
    $mat_index = 0;
    ?>

    <div class="container pt-5">
        <form  action="../insert/insert-product-room-function.php" method="post" >
            <div class="form-group">
                <div class="row">
                    <div class="col-auto">
                        <label for="pName">Room Product Name</label>
                        <input name="pname" required type="text" class="form-control" id="pName" aria-describedby="pHelp" placeholder="Enter Room Product Name" >
                        <small id="pHelp" class="form-text text-muted">Please enter the name of the new room product you want to create!</small><br>

                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="pLoc">Room Location</label>
                            <select required multiple class="form-control" id="pLoc" name="ploc">
                                <?php
                                foreach ($roomLoc as $loc){
                                    ?>
                                    <option><?php echo $loc;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="furStyle">Room Style</label>
                            <select required multiple class="form-control" id="furStyle" name="pstyle">
                                <?php
                                foreach ($styles as $style){
                                    ?>
                                    <option value="<?php echo $styles_id[$style_index++]; ?>" > <?php echo $style;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <label for="pPrice">Room Total Area</label>
                        <input id="rtaPrice" type="number" required name="rta" min="0" max="100" placeholder="Max 100" step="any">
                        <div class="w-100"></div>
                        <label for="pPrice">Price</label>
                        <input id="pPrice" type="number" required name="pprice" min="0" max="10000" placeholder="Max 10000" step="any">
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="pDescription">Enter the room product description!</label>
                    <textarea required name="pdes" class="form-control" id="pDescription" rows="3"></textarea>
                </div>
                <br>
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="rfDescription">Enter the room floor description!</label>
                            <textarea required name="rfdes" class="form-control" id="rfDescription" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="rfStyle">Room Floor Style</label>
                            <select required multiple class="form-control" id="rfStyle" name="rfstyle">
                        <?php
                        foreach ($rfTypes as $rfType){
                            ?>
                            <option > <?php echo $rfType;?></option>
                            <?php
                        }
                        ?>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <label for="rfMaterials">Materials the room floor will be made of:</label>
                <div class="form-check" id="rfMaterials">
                    <div class="row">
                        <?php foreach ($materials as $mat){
                            ?>
                        <div class="col-2">
                            <input name="rfmaterials[<?php echo $mat_index?>]" class="form-check-input" type="checkbox" value="<?php echo $mat;?>" id="rfmaterial<?php echo $mat;?>">
                            <label class="form-check-label" for="rfmaterial<?php echo $mat;?>">
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
                <br>
                <label for="roomComponents">Room Components the room will be made of:</label>
                <div class="form-check" id="roomComponents">
                    <div class="row">
                        <?php foreach ($rc as $component){
                        ?>
                        <div class="col-2">
                            <input name="rc[<?php echo $rc_index?>]" class="form-check-input" type="checkbox" value="<?php echo $component;?>" id="material<?php echo $component;?>">
                            <label class="form-check-label" for="material<?php echo $component;?>">
                                <?php echo $component ?>
                            </label>
                        </div>
                        <?php
                        $rc_index++;
                        if ($rc_index % 6 == 0)
                        {
                        ?>
                    </div>
                    <div class="row">
                        <?php
                        }
                        } ?>
                    </div>
                </div>
                <br>
            <div class="row">
                <div class="col-auto">
                    <input name="piccover" required type="text" class="form-control" id="pCover" aria-describedby="picHelp" placeholder="Cover Photo Path" >
                </div>
                <div class="col-auto">
                    <input name="picfront" required type="text" class="form-control" id="pFront" aria-describedby="picHelp" placeholder="Front Photo Path" >
                </div>
                <div class="col-auto">
                    <input name="picside" required type="text" class="form-control" id="pSide" aria-describedby="picHelp" placeholder="Side Photo Path" >
                </div>
                <div class="col-auto">
                    <input name="picback" required type="text" class="form-control" id="pBack" aria-describedby="picHelp" placeholder="Back Photo Path" >
                </div>
            </div>
            <br>
            <input type="hidden" name="pmode" value="1" />
            <input type="hidden" name="ppop" value="0" />
            <input type="hidden" name="prate" value="0" />

            <div class="row">
                <div class="col-auto">
                    <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-auto mt-2">
                    <input name="pfeat" class="form-check-input" type="checkbox" value="1" id="pFeat">
                    <label class="form-check-label" for="pFeat">
                        Featured?
                    </label>
                </div>
            </div>
            </div>
        </form>
    </div>
    <?php

}
else{

    ?>

    <div class="jumbotron">
        <h1> Oops looks like you are trying to add a furniture product, but there are no materials, styles or room components!</h1>
        <p>Try adding one first!</p>
        <a class="btn btn-primary" href="../forms/insert-material.php">Add Material!</a>
        <a class="btn btn-primary" href="../forms/insert-style.php">Add Style!</a>
        <a class="btn btn-primary" href="../forms/insert-room-component.php">Add Room Component!</a>
    </div>

    <?php

}
require_once "../footer.php";

?>

