<?php
require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

if(!isAdmin())
{
    header("Location: http://jc-concepts.local/error.php");
}

?>

<div class="container pt-5">

    <form  action="../insert/insert-style-function.php" method="post" >
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="styleName">Style Name</label>
                    <input required name="sname" type="text" class="form-control" id="styleName" aria-describedby="styleHelp" placeholder="Enter Style Name:" >
                    <small id="styleHelp" class="form-text text-muted">Please enter the name of the new style you want to create!</small>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="styleYear">Style Year</label>
                    <select required multiple class="form-control" id="styleYear" name="syear">
                        <?php for($i = 1920; $i < 2019; $i++)
                            {
                             ?><option><?php echo $i; ?></option> <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="styleDescription">Enter the Style description!</label>
            <textarea required name="sdes" class="form-control" id="styleDescription" rows="3"></textarea>
        </div>
        <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
    </form>

</div>

<?php


require_once "../footer.php";

?>