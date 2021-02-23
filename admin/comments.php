<?php
session_start();
$pageTitle = 'Comments';

// init
include 'init.php';

if (isset($_SESSION['userName'])) {

  $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

  // manage page           
  if ($do == 'Manage') {

    $stmt = $con->prepare("SELECT
                              comments.*, items.Naming AS Item_Name, users.userName AS Member
                            FROM
                                comments
                            INNER JOIN
                                items
                            ON 
                                items.Items_ID = comments.item_ID
                            INNER JOIN
                                users
                            ON  
                                users.userID = comments.user_ID
                              ORDER BY 
                                comm_ID DESC");

    $stmt->execute();
    $records = $stmt->fetchAll();
    if (!empty($records)) {

?>

      <h1 class="text-center">Manage Comments</h1>
      <div class="container">
        <div class="table-responsive">
          <table class="text-center main-table table table-bordered">
            <tr>
              <td>#ID</td>
              <td>Comment</td>
              <td>Item Name</td>
              <td>User Name</td>
              <td>Add Date</td>
              <td>Control</td>
            </tr>
            <?php
            foreach ($records as $record) {
              echo "<tr>";
              echo "<td>" .  $record['comm_ID']    . "</td>";
              echo "<td>" .  $record['comment']  . "</td>";
              echo "<td>" .  $record['Item_Name']     . "</td>";
              echo "<td>" .  $record['Member']  . "</td>";
              echo "<td>"  . $record['comment_Date']  . "</td>";
              echo "<td> 
                      <a href ='comments.php?do=Edit&commid=" . $record['comm_ID'] . "' class='btn btn-success'><i class='fas fa-edit'></i> Edit</a>
                      <a href ='comments.php?do=Delete&commid=" . $record['comm_ID'] . "' class='btn btn-danger confirm'><i class='fas fa-times'></i> Delete </a>";
              if ($record['situation'] == 0) {

                echo "<a href ='comments.php?do=Approve&commid="
                  . $record['comm_ID'] . "' class='btn btn-info activate'>
                          <i class='fas fa-check'></i> Approve </a>";
              }
              echo "</td>";
              echo "</tr>";
            }
            ?>

          </table>
        </div>
      </div>
    <?php
    } else {
      echo '<div class="container">';
      echo '<div class="massage"> there is no Comments to show </div>';
      echo '</div>';
    }

    // edit page 
  } elseif ($do == 'Edit') {

    $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;

    $stmt = $con->prepare("SELECT * FROM comments  WHERE comm_ID= ?");
    $stmt->execute(array($commid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) { ?>

      <h1 class="text-center">Edit Comment</h1>
      <div class="container">
        <div class="row">
          <div class="col-md-7">
            <form action="?do=Update" method="POST" class="form-horizontal">
              <input type="hidden" name="commid" value="<?php echo $commid ?>">
              <!-- start comment  -->
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Comment</label>
                <div class="col-sm-10 ">
                  <textarea class="form-control" name="comment" cols="10" rows="5"><?php echo $row['comment'] ?></textarea>
                </div>
              </div>
              <!-- end comment  -->
              <!-- start submit  -->
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" value="Save" class="btn btn-primary btn-lg">
                </div>
              </div>
              <!-- end submit  -->
            </form>
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

    echo "<h1 class='text-center'>Update Comment</h1>";
    echo "<div class='container'>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // get variables fron the form 
      $commid     = $_POST['commid'];
      $comment    = $_POST['comment'];

      // update th DB
      $stmt = $con->prepare("UPDATE comments SET comment=? WHERE comm_ID=?");
      $stmt->execute(array($comment, $commid));

      // echo success message 
      $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() .  'Record Updated </div>';
      redirectHome($theMsg, 'back');
    } else {
      $theMsg = '<div class="alert alert-danger">you cannot browse this page directly</div>';
      redirectHome($theMsg);
    }
    echo "</div>";

    // delete page
  } elseif ($do == 'Delete') {

    echo "<h1 class='text-center'>Delete Comment</h1>";
    echo "<div class='container'>";
    $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;

    $check = checkItem("comm_ID", "comments", $commid);

    if ($check > 0) {

      $stmt = $con->prepare("DELETE FROM comments WHERE comm_ID= :zcomment");

      $stmt->bindparam(":zcomment", $commid);
      $stmt->execute();

      $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() .  'Record Deleted </div>';
      redirectHome($theMsg);
    } else {
      $theMsg = '<div class="alert alert-danger">this id is not exit </div>';
      redirectHome($theMsg, 'back');
    }

    echo '</div>';
  }
  // activate page
  elseif ($do == 'Approve') {

    echo "<h1 class='text-center'>Approve Comment</h1>";
    echo "<div class='container'>";
    $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;

    $stmt = $con->prepare("SELECT * FROM comments  WHERE comm_ID= ? LIMIT 1");

    $check = checkItem("comm_ID", "comments", $commid);

    if ($check > 0) {

      $stmt = $con->prepare("UPDATE comments SET situation = 1 WHERE comm_ID = ?");
      $stmt->execute(array($commid));

      $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() .  'Record Approved </div>';
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
