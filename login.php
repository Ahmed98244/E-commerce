<?php
session_start();
$pageTitle = 'Login';

if (isset($_SESSION['user'])) {

  header('Location: index.php');
}

include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (isset($_POST['login'])) {

    $user        = $_POST['username'];
    $password    = $_POST['password'];
    $hashedpass  = sha1($password);

    // check if the user is exist in database
    $stmt = $con->prepare("SELECT 
                                    userID, userName, userPassword 
                                  FROM 
                                    users 
                                  WHERE 
                                    userName= ? 
                                  AND 
                                    userPassword= ? ");

    $stmt->execute(array($user, $hashedpass));
    $get = $stmt->fetch();
    $count = $stmt->rowCount();

    // if count > 0 mean entery data == DB's data
    if ($count > 0) {
      $_SESSION['user'] = $user; // register username
      $_SESSION['uid']  = $get['userID']; // register userid

      header('Location: index.php');
      exit();
    }
  } else {

    $formErrors = array();

    $username  = $_POST['username'];
    $password  = $_POST['password'];
    $password2 = $_POST['password-again'];
    $hashPassword = sha1($password);
    $email     = $_POST['email'];
    $datee = date("y/m/d");

    if (isset($username)) {

      $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

      if (strlen($filterdUser) < 4) {

        $formErrors[] = '<div class="alert alert-danger"> Sorry <strong>username</strong> must be larger than <strong>4</strong> characters </div>';
      }
    }
    // password 
    if (isset($password) && isset($password2)) {

      if (empty($password)) {

        $formErrors[] = '<div class="alert alert-danger">Sorry <strong>Password</strong> cant be empty </div>';
      }

      if (sha1($password) !== sha1($password2)) {

        $formErrors[] = '<div class="alert alert-danger">Sorry <strong>Password</strong> not match </div>';
      }
    }

    if (isset($email)) {

      $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

      if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

        $formErrors[] = '<div class="alert alert-danger">Sorry <strong>Email</strong> is not valid </div>';
      }
    }
    // if there is no error processed the userAdd
    if (empty($formErrors)) {

      // check if user exit in database 
      $check = checkItem("userName", "users", $username);
      if ($check == 1) {

        $formErrors[] = "<div class='alert alert-danger'>sorry this user is exist </div>";
      } else {

        // insert  user information in DB
        $stmt = $con->prepare("INSERT INTO users SET userName=?, userPassword=?, email=?, regStatus=?, theDate=?");
        $stmt->execute(array($username, $hashPassword, $email, 0, $datee));

        // echo successful message 
        $sucessMessage = "<div class='alert alert-success'> Congrates you are now Registered </div>";
      }
    }
  }
}
?>
<div class="container login-page">
  <h1 class="text-center">
    <span class="selected" data-class="login">Login</span> |
    <span data-class="signup">Signup</span>
  </h1>

  <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type username">
    <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type password">
    <input class="btn btn-primary btn-block" type="submit" name="login" value="Login">
  </form>
  <!-- end login  -->
  <!-- start signup  -->
  <form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <input class="form-control" type="text" pattern=.{4,} title="username must be larger than 4 char" required name="username" autocomplete="off" placeholder="Type username">
    <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type password">
    <input class="form-control" type="password" name="password-again" autocomplete="new-password" placeholder="Type password again">
    <input class="form-control" type="email" name="email" autocomplete="off" placeholder="Email">
    <input class="btn btn-success btn-block" type="submit" name="signup" value="Signup">
  </form>
  <!-- end signup  -->
  <div class="errors">
    <?php
    if (!empty($formErrors)) {

      foreach ($formErrors as $error) {

        echo $error . '<br>';
      }
    }
    if (isset($sucessMessage)) {
      echo $sucessMessage;
    }
    ?>
  </div>

</div>

<?php
include $tbl . 'footer.php';
?>