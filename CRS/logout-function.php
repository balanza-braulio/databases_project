<?php

require 'auth.php';

session_unset();
session_destroy();
$_SESSION = array();

header("Location: http://jc-concepts.local/logout.php");
