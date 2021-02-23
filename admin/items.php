<?php

/* 
============================================
== Items page
============================================
*/

ob_start();
session_start();

$pageTitle = 'Items';

if (isset($_SESSION['userName'])) {

  include 'init.php';

  $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';
  // manage page
  if ($do == 'Manage') {

    $stmt = $con->prepare("SELECT items.*
                                  ,categories.Naming AS category_name,
                                  users.userName
                            FROM
                                  items
                            INNER JOIN 
                                  categories 
                            ON 
                                  categories.ID = items.Cat_ID
                            INNER JOIN 
                                  users 
                            ON 
                                  users.userID = items.Member_ID
                            ORDER BY 
                                  Items_ID DESC");
    $stmt->execute();

    $items = $stmt->fetchAll();
    if (!empty($items)) {

?>
      <h1 class="text-center">Manage Items</h1>
      <div class="container">
        <div class="table-responsive">
          <table class="text-center main-table table table-bordered">
            <tr>
              <td>#ID</td>
              <td>Name</td>
              <td>Description</td>
              <td>Price</td>
              <td>Adding Date</td>
              <td>Category</td>
              <td>Username</td>
              <td>Control</td>
            </tr>
            <?php
            foreach ($items as $item) {
              echo "<tr>";
              echo "<td>"  .  $item['Items_ID']    . "</td>";
              echo "<td>"  .  $item['Naming']      . "</td>";
              echo "<td>"  .  $item['Descripe']    . "</td>";
              echo "<td>"  .  $item['Price']       . "</td>";
              echo "<td>"  .  $item['Add_Date']     . "</td>";
              echo "<td>"  .  $item['category_name'] . "</td>";
              echo "<td>"  .  $item['userName']      . "</td>";
              echo "<td> 
                        <a href ='items.php?do=Edit&itemid="  . $item['Items_ID'] . "' class='btn btn-success'><i class='fas fa-edit'></i> Edit</a>
                        <a href ='items.php?do=Delete&itemid=" . $item['Items_ID'] . "' class='btn btn-danger confirm'><i class='fas fa-times'></i> Delete </a>";
              if ($item['Approve'] == 0) {

                echo "<a href ='items.php?do=Approve&itemid=" . $item['Items_ID'] . "'
                          class='btn btn-info activate'>
                          <i class='fas fa-check'></i> Approve </a>";
              }
              echo "</td>";
              echo "</tr>";
            }
            ?>
          </table>
        </div>
        <a href='items.php?do=Add' class="btn btn-sm  btn-primary"> <i class="fa fa-plus"></i> Add new items</a>
      </div>
    <?php
    } else {
      echo '<div class="container">';
      echo '<div class="massage"> there is no items to show </div>';
      echo '<a href="items.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"></i> Add New Item</a>';
      echo '</div>';
    }

    // add page
  } elseif ($do == 'Add') { ?>

    <h1 class="text-center">Add New Item</h1>
    <div class="container items">
      <form action="?do=Insert" method="POST" class="form-horizontal">

        <!-- start name field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Name</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="name" class="form-control" required="required" placeholder="Name of the item">
          </div>
        </div>
        <!-- end name field  -->
        <!-- start desc field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Description</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="Description" class="form-control" required="required" placeholder="Description of the item">
          </div>
        </div>
        <!-- end desc field  -->
        <!-- start price field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Price</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="Price" class="form-control" required="required" placeholder="Price of the item">
          </div>
        </div>
        <!-- end price field  -->
        <!-- start country field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Country</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="country" class="form-control" required="required" placeholder="country of the item">
          </div>
        </div>
        <!-- end country field  -->
        <!-- start status field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Status</label>
          <div class="col-sm-10 col-md-6">
            <select name="status">
              <option value="0">...</option>
              <option value="1">New</option>
              <option value="2">Like New</option>
              <option value="3">Used</option>
              <option value="4">Very Old</option>
            </select>
          </div>
        </div>
        <!-- start members field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Member</label>
          <div class="col-sm-10 col-md-6">
            <select name="member">
              <option value="0">....</option>
              <?php
              $allMembers = getAllFrom('*', 'users', '', 'userID');
              foreach ($allMembers as $user) {
                echo "<option value='" . $user['userID'] . "'>" . $user['userName'] . "</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <!-- end members field  -->
        <!-- start categories field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Categories</label>
          <div class="col-sm-10 col-md-6">
            <select name="categories">
              <option value="0">....</option>
              <?php
              $allCats = getAllFrom('*', 'categories', 'WHERE Parent = 0', 'ID');
              foreach ($allCats as $category) {
                echo "<option value='" . $category['ID'] . "'>" . $category['Naming'] . "</option>";

                $childCats = getAllFrom('*', 'categories', "WHERE Parent = {$category['ID']}", 'ID');

                foreach ($childCats as $child) {
                  echo "<option value='" . $child['ID'] . "'>--- " . $child['Naming'] . ' part of ' .  $category['Naming'] . "</option>";
                }
              }
              ?>
            </select>
          </div>
        </div>
        <!-- end categories field  -->
        <!-- start Tags field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Tags</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="tags" class="form-control" placeholder="Separate Tags Withs Comma ( , ) ">
          </div>
        </div>
        <!-- end Tags field  -->
        <!-- start submit  -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
          </div>
        </div>
        <!-- end submit  -->
      </form>
    </div>
    <?php

    // insert page
  } elseif ($do == 'Insert') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      echo "<h1 class='text-center'>Insert Items</h1>";
      echo "<div class='container'>";

      // get variables fron the form 
      $name         = $_POST['name'];
      $descripe     = $_POST['Description'];
      $price        = $_POST['Price'];
      $country      = $_POST['country'];
      $status       = $_POST['status'];
      $member       = $_POST['member'];
      $cat          = $_POST['categories'];
      $tags         = $_POST['tags'];
      $date         = date("y/m/d");

      // validate the form 
      $formErrors = array();

      if (empty($name)) {

        $formErrors[] = 'Name can\'t be <strong> Empty </strong>';
      }
      if (empty($descripe)) {

        $formErrors[] = 'Description can\'t be <strong> Empty </strong>';
      }
      if (empty($price)) {

        $formErrors[] = 'Price can\'t be <strong> Empty </strong>';
      }
      if (empty($country)) {

        $formErrors[] = 'Country can\'t be <strong> Empty </strong>';
      }
      if ($status == 0) {

        $formErrors[] = 'You must choose the <strong> Status </strong>';
      }
      if ($member == 0) {

        $formErrors[] = 'You must choose the <strong> Member </strong>';
      }
      if ($cat == 0) {

        $formErrors[] = 'You must choose the <strong> Category </strong>';
      }

      foreach ($formErrors as $error) {

        echo " <div class='alert alert-danger'>" .  $error . "</div>";
      }

      // check if there is no error
      if (empty($formErrors)) {

        // insert  user information in DB
        $stmt = $con->prepare("INSERT INTO items SET Naming=?, Descripe=?, Price=?, Country=?, Situation=?, Add_Date=?, Cat_ID=?, Member_ID=?, tags=?");
        $stmt->execute(array($name, $descripe, $price, $country, $status, $date, $cat, $member, $tags));

        // echo successful message 
        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div>';
        redirectHome($theMsg, 'back');
      }
    } else {
      echo "<div class='container'>";
      $theMsg =  '<div class="alert alert-danger">you cannot browse this page directly </div>';
      redirectHome($theMsg);
      echo "</div>";
    }
    echo "</div>";

    // Edit page
  } elseif ($do == 'Edit') {

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    $stmt = $con->prepare("SELECT * FROM items  WHERE Items_ID= ?");
    $stmt->execute(array($itemid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) { ?>

      <h1 class="text-center">Edit Item</h1>
      <div class="container items">
        <form action="?do=Edit" method="POST" class="form-horizontal">
          <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
          <!-- start name field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="name" class="form-control" value="<?php echo $row['Naming'] ?>" required="required" placeholder="Name of the item">
            </div>
          </div>
          <!-- end name field  -->
          <!-- start desc field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="Description" class="form-control" value="<?php echo $row['Descripe'] ?>" required="required" placeholder="Description of the item">
            </div>
          </div>
          <!-- end desc field  -->
          <!-- start price field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Price</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="Price" class="form-control" value="<?php echo $row['Price'] ?>" required="required" placeholder="Price of the item">
            </div>
          </div>
          <!-- end price field  -->
          <!-- start country field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Country</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="country" class="form-control" value="<?php echo $row['Country'] ?>" required="required" placeholder="country of the item">
            </div>
          </div>
          <!-- end country field  -->
          <!-- start status field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-10 col-md-6">
              <select name="status">
                <option value="0">...</option>
                <option value="1" <?php if ($row['Situation'] == 1) {
                                    echo 'selected';
                                  } ?>>New</option>
                <option value="2" <?php if ($row['Situation'] == 2) {
                                    echo 'selected';
                                  } ?>>Like New</option>
                <option value="3" <?php if ($row['Situation'] == 3) {
                                    echo 'selected';
                                  } ?>>Used</option>
                <option value="4" <?php if ($row['Situation'] == 4) {
                                    echo 'selected';
                                  } ?>>Very Old</option>
              </select>
            </div>
          </div>
          <!-- start members field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Member</label>
            <div class="col-sm-10 col-md-6">
              <select name="member">
                <option value="0">....</option>
                <?php
                $stmt = $con->prepare("SELECT * FROM users");
                $stmt->execute();
                $users = $stmt->fetchAll();
                foreach ($users as $user) {
                  echo "<option value='" . $user['userID'] . "'";
                  if ($row['Member_ID'] == $user['userID']) {
                    echo 'selected';
                  }
                  echo ">" . $user['userName'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!-- end members field  -->
          <!-- start categories field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Categories</label>
            <div class="col-sm-10 col-md-6">
              <select name="categories">
                <option value="0">....</option>
                <?php
                $stmt = $con->prepare("SELECT * FROM categories");
                $stmt->execute();
                $categories = $stmt->fetchAll();
                foreach ($categories as $category) {
                  echo "<option value='" . $category['ID'] . "'";
                  if ($row['Cat_ID'] == $category['ID']) {
                    echo 'selected';
                  }
                  echo ">" . $category['Naming'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!-- end categories field  -->
          <!-- start Tags field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Tags</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="tags" class="form-control" placeholder="Separate Tags Withs Comma ( , )" value="<?php echo $row['tags'] ?>">
            </div>
          </div>
          <!-- end Tags field  -->
          <!-- start submit  -->
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <input type="submit" value="Save Item" class="btn btn-primary btn-sm">
            </div>
          </div>
          <!-- end submit  -->
        </form>

        <?php
        // comment page
        $stmt = $con->prepare("SELECT
                                    comments.*, users.userName AS Member
                                  FROM
                                    comments
                                  INNER JOIN
                                    users
                                  ON  
                                    users.userID = comments.user_ID
                                  WHERE 
                                    item_ID = ?");

        $stmt->execute(array($itemid));
        $records = $stmt->fetchAll();

        if (!empty($records)) {

        ?>

          <h1 class="text-center">Manage Comments</h1>
          <div class="table-responsive">
            <table class="text-center main-table table table-bordered">
              <tr>
                <td>Comment</td>
                <td>User Name</td>
                <td>Add Date</td>
                <td>Control</td>
              </tr>
              <?php
              foreach ($records as $record) {
                echo "<tr>";
                echo "<td>" .  $record['comment']  . "</td>";
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
        <?php } ?>
      </div>

<?php
    } else {
      echo "<div class='container'>";
      $theMsg = '<div class="alert alert-danger">there is no such ID </div>';
      redirectHome($theMsg);
      echo "</div>";
    }

    // updaate page
  } elseif ($do == 'Upadte') {

    echo "<h1 class='text-center'>Update Item</h1>";
    echo "<div class='container'>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // get variables fron the form 
      $id           = $_POST['itemid'];
      $name         = $_POST['name'];
      $descripe     = $_POST['Description'];
      $price        = $_POST['Price'];
      $country      = $_POST['country'];
      $status       = $_POST['status'];
      $member       = $_POST['member'];
      $cat          = $_POST['categories'];
      $tags         = $_POST['tags'];

      $formErrors = array();

      if (empty($name)) {

        $formErrors[] = 'Name can\'t be <strong> Empty </strong>';
      }
      if (empty($descripe)) {

        $formErrors[] = 'Description can\'t be <strong> Empty </strong>';
      }
      if (empty($price)) {

        $formErrors[] = 'Price can\'t be <strong> Empty </strong>';
      }
      if (empty($country)) {

        $formErrors[] = 'Country can\'t be <strong> Empty </strong>';
      }
      if ($status == 0) {

        $formErrors[] = 'You must choose the <strong> Status </strong>';
      }
      if ($member == 0) {

        $formErrors[] = 'You must choose the <strong> Member </strong>';
      }
      if ($cat == 0) {

        $formErrors[] = 'You must choose the <strong> Category </strong>';
      }

      foreach ($formErrors as $error) {

        echo " <div class='alert alert-danger'>" .  $error . "</div>";
      }

      // check if there is no error
      if (empty($formErrors)) {

        // update th DB
        $stmt = $con->prepare("UPDATE items SET Naming=?, Descripe=?, Price=?, Country=?, Situation=?, Cat_ID=?, Member_ID=?, tags=? WHERE Items_ID=?");
        $stmt->execute(array($name, $descripe, $price, $country, $status, $cat, $member, $tags, $id));


        // echo success message 
        $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() .  'Record Updated </div>';
        redirectHome($theMsg, 'back');
      }
    } else {
      $theMsg = '<div class="alert alert-danger">you cannot browse this page directly</div>';
      redirectHome($theMsg);
    }
    echo "</div>";

    // delete page
  } elseif ($do == 'Delete') {

    echo "<h1 class='text-center'>Delete Item</h1>";
    echo "<div class='container'>";
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    $check = checkItem("Items_ID", "items", $itemid);

    if ($check > 0) {

      $stmt = $con->prepare("DELETE FROM items WHERE Items_ID= :zitem");

      $stmt->bindparam(":zitem", $itemid);
      $stmt->execute();

      $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() .  'Record Deleted </div>';

      redirectHome($theMsg, 'back');
    } else {
      $theMsg = '<div class="alert alert-danger">this id is not exit </div>';
      redirectHome($theMsg);
    }

    echo '</div>';

    // approve page
  } elseif ($do == 'Approve') {

    echo "<h1 class='text-center'>Approve Item</h1>";
    echo "<div class='container'>";
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    $stmt = $con->prepare("SELECT * FROM items  WHERE Items_ID= ?");

    $check = checkItem("Items_ID", "items", $itemid);

    if ($check > 0) {

      $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Items_ID = ?");
      $stmt->execute(array($itemid));

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
ob_end_flush();
?>