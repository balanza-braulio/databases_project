<?php

require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";




$usr = htmlspecialchars($_POST['usr']);
$pwd = htmlspecialchars($_POST['pwd']);


function login($usr, $pwd){

    $conn = db();

    //// Look up for username!
    // Query definition
    $sql_s_u = "SELECT user_name, user_password_hash, user_role FROM user WHERE user_name = '" . $usr . "';";

    $result = $conn->query($sql_s_u);

    if($result->num_rows > 0){

        $row = $result->fetch_assoc();
        $db_usr = $row['user_name'];
        $db_pwd = $row['user_password_hash'];
        $db_role = $row['user_role'];

        $p_hash = hash("sha256", $pwd);

        //echo $p_hash . "<br>";

        if($p_hash == $db_pwd)
        {
            $_SESSION['username'] = $db_usr;
            $_SESSION['role'] = $db_role;
            header("Location: http://jc-concepts.local/index.php");
            die();
            //echo $db_usr . " " . $db_pwd . " " . $db_role;
        }
        else{

            ?>

            <div class="jumbotron">
                <h1> Oops looks like the credentials are incorrect!</h1>
                <p>Try again!</p>
                <a class="btn btn-primary" href="../forms/login.php">Try Again!</a>
            </div>

            <?php

        }
    }
    else{

        ?>

        <div class="jumbotron">
            <h1> Oops looks like the credentials are incorrect!</h1>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/login.php">Try Again!</a>
        </div>

        <?php
    }
}

if(isUser() || isAdmin())
{
    header("Location: http://jc-concepts.local/index.php");
}
else{
    login($usr, $pwd);
}

require_once "footer.php";