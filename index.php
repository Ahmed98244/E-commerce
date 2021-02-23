<?php 
  session_start();
  
  $pageTitle = 'Home';

  include 'init.php';
?>
<div class="home">
  <div class="container">
    <div class="row">
      <?php
          $allItems = getAllFrom('*','items', 'where Approve = 1', 'Items_ID');
          foreach($allItems as $item) { 
            echo '<div class="col-sm-6 col-md-3">';
              echo '<div class="card item-box">';
                echo '<span class="price-tag">$'. $item['Price'] .'</span>';
                echo '<img src="./layout/images/3.jpg" alt="" class="image-resposive card-img-top">';
                echo ' <div class="card-body">';
                  echo '<h3 class="card-title"><a href="items.php?itemid='. $item['Items_ID'] .'">' . $item["Naming"] . '</a></h3>';
                  echo '<p class="card-text">'. $item["Descripe"] .'</p>';
                  echo '<div class="card-text date">'. $item["Add_Date"] .'</div>';
                echo '</div>';
              echo '</div>';
            echo '</div>';
          }
      ?>
    </div>
  </div>
</div>













<?php
  include $tbl . 'footer.php';
?>