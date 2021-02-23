<?php
// Error
ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'admin/connect.php';
$sessionUser = '';
if (isset($_SESSION['user'])) {
  $sessionUser = $_SESSION['user'];
}

// [Routes]
$tbl  = 'include/templates/'; // path to template directory
$lang = 'include/languages/'; // path to lang
$func = 'include/functions/'; // path to functions
$css  = 'layout/css/'; // path to cs directory 
$js   = './layout/js/'; // path to js directory 

// include the important file
include $func . 'function.php';
include $tbl  . 'header.php';
