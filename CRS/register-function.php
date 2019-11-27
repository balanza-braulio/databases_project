<?php

require_once "../config.php";
require_once "../head.php";
require_once "../sub-navbar.php";

$usr = htmlspecialchars($_POST['usr']);
$pwd = htmlspecialchars($_POST['pwd']);

function register($usr, $pwd){

    $conn = db();

    //// Look up for username!
    // Query definition
    $sql_s_u = "SELECT user_name, user_password_hash, user_role FROM user WHERE user_name = '" . $usr . "';";

    $user_insert = $conn->query($sql_s_u);

    if($user_insert->num_rows == 0){

        $p_hash = hash("sha256", $pwd);
        $db_role = 'user';

        //echo $usr . "<br>" . $p_hash . "<br>" . $db_role . "<br>";
        // Begin transaction to add user!
        $conn->begin_transaction();

        //Query definition
        $sql_i_u = "INSERT INTO user (user_name, user_password_hash, user_role) VALUES ('" . $usr . "', '" . $p_hash . "', '" . $db_role . "')";

        $user_insert = $conn->query($sql_i_u);

        if($user_insert === TRUE) {
            $_SESSION['username'] = $usr;
            $_SESSION['role'] = $db_role;

            header("Location: http://jc-concepts.local/registered.php");
            // Test rollback
            //$conn->rollback();
            $conn->commit();
        }
        else{

            ?>
            <div class="container mt-4 w-25">
                <div class="jumbotron">
                    <h1> Oops looks like there was an error creating your account!</h1>
                    <p><?php echo  $conn->error; ?></p>
                    <p>Try again!</p>
                    <a class="btn btn-primary" href="../forms/register.php">Try Again!</a>
                </div>
            </div>

            <?php

            $conn->rollback();

        }
//        if($p_hash == $db_pwd)
//        {
//            $_SESSION['username'] = $db_usr;
//            $_SESSION['role'] = $db_role;
//            header("Location: http://jc-concepts.local/index.php");
//            die();
//            //echo $db_usr . " " . $db_pwd . " " . $db_role;
//        }
//        else{
//
//            ?>
<!---->
<!--            <div class="jumbotron">-->
<!--                <h1> Oops looks like the credentials are incorrect!</h1>-->
<!--                <p>Try again!</p>-->
<!--                <a class="btn btn-primary" href="../forms/login.php">Try Again!</a>-->
<!--            </div>-->
<!---->
<!--            --><?php
//
//        }
    }
    else{

        ?>

        <div class="jumbotron">
            <h1> Oops looks like there is already an account with that email!</h1>
            <p>Try again!</p>
            <a class="btn btn-primary" href="../forms/register.php">Try Again!</a>
        </div>

        <?php
    }
}

if(isUser() || isAdmin())
{
    header("Location: http://jc-concepts.local/index.php");
}
else{
    register($usr, $pwd);
}

require "footer.php";