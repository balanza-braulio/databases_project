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
$furTypes = array('Armoire', 'Bar', 'Barstool', 'Bed Frame', 'Bench', 'Bookcase', 'Chair', 'Desk', 'Cocktail Table', 'Nightstand', 'Table');
$furLoc = array('Kitchen', 'Living Room', 'Bedroom', 'Patio', 'Backyard', 'TV Room', 'Garage');


//Query definition
$sql_s_matNames = "SELECT DISTINCT material_name FROM material;";
$sql_s_style_name = "SELECT DISTINCT style_name, style_id FROM style;";

$conn = db();

// Variable definition
$style_index = 0;
$mat_index = 0;

$style_result = $conn->query($sql_s_style_name);
$result = $conn->query($sql_s_matNames);

$styles = array_fill(0, $style_result->num_rows, 'value');
$styles_id = array_fill(0, $style_result->num_rows, '0');

$materials = array_fill(0, $result->num_rows, 'value');

if($result->num_rows > 0 && $style_result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materials[$mat_index++] = $row["material_name"];
    }
    while($row = $style_result->fetch_assoc()){
        $styles[$style_index] = $row["style_name"];
        $styles_id[$style_index++] = $row["style_id"];
    }
    $style_index = 0;
    $mat_index = 0;
    ?>

    <div class="container pt-5">
        <form  action="../insert/insert-product-furniture-function.php" method="post" >
            <div class="form-group">
                <div class="row">
                    <div class="col-auto">
                        <label for="pName">Furniture Product Name</label>
                        <input name="pname" required type="text" class="form-control" id="pName" aria-describedby="pHelp" placeholder="Enter Furniture Product Name" >
                        <small id="pHelp" class="form-text text-muted">Please enter the name of the new furniture product you want to create!</small><br>
                        <label for="pPrice">Price</label>
                        <input id="pPrice" type="number" required name="pprice" min="0" max="10000" placeholder="Max 10000" step="any">
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <label for="furType">Furniture Type</label>
                            <select required multiple class="form-control" id="furType" name="furtype">
                                <?php
                                foreach ($furTypes as $type){
                                    ?>
                                    <option><?php echo $type;?></option>
                                    <?php
                                }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <label for="furLoc">Furniture Location</label>
                            <select required multiple class="form-control" id="furLoc" name="furloc">
                                <?php
                                foreach ($furLoc as $loc){
                                    ?>
                                    <option><?php echo $loc;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group">
                            <label for="furStyle">Furniture Style</label>
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
                </div>
                <br>
                <label>Physical Properties!</label>
                <div class="row">
                    <div class="col-3">
                        <label for="furLength">Furniture Length<br></label>
                        <input id="furLength" type="number" required name="l" min="0" max="10" placeholder="Max 10" step="any">
                    </div>
                    <div class="col-3">
                        <label for="furWidth">Furniture Width<br></label>
                        <input id="furWidth" type="number" required name="w" min="0" max="10" placeholder="Max 10" step="any">
                    </div>
                    <div class="col-3">
                        <label for="furHeight">Furniture Height</label>
                        <input id="furHeight" type="number" required name="h" min="0" max="10" placeholder="Max 10 " step="any">
                    </div>
                    <div class="col-3">
                        <label for="furGeo">Furniture Geometry</label>
                        <input name="g" required type="text" class="form-control" id="furGeo" aria-describedby="geoHelp" placeholder="Enter Geometry!" >
                    </div>
                </div>
                <label for="furMaterials">Materials the furniture will be made of:</label>
                <div class="form-check" id="furMaterials">
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
                <label for="pDescription">Enter the furniture product description!</label>
                <textarea required name="pdes" class="form-control" id="pDescription" rows="3"></textarea>
            </div>
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
            <input type="hidden" name="pmode" value="0" />
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
        </form>
    </div>
    <?php

}
else{

    ?>

    <div class="jumbotron">
        <h1> Oops looks like you are trying to add a furniture product, but there are no materials or styles!</h1>
        <p>Try adding one first!</p>
        <a class="btn btn-primary" href="../forms/insert-material.php">Add material!</a>
        <a class="btn btn-primary" href="../forms/insert-style.php">Add style!</a>
    </div>

    <?php

}
require_once "../footer.php";

?>

