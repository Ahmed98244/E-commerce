<?php
include 'connect.php';

// [Routes]
$tbl  = 'include/templates/'; // path to template directory
$lang = 'include/languages/'; // path to lang
$func = 'include/functions/'; // path to functions
$css  = 'layout/css/'; // path to cs directory 
$js   = 'layout/js/'; // path to js directory 

// include the important file
include $func . 'function.php';
include $lang . 'english.php';
include $tbl  . 'header.php';

// include navbar on all pages expext the one with noNvbar
if (!isset($noNavbar)) {
  include $tbl  . 'navbar.php';
}
