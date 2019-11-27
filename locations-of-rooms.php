<?php

// Connection to database
require_once "config.php";
$conn = db();

require_once "head.php";
require_once "navbar.php";

$locations = array('Kitchen', 'Living Room', 'Bedroom', 'Patio', 'Backyard', 'TV Room', 'Garage');

foreach ($locations as $location)
{
    ?>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?php echo $location; ?></h4>
                <a href="products-by-location.php?plocation=<?php echo $location;?>&pmode=1" class="btn btn-primary">See all furniture for the <?php echo $location; ?>!</a>
            </div>
        </div>
    </div>
    <?php

}

require_once "footer.php";
?>



