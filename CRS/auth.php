<?php

//If no session, start one!
if(session_status() == PHP_SESSION_NONE){
    session_start();
    session_regenerate_id(true);

    if (!isset($_SESSION['role'])){
        $_SESSION['role'] = 'guest';
    }

}

// Log out of session after some time!
if (isset($_SESSION['EXPIRES']) && (time() - $_SESSION['EXPIRES'] > 0)) {
    session_unset();
    session_destroy();
    $_SESSION = array();

//    // In case session expired
//    if ($_SESSION['EXPIRES'] < time() + 3600){
//// Need ajax for this!
//            <div class="modal" tabindex="-1" role="dialog">
//                <div class="modal-dialog" role="document">
//                    <div class="modal-content">
//                        <div class="modal-header">
//                            <h5 class="modal-title">You have been signed out automatically</h5>
//                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
//                                <span aria-hidden="true">&times;</span>
//                            </button>
//                        </div>
//                        <div class="modal-body">
//                            <p>Please sign in again!</p>
//                        </div>
//                        <div class="modal-footer">
//                            <a type="button" href="../login.php" class="btn btn-primary">Go to login!</a>
//                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
//                        </div>
//                    </div>
//                </div>
//            </div>
//    }

}
$_SESSION['EXPIRES'] = time() + 600;

// If user is not logged in use session id as username
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = session_id();
}

//if(session_status() == PHP_SESSION_ACTIVE)
//{
//    echo "Session active!";
//}
//else
//    echo "Session inactive!";

//if (!isset($_SESSION['role'])){
//    if($_SESSION['username'] == SID)
//        $_SESSION['role'] = 'guest';
//}

//echo $_SESSION['username'] . "<br>" . $_SESSION['role'] . "<br>";

function isAdmin(){
    return $_SESSION['role'] == 'admin' ? TRUE : FALSE;
}

function isUser(){
    return $_SESSION['role'] == 'user' ? TRUE : FALSE;
}

?>