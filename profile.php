<?php
session_start();
$pageTitle = 'Profile';
include 'init.php';

if (isset($_SESSION['user'])) {

  $getUser = $con->prepare("SELECT * FROM users WHERE userName =?");
  $getUser->execute(array($_SESSION['user']));
  $info = $getUser->fetch();
?>
  <h1 class="text-center">My Profile</h1>
  <div class="information block">
    <div class="container">
      <div class="card">
        <div class="card-header">
          My Information
        </div>
        <div class="card-body">
          <ul class="list-unstyled">
            <li>
              <i class="fas fa-unlock-alt fa-fw"></i>
              <span>Login Name</span> : <?php echo $info['userName']; ?>
            </li>
            <li>
              <i class="fas fa-envelope-square fa-fw"></i>
              <span>Email</span> : <?php echo $info['email']; ?>
            </li>
            <li>
              <i class="fas fa-user fa-fw"></i>
              <span> Full Name</span> : <?php echo $info['fullName']; ?>
            </li>
            <li>
              <i class="fas fa-calendar fa-fw"></i>
              <span>Regidter Date</span> : <?php echo $info['theDate']; ?>
            </li>
            <li>
              <i class="fas fa-tags fa-fw"></i>
              <span>favorite Category</span> :
            </li>
          </ul>
          <button class="btn btn-primary">Edit Information</button>
        </div>
      </div>
    </div>
  </div>
  <!-- start ads  -->
  <div id="my-ads" class="My-ads block">
    <div class="container">
      <div class="card">
        <div class="card-header">
          My Items
        </div>
        <div class="card-body">
          <?php
          if (!empty(getItem('Member_ID', $info['userID']))) {
            echo '<div class="row">';
            foreach (getItem('Member_ID', $info['userID'], 1) as $item) {
              echo '<div class="col-sm-6 col-md-3">';
              echo '<div class="card item-box">';
              if ($item['Approve'] == 0) {
                echo '<span= class="aprrove-status">Not Approved</span=>';
              }
              echo '<span class="price-tag">' . $item['Price'] . '</span>';
              echo '<img src="./layout/images/3.jpg" alt="" class="image-resposive card-img-top">';
              echo ' <div class="card-body">';
              echo '<h3 class="card-title"><a href="items.php?itemid=' . $item['Items_ID'] . '">' . $item["Naming"] . '</a></h3>';
              echo '<p class="card-text">' . $item["Descripe"] . '</p>';
              echo '<div class="card-text date">' . $item["Add_Date"] . '</div>';
              echo '</div>';
              echo '</div>';
              echo '</div>';
            }
            echo '</div>';
          } else {
            echo "Sorry there is no ads to show, <a href='newad.php'>New Ad</a>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- end ads  -->
  <!-- start comments  -->
  <div class="my-comments block">
    <div class="container">
      <div class="card">
        <div class="card-header">
          Latest Comment
        </div>
        <div class="card-body">
          <?php
          $stmt = $con->prepare("SELECT comment FROM comments WHERE user_ID = ?");
          $stmt->execute(array($info['userID']));
          $comments = $stmt->fetchAll();

          if (!empty($comments)) {
            foreach ($comments as $comment) {
              echo '<p>' .  $comment['comment'] . '</p>';
            }
          } else {
            echo "there is no comments to show ";
          }
          ?>
        </div>
      </div>
    </div>
  </div>

<?php
} else {
  header('Location: login.php');
  exit();
}
include $tbl . 'footer.php';
?>