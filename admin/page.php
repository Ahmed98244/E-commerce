<?php

/*
    categories = [manage | edit | update | add | insert | delete | stats ]
  */

$do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

if ($do == 'Manage') {

  echo '<a href="page.php?do=Add"> Add new category + </a>';
} elseif ($do == 'Add') {

  echo 'welcome you are in add category page';
} elseif ($do == 'Insert') {

  echo 'welcome you are in add insert page';
} elseif ($do == 'Edit') {
} elseif ($do == 'Update') {
} elseif ($do == 'Delete') {
} elseif ($do == 'Activate') {
}
