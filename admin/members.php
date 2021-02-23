<?php
session_start();
$pageTitle = 'Members';

// init
include 'init.php';

if (isset($_SESSION['userName'])) {

  $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

  // manage page           
  if ($do == 'Manage') {

    $query = '';

    if (isset($_GET['page']) && $_GET['page'] == 'pending') {

      $query = 'AND regStatus = 0';
    }

    $stmt = $con->prepare("SELECT * FROM users WHERE groupID != 1 $query  ORDER BY userID DESC");
    $stmt->execute();

    $records = $stmt->fetchAll();
    if (!empty($records)) {
?>
      <h1 class="text-center">Manage Members</h1>
      <div class="container">
        <div class="table-responsive">
          <table class="text-center main-table table table-bordered manage-members">
            <tr>
              <td>#ID</td>
              <td>image</td>
              <td>Username</td>
              <td>Email</td>
              <td>Full Name</td>
              <td>Registerd Date</td>
              <td>Control</td>
            </tr>
            <?php
            foreach ($records as $record) {
              echo "<tr>";
              echo "<td>" .  $record['userID']    . "</td>";
              echo "<td>";
              if (empty($record['avatar'])) {
                echo "no image";
              } else {
                echo "<img src='./upload/avatars/" . $record['avatar'] . "' alt=''/>";
              }
              echo "</td>";
              echo "<td>" .  $record['userName']  . "</td>";
              echo "<td>" .  $record['email']     . "</td>";
              echo "<td>" .  $record['fullName']  . "</td>";
              echo "<td>"  . $record['theDate']  . "</td>";
              echo "<td> 
                            <a href ='members.php?do=Edit&userid=" . $record['userID'] . "' class='btn btn-success'><i class='fas fa-edit'></i> Edit</a>
                            <a href ='members.php?do=Delete&userid=" . $record['userID'] . "' class='btn btn-danger confirm'><i class='fas fa-times'></i> Delete </a>";
              if ($record['regStatus'] == 0) {

                echo "<a href ='members.php?do=Activate&userid=" . $record['userID'] . "' class='btn btn-info activate'><i class='fas fa-check'></i> Activate </a>";
              }

              echo "</td>";
              echo "</tr>";
            }
            ?>

          </table>
        </div>
        <a href='members.php?do=Add' class="btn btn-primary"> <i class="fa fa-plus"></i> Add new members</a>
      </div>
    <?php } else {
      echo '<div class="container">';
      echo '<div class="massage"> there is no Members to show </div>';
      echo '<a href="members.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"></i> Add New Member</a>';
      echo '</div>';
    }

    // add members page 
  } elseif ($do == 'Add') {  ?>

    <h1 class="text-center">Add New Member</h1>
    <div class="container">
      <form action="?do=Insert" method="POST" class="form-horizontal" enctype="multipart/form-data">
        <!-- start username  -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">username</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="username" class="form-control" autocomplete="off" required="required">
          </div>
        </div>
        <!-- end username  -->
        <!-- start password  -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Password</label>
          <div class="col-sm-10 col-md-6">
            <input type="password" name="pass" class="form-control password" autocomplete="new-password" required="required">
            <i class="show-pass fas fa-eye fa-2x"></i>
          </div>
        </div>
        <!-- end password  -->
        <!-- start email  -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-10 col-md-6">
            <input type="email" name="email" class="form-control" required="required">
          </div>
        </div>
        <!-- end email  -->
        <!-- start fullname  -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label col-md-4">Full Name</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="full" class="form-control" autocomplete="off" required="required">
          </div>
        </div>
        <!-- end fullname  -->
        <!-- start user image  -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label col-md-4">User Image</label>
          <div class="col-sm-10 col-md-6">
            <input type="file" name="avatar" class="form-control" required>
          </div>
        </div>
        <!-- end user image  -->
        <!-- start submit  -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
          </div>
        </div>
        <!-- end submit  -->
      </form>
    </div>

    <?php
    // insert page
  } elseif ($do == 'Insert') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      echo "<h1 class='text-center'>Insert Member</h1>";
      echo "<div class='container'>";

      // upload variables 
      $avatar = $_FILES['avatar'];

      $avatarName = $_FILES['avatar']['name'];
      $avatarSize = $_FILES['avatar']['size'];
      $avatarTmp  = $_FILES['avatar']['tmp_name'];
      $avatarType = $_FILES['avatar']['type'];

      // list type allowed file to upload 
      $avatarAllowedExtention = array("jpeg", "jpg", "png", "gif");

      // get avatar extention 
      $avatarExtention = strtolower(end(explode('.', $avatarName)));

      // get variables fron the form 
      $user         = $_POST['username'];
      $password     = $_POST['pass'];
      $email        = $_POST['email'];
      $name         = $_POST['full'];
      $hashPassword = sha1($password);
      $datee        = date("y/m/d");
      $regStatus    = 1;

      // validate the form 
      $formErrors = array();
      if (empty($user)) {

        $formErrors[] = 'username cant be <strong>Empty</strong>';
      }
      if (strlen($user) > 14) {

        $formErrors[] = 'username cant be more than <strong>14 characters</strong>';
      }
      if (empty($password)) {

        $formErrors[] = 'password cant be <strong>Empty</strong>';
      }
      if (empty($name)) {

        $formErrors[] = 'fullname cant be <strong>Empty</strong>';
      }
      if (empty($email)) {

        $formErrors[] = 'email cant be <strong>Empty</strong>';
      }
      if (!empty($avatarName) && !in_array($avatarExtention, $avatarAllowedExtention)) {

        $formErrors[] = 'this extention is not <strong>Allowed</strong>';
      }
      if (empty($avatarName)) {

        $formErrors[] = 'must upload your image it\'s<strong>Reuired</strong>';
      }
      if (($avatarSize > 4194304)) {

        $formErrors[] = 'image size must be smaller than<strong>4MB</strong>';
      }

      foreach ($formErrors as $error) {

        echo " <div class='alert alert-danger'>" .  $error . "</div>";
      }

      // check if there is no error
      if (empty($formErrors)) {

        // avatar 
        $avatars = rand(0, 100000000) . '_' . $avatarName;
        move_uploaded_file($avatarTmp, "upload\avatars\\" . $avatars);

        // check if user exit in database 
        $check = checkItem("userName", "users", $user);
        if ($check == 1) {

          $theMsg =  "<div class='alert alert-danger'>sorry this user is exist </div>";
          redirectHome($theMsg);
        } else {

          // insert  user information in DB
          $stmt = $con->prepare("INSERT INTO users SET userName=?, email=?, fullName=?, avatar=?, userPassword=?, regStatus=?, theDate=?");
          $stmt->execute(array($user, $email, $name, $avatars, $hashPassword, $regStatus, $datee));

          // echo successful message 
          $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div>';
          redirectHome($theMsg, 'back');
        }
      }
    } else {
      echo "<div class='container'>";
      $theMsg =  '<div class="alert alert-danger">you cannot browse this page directly </div>';
      redirectHome($theMsg, 'back');
      echo "</div>";
    }
    echo "</div>";

    // edit page 
  } elseif ($do == 'Edit') {

    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

    $stmt = $con->prepare("SELECT * FROM users  WHERE userID= ? LIMIT 1");
    $stmt->execute(array($userid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) { ?>

      <h1 class="text-center">Edit Member</h1>
      <div class="container">
        <div class="row">
          <div class="col-md-7">
            <form action="?do=Update" method="POST" class="form-horizontal">
              <input type="hidden" name="userid" value="<?php echo $userid ?>">
              <!-- start username  -->
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">username</label>
                <div class="col-sm-10 ">
                  <input type="text" name="username" class="form-control" value="<?php echo $row['userName'] ?>" autocomplete="off" required="required">
                </div>
              </div>
              <!-- end username  -->
              <!-- start password  -->
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10 ">
                  <input type="password" name="newpass" class="form-control" autocomplete="new-password" placeholder="leave it empty contain old pass or add new">
                  <input type="hidden" name="oldpass" value="<?php echo $row['userPassword'] ?>">
                </div>
              </div>
              <!-- end password  -->
              <!-- start email  -->
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" name="email" class="form-control" value="<?php echo $row['email'] ?>" required="required">
                </div>
              </div>
              <!-- end email  -->
              <!-- start fullname  -->
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Full Name</label>
                <div class="col-sm-10">
                  <input type="text" name="full" class="form-control" value="<?php echo $row['fullName'] ?>" autocomplete="off">
                </div>
              </div>
              <!-- end fullname  -->
              <!-- start submit  -->
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" value="Save" class="btn btn-primary btn-lg">
                </div>
              </div>
              <!-- end submit  -->
            </form>
          </div>
          <div class="col-md-5">
            <div class="image">
              <img src="layout/images/member.jpg" alt="" class="rounded">
            </div>
          </div>
        </div>
      </div>
<?php
    } else {
      echo "<div class='container'>";
      $theMsg = '<div class="alert alert-danger">there is no such ID </div>';
      redirectHome($theMsg);
      echo "</div>";
    }
    // update page
  } elseif ($do == 'Update') {

    echo "<h1 class='text-center'>Update Member</h1>";
    echo "<div class='container'>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // get variables from the form 
      $id     = $_POST['userid'];
      $user   = $_POST['username'];
      $email  = $_POST['email'];
      $name   = $_POST['full'];

      // password trick 
      $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']);

      // validate the form 
      $formErrors = array();
      if (empty($user)) {

        $formErrors[] = 'username cant be empty';
      }
      if (strlen($user) > 14) {

        $formErrors[] = 'username cant be more than <strong>14 characters</strong>';
      }
      if (empty($name)) {

        $formErrors[] = 'fullname cant be empty';
      }
      if (empty($email)) {

        $formErrors[] = 'email cant be empty';
      }

      foreach ($formErrors as $error) {

        echo " <div class='alert alert-danger'>" .  $error . "</div>";
      }

      // check if there is no error
      if (empty($formErrors)) {

        $stmt2 = $con->prepare("SELECT * FROM users WHERE userName = ? AND userID !=?");
        $stmt2->execute(array($user, $id));
        $count = $stmt2->rowCount();

        if ($count == 1) {

          // echo Error message 
          $theMsg = '<div class="alert alert-danger">Sorry this user is exist</div>';
          redirectHome($theMsg, 'back');
        } else {

          // update th DB
          $stmt = $con->prepare("UPDATE users SET userName=?, email=?, fullName=?, userPassword=? WHERE userID=?");
          $stmt->execute(array($user, $email, $name, $pass, $id));

          // echo success message 
          $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() .  'Record Updated </div>';
          redirectHome($theMsg, 'back');
        }
      }
    } else {
      $theMsg = '<div class="alert alert-danger">you cannot browse this page directly</div>';
      redirectHome($theMsg);
    }
    echo "</div>";

    // delete page
  } elseif ($do == 'Delete') {

    echo "<h1 class='text-center'>Delete Member</h1>";
    echo "<div class='container'>";
    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

    $check = checkItem("userID", "users", $userid);

    if ($check > 0) {

      $stmt = $con->prepare("DELETE FROM users WHERE userID= :zuser");

      $stmt->bindparam(":zuser", $userid);
      $stmt->execute();

      $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() .  'Record Deleted </div>';

      redirectHome($theMsg, 'back');
    } else {
      $theMsg = '<div class="alert alert-danger">this id is not exit </div>';
      redirectHome($theMsg, 'back');
    }

    echo '</div>';
  }
  // activate page
  elseif ($do == 'Activate') {

    echo "<h1 class='text-center'>Activate Member</h1>";
    echo "<div class='container'>";
    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

    $stmt = $con->prepare("SELECT * FROM users  WHERE userID= ? LIMIT 1");

    $check = checkItem("userID", "users", $userid);

    if ($check > 0) {

      $stmt = $con->prepare("UPDATE users SET regStatus = 1 WHERE userID = ?");
      $stmt->execute(array($userid));

      $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() .  'Record Activated </div>';

      redirectHome($theMsg, 'back');
    } else {
      $theMsg = '<div class="alert alert-danger">this id is not exit </div>';
      redirectHome($theMsg);
    }

    echo '</div>';
  }

  // footer
  include $tbl . 'footer.php';
} else {
  header('Location: index.php');
  exit();
}
