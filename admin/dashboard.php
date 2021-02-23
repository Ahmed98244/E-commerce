<?php
session_start();
if (isset($_SESSION['userName'])) {

  //pageTitle
  $pageTitle = 'Dashboard';

  // init
  include 'init.php';

  // start Dashboard
  $usersCount = 6;

  $theLatestUser = getLatest("*", "users", "userID", $usersCount);

  $numItems = 6;

  $latestItems = getLatest("*", "items", "Items_ID", $numItems);

  $numComments = 4;

?>
  <!-- row 1  -->
  <div class="home-stats">
    <div class="container text-center">
      <h1>Dashboard</h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat st-members">
            <i class="fa fa-users"></i>
            <div class="info">
              Total Members
              <span><a href="members.php" target="_blanck"><?php echo countItems('userID', 'users') ?></a></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-pending">
            <i class="fa fa-user-plus"></i>
            <div class="info">
              Pending Members
              <span><a href="members.php?do=Manage&page=pending"><?php echo checkItem('regStatus', 'users', 0) ?></a></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-items">
            <i class="fa fa-tag"></i>
            <div class="info">
              Total Items
              <span><a href="items.php" target="_blanck"><?php echo countItems('Items_ID', 'items') ?></a></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-comments">
            <i class="fa fa-comments"></i>
            <div class="info">
              Total Comments
              <span><a href="comments.php" target="_blanck"><?php echo countItems('comm_ID', 'comments') ?></a></span>
            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- latest  users------>
    <div class="latest">
      <div class="container ">
        <div class="row">
          <div class="col-sm-6">
            <div class="card">
              <div class="card-header">
                <i class="fas fa-users"></i> Latest <?php echo $usersCount ?> Registerd Users
                <span class="icon">
                  <i class="fa fa-plus fa-lg"></i>
                </span>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <ul class="list-unstyled latest-user">
                    <?php
                    if (!empty($theLatestUser)) {
                      foreach ($theLatestUser as $user) {
                        echo '<li>';
                        echo $user['userName'];
                        echo '<a href="members.php?do=Edit&userid=' . $user['userID'] . '">';
                        echo '<span class="btn btn-success">';
                        echo  '<i class="fa fa-edit"></i>Edit';
                        echo '</span>';
                        echo '</a>';
                        echo '</li>';
                      }
                    } else {
                      echo 'there is no Record to show';
                    }
                    ?>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
          <!-- Latest Items -->
          <div class="col-sm-6">
            <div class="card">
              <div class="card-header">
                <i class="fas fa-tag"></i> Latest <?php echo $numItems ?> Items
                <span class="icon iconn">
                  <i class="fa fa-plus fa-lg"></i>
                </span>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <ul class="list-unstyled latest-user">
                    <?php
                    if (!empty($latestItems)) {
                      foreach ($latestItems as $item) {
                        echo '<li>';
                        echo $item['Naming'];
                        echo '<a href="items.php?do=Edit&itemid=' . $item['Items_ID'] . '">';
                        echo '<span class="btn btn-success">';
                        echo  '<i class="fa fa-edit"></i>Edit';
                        // if($item['Approve'] == 0) {

                        //       echo "<a href ='items.php?do=Approve&itemid=". $item['Items_ID'] . "' 
                        //       class='btn btn-info activate'>
                        //       <i class='fas fa-check'></i> Approve </a>";
                        // }
                        echo '</span>';
                        echo '</a>';
                        echo '</li>';
                      }
                    } else {
                      echo 'there is no Record to show';
                    }
                    ?>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- row 2 latest commment -->
        <br>
        <div class="row">
          <div class="col-sm-6">
            <div class="card">
              <div class="card-header">
                <i class="fas fa-comments"></i> Latest <?php echo $numComments ?> Comments
                <span class="icon iconnn">
                  <i class="fa fa-plus fa-lg"></i>
                </span>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <?php
                  $stmt = $con->prepare("SELECT
                              comments.*, users.userName AS Member
                            FROM
                              comments
                            INNER JOIN
                              users
                            ON  
                              users.userID = comments.user_ID
                            ORDER BY 
                              comm_ID DESC
                            LIMIT $numComments");

                  $stmt->execute();
                  $records = $stmt->fetchAll();
                  if (!empty($records)) {

                    foreach ($records as $comment) {
                      echo "<div class='comment-box'>";
                      echo '<span class="member-n">' . $comment['Member'] . '</span>';
                      echo '<p class="member-c">' . $comment['comment'] . '</p>';
                      echo "</div>";
                    }
                  } else {
                    echo 'there is no Comment to show';
                  }
                  ?>
                </li>
            </div>
          </div>
        </div>
        <!--end  row 2 latest commment -->

      </div>
    </div>

  <?php

  // footer
  include $tbl . 'footer.php';
} else {
  header('Location: index.php');
  exit();
}
