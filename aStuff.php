<?php
require_once "config.php";
require_once "head.php";
require_once "navbar.php";
?>
<div class="container mb-3 mt-3">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add Material</h4>
            <p class="card-text">Add a new material into the database</p>
            <a href="forms/insert-material.php" class="btn btn-primary mb-2 ">Add Material!</a>
            <h4 class="card-title ">Delete Material</h4>
            <p class="card-text">Delete a material from the database</p>
            <a href="forms/delete-material.php" class="btn btn-primary">Delete Material!</a>
        </div>
    </div>
</div>

    <div class="container mb-3 mt-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Style</h4>
                <p class="card-text">Add a new style into the database</p>
                <a href="forms/insert-style.php" class="btn btn-primary mb-2 ">Add Style!</a>
                <h4 class="card-title">Delete Style</h4>
                <p class="card-text">Delete a style from the database</p>
                <a href="forms/delete-style.php" class="btn btn-primary">Delete Style!</a>
            </div>
        </div>
    </div>

    <div class="container mb-3 mt-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Room Component</h4>
                <p class="card-text">Add a new room component into the database</p>
                <a href="forms/insert-room-component.php" class="btn btn-primary mb-2 ">Add Room Component!</a>
                <h4 class="card-title">Delete Room Component</h4>
                <p class="card-text">Delete a room component from the database</p>
                <a href="forms/delete-room-component.php" class="btn btn-primary">Delete Room Component!</a>
            </div>
        </div>
    </div>

    <div class="container mb-3 mt-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Furniture Products</h4>
                <p class="card-text">Add a new furniture product into the database</p>
                <a href="forms/insert-furniture-product.php" class="btn btn-primary mb-2 ">Add Furniture Product!</a>
                <h4 class="card-title">Delete Furniture Product</h4>
                <p class="card-text">Delete a furniture product from the database</p>
                <a href="forms/delete-furniture-product.php" class="btn btn-primary">Delete Furniture Product!</a>
            </div>
        </div>
    </div>
    <div class="container mb-3 mt-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Room Products</h4>
                <p class="card-text">Add a new room product into the database</p>
                <a href="forms/insert-room-product.php" class="btn btn-primary mb-2 ">Add Room Product!</a>
                <h4 class="card-title">Delete Room Product</h4>
                <p class="card-text">Delete a room product from the database</p>
                <a href="forms/delete-room-product.php" class="btn btn-primary">Delete Room Product!</a>
            </div>
        </div>
    </div>    <div class="container mb-3 mt-3">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add Promotion</h4>
            <p class="card-text">Add a new promotion into the database</p>
            <a href="forms/insert-promotion.php" class="btn btn-primary mb-2 ">Add Promotion!</a>
            <h4 class="card-title">Delete Promotion</h4>
            <p class="card-text">Delete a promotion from the database</p>
            <a href="forms/delete-promotion.php" class="btn btn-primary mb-2">Delete Promotion!</a>
            <h4 class="card-title">Associate Products to a Promotion</h4>
            <p class="card-text">Associate products to a promotion from the database</p>
            <a href="forms/associate-promotion-product.php" class="btn btn-primary">Associate Products to Promotion!</a>
        </div>
    </div>
</div>

<?php
require_once "footer.php";
?>