<?php
session_start();
$noNavbar  = '';
$pageTitle = 'Login';
if (isset($_SESSION['userName'])) {

  header('Location: dashboard.php');
}
include 'init.php';

// check if user coming from http_Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $username     = $_POST['user'];
  $password     = $_POST['pass'];
  $hashedpass   = sha1($password);
  // check if the user is exist in database

  $stmt = $con->prepare("SELECT 
                                  userID, userName, userPassword 
                                FROM 
                                  users 
                                WHERE 
                                  userName= ? 
                                AND 
                                  userPassword= ? 
                                AND 
                                  groupID= 1
                                LIMIT 1");

  $stmt->execute(array($username, $hashedpass));
  $row = $stmt->fetch();
  $count = $stmt->rowCount();

  // if count > 0 mean entery data == DB's data
  if ($count > 0) {
    $_SESSION['userName'] = $username;
    $_SESSION['ID'] = $row['userid'];
    header('Location: dashboard.php');
    exit();
  }
}
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
  <h4 class="text-center">Admin Login</h4>
  <input class="form-control" type="text" name="user" placeholder="user name" autocomplete="off">
  <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password">
  <input class="btn btn-primary btn-block" type="submit" value="login">
</form>

<?php
include $tbl . 'footer.php';
?>