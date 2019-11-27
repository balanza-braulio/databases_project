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

    <form  action="../insert/insert-promotion-function.php" method="post" >
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="promotionName">Promotion Name</label>
                    <input required name="proname" type="text" class="form-control" id="promotionName" aria-describedby="promoHelp" placeholder="Enter Promotion Name:" >
                    <small id="promotionHelp" class="form-text text-muted">Please enter the name of the new promotion!</small>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="promotionMod">Promotion Price Modifiers</label>
                    <select required multiple class="form-control" id="promotionMod" name="promod">
                        <?php for($i = 10; $i < 100; $i = $i + 10)
                            {
                             ?><option value="<?php echo $i;?>"><?php echo $i ;?>%</option> <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
                <label for="promoDescription">Enter the promotion description!</label>
                <textarea required name="prodes" class="form-control" id="promoDescription" rows="3"></textarea>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-4">
                    <label for="proPic">Enter the promotion picture path!</label>
                    <input name="propic" required type="text" class="form-control" id="proPic" aria-describedby="proHelp" placeholder="Promotion Photo Path" >
                </div>
            </div>
        </div>

        <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
    </form>

</div>

<?php


require_once "../footer.php";

?>