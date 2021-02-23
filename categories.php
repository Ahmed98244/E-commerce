<?php include 'init.php'; ?>

<div class="container">
  <h1 class="text-center">show category</h1>
  <div class="row">
    <?php
        $category = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
        foreach(getItem('Cat_ID', $category) as $item) {
          echo '<div class="col-sm-6 col-md-3">';
            echo '<div class="card item-box">';
              echo '<span class="price-tag">'. $item['Price'] .'</span>';
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



<?php
  include $tbl . 'footer.php';
?>