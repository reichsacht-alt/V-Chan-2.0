<?php
require_once "includes/config.php";
if(session_status()===PHP_SESSION_NONE)session_start();
if(!isset($_SESSION['user'])){header("Location: index.php");}

$section = "myProfile";
$title = "Profile";
require_once "views/layout.php";
