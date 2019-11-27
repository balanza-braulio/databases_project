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
    <form  action="../insert/insert-material-function.php" method="post" >
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label  for="materialName">Material Name</label>
                    <input required name="mname" type="text" class="form-control" id="materialName" aria-describedby="materialHelp" placeholder="Enter Material Name" >
                    <small id="materialHelp" class="form-text text-muted">Please enter the name of the new material you want to create!</small>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="materialCost">Material Cost Per Unit</label>
                    <select required multiple class="form-control" id="materialCost" name="mcost">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                        <option>13</option>
                        <option>14</option>
                        <option>15</option>
                        <option>16</option>
                        <option>17</option>
                        <option>18</option>
                        <option>19</option>
                        <option>20</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="materialDescription">Enter the Material description!</label>
            <textarea required name="mdes" class="form-control" id="materialDescription" rows="3"></textarea>
        </div>
        <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php


require_once "../footer.php";

?>