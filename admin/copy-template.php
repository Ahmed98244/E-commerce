<?php 

  ob_start();
  session_start();

  $pageTitle = '';

  if (isset($_SESSION['userName'])) {

    include 'init.php';

    $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

    if($do == 'Manage') {

      echo 'welocome you are in mange category page';

    } elseif ($do == 'Add') {
  
      echo 'welcome you are in add category page';
  
    } elseif ($do == 'Insert') {
  
      echo 'welcome you are in add insert page';

    } elseif ($do == 'Edit') {

    } elseif ($do == 'Update') {

    } elseif ($do == 'Delete') {
      
    } elseif ($do == 'Activate') {

    } 

      // footer
      include $tbl . 'footer.php';

  } else {
        
        header('Location: index.php');
        exit();
  }
    ob_end_flush();
