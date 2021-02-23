<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo $css; ?>normalize.css">
  <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $css; ?>style.css">
  <link rel="stylesheet" href="<?php echo $css; ?>all.min.css">
  <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
  <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">
  <title><?php getTitle() ?></title>
</head>

<body>
  <div class="upper-bar">
    <div class="container info">
      <?php
      if (isset($_SESSION['user'])) { ?>
        <img src="./layout/images/3.jpg" alt="">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav">
          <?php echo $_SESSION['user']; ?>
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="app-nav">
          <ul class="nav navbar-nav ml-auto">
            <li><a href="profile.php">My Profile</a></li>
            <li><a href="newad.php">New Item</a></li>
            <li><a href="profile.php#my-ads">New Ads</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      <?php
      } else {
      ?>
        <a href="login.php" class="d-flex flex-row-reverse">
          <span class="">Login/Signup</span>
        </a>
      <?php } ?>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Home</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="app-nav">
        <ul class="nav navbar-nav ml-auto">
          <?php
          $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "ID", "ASC");
          foreach ($allCats as $cat) {
            echo
            '<li class="nav-item">
                  <a class="nav-link" href="categories.php?pageid=' . $cat['ID'] . '">
                    ' . $cat["Naming"] . ' 
                  </a>
              </li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>