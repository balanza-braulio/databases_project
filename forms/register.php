<!--Include header-->
<?php

//require '../vendor/autoload.php';
require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

if(isUser() || isAdmin())
{
    header("Location: http://jc-concepts.local/index.php");
}

?>


<div class="container mt-4 w-25">
    <form action="../CRS/register-function.php" method="post">
        <div class="form-group">
            <p class="text-center font-weight-light" style="font-size: 30px;"> Register a new account!</p>
            <label for="exampleInputEmail1">Email address</label>
            <input name="usr" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Enter New Password</label>
            <input name="pwd" type="password" class="form-control" id="exampleInputPassword1" pattern=".{8,}" required title="8 characters minimum" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>
    </form>
</div>

<?php require_once "../footer.php";?>