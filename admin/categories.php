<?php
/* 
============================================
== Categories page
============================================
*/

ob_start();
session_start();

$pageTitle = 'Categories';

if (isset($_SESSION['userName'])) {

  include 'init.php';

  $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

  // manage page
  if ($do == 'Manage') {

    $sort = 'ASC';
    $sort_array = array('ASC', 'DESC');

    if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

      $sort = $_GET['sort'];
    }

    $stmt = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort");
    $stmt->execute();
    $cats = $stmt->fetchAll();

    if (!empty($cats)) {
?>

      <!-- view the data from DB -->
      <h1 class="text-center">Manage Categories</h1>
      <div class="container">
        <div class="card">
          <div class="card-header">
            <i class="fas fa-edit"></i> Manage Categories
            <div class="option">
              <i class='fas fa-sort'></i> Ordering [
              <a href="?sort=ASC" class="<?php if ($sort == 'ASC') {
                                            echo 'active';
                                          } ?>">ASC</a> /
              <a href="?sort=DESC" class="<?php if ($sort == 'DESC') {
                                            echo 'active';
                                          } ?>">DESC</a> ]
              <i class='fas fa-eye'></i> View [ <span class="active" data-view="full">Full</span> / <span data-view="classic">Classic</span> ]
            </div>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item  categories">
              <?php
              foreach ($cats as $cat) {
                echo "<div class='cat'>";
                echo "<div class='hidden-button'>";
                echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit </a>";
                echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "'' class='confirm btn btn-xs btn-danger'><i class='fas fa-times'></i> Delete </a>";
                echo "</div>";
                echo "<h3>" . $cat['Naming'] . "</h3>";
                echo "<div class='full-view'>";
                echo "<p>";
                if ($cat['Descripe'] == '') {
                  echo 'is empty';
                } else {
                  echo $cat['Descripe'];
                }
                echo "</p>";

                if ($cat['Visibility'] == 1) {
                  echo "<span class='visible'><i class='fas fa-eye'></i> Hidden</span>";
                }
                if ($cat['Allow_Comment'] == 1) {
                  echo "<span class='comm'><i class='fas fa-times'></i> Comment disabled</span>";
                }
                if ($cat['Allow_Ads'] == 1) {
                  echo "<span class='ads'><i class='fas fa-eye'></i> Ads disabled</span>";
                }
                echo "</div>";
                // get child categories 
                $childCats = getAllFrom("*", "categories", "WHERE Parent = {$cat['ID']}", "ID", "ASC");
                if (!empty($childCats)) {
                  echo "<h4 class='chiled-head'>Child Categories</h4>";
                  echo "<ul class='list-unstyled chiled'>";
                  foreach ($childCats as $c) {
                    echo  "<li class='child-link'>
                          <a href='categories.php?do=Edit&catid=" . $c['ID'] . "'>" . $c['Naming'] . "</a>
                          <a href='categories.php?do=Delete&catid=" . $c['ID'] . "'class='confirm show-delete'> Delete </a>
                        </li>";
                  }
                  echo "</ul>";
                }
                echo "</div>";
                echo "<hr>";
              }
              ?>
            </li>
          </ul>
        </div>
        <a class="add btn btn-primary" href="categories.php?do=Add"> <i class="fas fa-plus"></i> Add New Category</a>
      </div>

    <?php
    } else {
      echo '<div class="container">';
      echo '<div class="massage"> there is no items to show </div>';
      echo '<a class="add btn btn-primary" href="categories.php?do=Add"> <i class="fas fa-plus"></i> Add New Category</a>';
      echo '</div>';
    }
    // add page
  } elseif ($do == 'Add') { ?>

    <h1 class="text-center">Add New Category</h1>
    <div class="container">
      <form action="?do=Insert" method="POST" class="form-horizontal">
        <!-- start name field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Name</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of the category">
          </div>
        </div>
        <!-- end name field  -->
        <!-- start description field  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Description</label>
          <div class="col-sm-10 col-md-6">
            <input type="text" name="description" class="form-control" placeholder="Descripe the category">
          </div>
        </div>
        <!-- end description field  -->
        <!-- start ordering  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Ordering</label>
          <div class="col-sm-10 col-md-6">
            <input type="number" name="order" class="form-control" placeholder="Number to arrange the categories">
          </div>
        </div>
        <!-- end ordering  -->
        <!-- start category type  -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Parent?</label>
          <div class="col-sm-10 col-md-6">
            <select name="parent">
              <option value="0">None</option>
              <?php
              $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "ID", "ASC");
              foreach ($allCats as $cat) {
                echo "<option value='" . $cat['ID'] . "'>" . $cat["Naming"] . "</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <!-- end category type  -->
        <!-- start visiblity  -->
        <div class="form-group">
          <label class="col-sm-2 control-label col-md-4">Visible</label>
          <div class="col-sm-10 col-md-6">
            <div>
              <input id="visible-yes" type="radio" name="visible" value="0" checked>
              <label for="visible-yes">Yes</label>
            </div>
            <div>
              <input id="visible-no" type="radio" name="visible" value="1">
              <label for="visible-no">No</label>
            </div>
          </div>
        </div>
        <!-- end visibility  -->
        <!-- start Commenting  -->
        <div class="form-group">
          <label class="col-sm-2 control-label col-md-4">Commenting</label>
          <div class="col-sm-10 col-md-6">
            <div>
              <input id="comm-yes" type="radio" name="comment" value="0" checked>
              <label for="comm-yes">Yes</label>
            </div>
            <div>
              <input id="comm-no" type="radio" name="comment" value="1">
              <label for="comm-no">No</label>
            </div>
          </div>
        </div>
        <!-- end Commenting  -->
        <!-- start Allow-add  -->
        <div class="form-group">
          <label class="col-sm-2 control-label col-md-4">Allow Add</label>
          <div class="col-sm-10 col-md-6">
            <div>
              <input id="ads-yes" type="radio" name="ads" value="0" checked>
              <label for="ads-yes">Yes</label>
            </div>
            <div>
              <input id="ads-no" type="radio" name="ads" value="1">
              <label for="ads-no">No</label>
            </div>
          </div>
        </div>
        <!-- end Allow-add  -->
        <!-- start submit  -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
          </div>
        </div>
        <!-- end submit  -->
      </form>
    </div>
    <?php
    // insert page 
  } elseif ($do == 'Insert') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      echo "<h1 class='text-center'>Insert Category</h1>";
      echo "<div class='container'>";

      // get variables fron the form 
      $name         = $_POST['name'];
      $descripe     = $_POST['description'];
      $parent     = $_POST['parent'];
      $order        = $_POST['order'];
      $visibility   = $_POST['visible'];
      $comment      = $_POST['comment'];
      $adds         = $_POST['ads'];

      // check if category exit in database 
      $check = checkItem("Naming", "categories", $name);
      if ($check == 1) {

        $theMsg =  "<div class='alert alert-danger'>sorry this category is exist </div>";
        redirectHome($theMsg, 'back');
      } else {

        // insert  user information in DB
        $stmt = $con->prepare("INSERT INTO categories SET Naming=?, Descripe=?, Parent=?, Ordering=?, Visibility=?, Allow_Comment=?, Allow_Ads=?");
        $stmt->execute(array($name, $descripe, $parent, $order, $visibility, $comment, $adds));

        // echo successful message 
        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Category Is Added </div>';
        redirectHome($theMsg, 'back');
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

    $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

    $stmt = $con->prepare("SELECT * FROM categories  WHERE ID= ?");
    $stmt->execute(array($catid));
    $cat = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) { ?>

      <h1 class="text-center">Edit Category</h1>
      <div class="container">
        <form action="?do=Update" method="POST" class="form-horizontal">
          <input type="hidden" name="catid" value="<?php echo $catid; ?>">
          <!-- start name field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of the category" value="<?php echo $cat['Naming'] ?>">
            </div>
          </div>
          <!-- end name field  -->
          <!-- start description field  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="description" class="form-control" placeholder="Descripe the category" value="<?php echo $cat['Descripe'] ?>">
            </div>
          </div>
          <!-- end description field  -->
          <!-- start ordering  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Ordering</label>
            <div class="col-sm-10 col-md-6">
              <input type="number" name="order" class="form-control" placeholder="Number to arrange the categories" value="<?php echo $cat['Ordering'] ?>">
            </div>
          </div>
          <!-- end ordering  -->
          <!-- start category type  -->
          <div class="form-group">
            <label class="col-sm-2 control-label">Parent?</label>
            <div class="col-sm-10 col-md-6">
              <select name="parent">
                <option value="0">None</option>
                <?php
                $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "ID", "ASC");
                foreach ($allCats as $c) {
                  echo "<option value='" . $c['ID'] . "'";
                  if ($cat['Parent'] == $c['ID']) {
                    echo "selected";
                  }
                  echo ">" . $c['Naming'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!-- end category type  -->
          <!-- start visiblity  -->
          <div class="form-group">
            <label class="col-sm-2 control-label col-md-4">Visible</label>
            <div class="col-sm-10 col-md-6">
              <div>
                <input id="visible-yes" type="radio" name="visible" value="0" <?php if ($cat['Visibility'] == 0) {
                                                                                echo 'checked';
                                                                              } ?>>
                <label for="visible-yes">Yes</label>
              </div>
              <div>
                <input id="visible-no" type="radio" name="visible" value="1" <?php if ($cat['Visibility'] == 1) {
                                                                                echo 'checked';
                                                                              } ?>>
                <label for="visible-no">No</label>
              </div>
            </div>
          </div>
          <!-- end visibility  -->
          <!-- start Commenting  -->
          <div class="form-group">
            <label class="col-sm-2 control-label col-md-4">Commenting</label>
            <div class="col-sm-10 col-md-6">
              <div>
                <input id="comm-yes" type="radio" name="comment" value="0" <?php if ($cat['Allow_Comment'] == 0) {
                                                                              echo 'checked';
                                                                            } ?>>
                <label for="comm-yes">Yes</label>
              </div>
              <div>
                <input id="comm-no" type="radio" name="comment" value="1" <?php if ($cat['Allow_Comment'] == 1) {
                                                                            echo 'checked';
                                                                          } ?>>
                <label for="comm-no">No</label>
              </div>
            </div>
          </div>
          <!-- end Commenting  -->
          <!-- start Allow-add  -->
          <div class="form-group">
            <label class="col-sm-2 control-label col-md-4">Allow Add</label>
            <div class="col-sm-10 col-md-6">
              <div>
                <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) {
                                                                        echo 'checked';
                                                                      } ?>>
                <label for="ads-yes">Yes</label>
              </div>
              <div>
                <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) {
                                                                        echo 'checked';
                                                                      } ?>>
                <label for="ads-no">No</label>
              </div>
            </div>
          </div>
          <!-- end Allow-add  -->
          <!-- start submit  -->
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <input type="submit" value="Save" class="btn btn-primary btn-lg">
            </div>
          </div>
          <!-- end submit  -->
        </form>
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

    echo "<h1 class='text-center'>Update Category</h1>";
    echo "<div class='container'>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // get variables fron the form 
      $id           = $_POST['catid'];
      $name         = $_POST['name'];
      $descripe     = $_POST['description'];
      $order        = $_POST['order'];
      $parent       = $_POST['parent'];
      $visibility   = $_POST['visible'];
      $comment      = $_POST['comment'];
      $adds         = $_POST['ads'];

      // update th DB
      $stmt = $con->prepare("UPDATE categories SET Naming=?, Descripe=?, Ordering=?, Parent=?, Visibility=?, Allow_Comment=?, Allow_Ads=? WHERE ID=?");
      $stmt->execute(array($name, $descripe, $order, $parent, $visibility, $comment, $adds, $id));

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

    echo "<h1 class='text-center'>Delete Member</h1>";
    echo "<div class='container'>";

    $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

    $check = checkItem("ID", "categories", $catid);

    if ($check > 0) {

      $stmt = $con->prepare("DELETE FROM categories WHERE ID= :zid");

      $stmt->bindparam(":zid", $catid);
      $stmt->execute();

      $theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() .  'Record Deleted </div>';

      redirectHome($theMsg, 'back');
    } else {
      $theMsg = '<div class="alert alert-danger">this ID is not exit </div>';
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