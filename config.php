<?php
///* Database credentials. Assuming you are running MySQL
//server with default setting (user 'root' with no password) */
//define('DB_SERVER', 'localhost');
//define('DB_USERNAME', 'balanza');
//define('DB_PASSWORD', 'Brauliomysql1996!');
//define('DB_NAME', 'jc_concepts');
//
///* Attempt to connect to MySQL database */
//$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
//
//// Check connection
//if($link === false){
//    die("ERROR: Could not connect. " . mysqli_connect_error());
//}
//else{
//    echo "Connected successfully!";
//}

/* php file to establish mysql connection and query database */



/* create connection,
note this connection is using a mysqli connection,
this connection is ONLY intended for MySQL databases,
for other database connections use PDO */

function db () {
    static $conn;

    /* servername name should remain static,
replace username, password, and dbname with proper parameters */
    $servername = "localhost";
    $username = "balanza";
    $password = "Brauliomysql1996!";
    $dbname = "jc_concepts";

    if ($conn===NULL){
        $conn = mysqli_connect($servername, $username, $password, $dbname);
    }

    /* verify a unsuccessful connection */
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);

    return $conn;
}
?>