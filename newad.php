<?php
session_start();
$pageTitle = 'Create New Item';
include 'init.php';

if (isset($_SESSION['user'])) {

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $formErrors = array();

    $name     = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $desc     = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price    = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
    $country  = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
    $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
    $category = filter_var($_POST['categories'], FILTER_SANITIZE_NUMBER_INT);
    $tags     = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
    $date     = date("y/m/d");
    $member   = $_SESSION['uid'];



    if (strlen($name) < 4) {
      $formErrors[] =  'Sorry Item Title must be larger than 4 characters';
    }
    if (strlen($desc) < 10) {
      $formErrors[] =  'Sorry Item Description must be at least 10 characters';
    }
    if (strlen($country) < 2) {
      $formErrors[] =  'Sorry Country must be larger than 2 caracters';
    }
    if (empty($price)) {
      $formErrors[] =  'Sorry Item Price must be not Empty';
    }
    if (empty($status)) {
      $formErrors[] =  'Sorry Item Status must be not Empty';
    }
    if (empty($category)) {
      $formErrors[] =  'Sorry Item Category must be not Empty';
    }

    // if no error exist 
    if (empty($formErrors)) {

      // insert  user information in DB
      $stmt = $con->prepare("INSERT INTO items SET Naming=?, Descripe=?, Price=?, Country=?, Situation=?, Add_Date=?, Cat_ID=?, Member_ID=?, tags=?");
      $stmt->execute(array($name, $desc, $price, $country, $status, $date, $category, $member, $tags));

      // echo successful message 
      if ($stmt) {
        $sucessMessage = "<div class='alert alert-success'> Item is Added </div>";
      }
    }
  }
?>
  <h1 class="text-center"><?php echo $pageTitle ?></h1>
  <div class="create-ad block">
    <div class="container">
      <div class="card">
        <div class="card-header">
          <?php echo $pageTitle ?>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="form-horizontal">
                <!-- start name field  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-10 col-md-9">
                    <input type="text" name="name" class="form-control live" data-class=".live-title" required="required" placeholder="Name of the item">
                  </div>
                </div>
                <!-- end name field  -->
                <!-- start desc field  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-10 col-md-9">
                    <input type="text" name="description" class="form-control live" data-class=".live-desc" required="required" placeholder="Description of the item">
                  </div>
                </div>
                <!-- end desc field  -->
                <!-- start price field  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Price</label>
                  <div class="col-sm-10 col-md-9">
                    <input type="text" value="$" name="price" class="form-control live" data-class=".live-price" required="required" placeholder="Price of the item">
                  </div>
                </div>
                <!-- end price field  -->
                <!-- start country field  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Country</label>
                  <div class="col-sm-10 col-md-9">
                    <input type="text" name="country" class="form-control" required="required" placeholder="country of the item">
                  </div>
                </div>
                <!-- end country field  -->
                <!-- start status field  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-10 col-md-9">
                    <select name="status">
                      <option value="0">...</option>
                      <option value="1">New</option>
                      <option value="2">Like New</option>
                      <option value="3">Used</option>
                      <option value="4">Very Old</option>
                    </select>
                  </div>
                </div>
                <!-- start categories field  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Categories</label>
                  <div class="col-sm-10 col-md-9">
                    <select name="categories">
                      <option value="">....</option>
                      <?php
                      $categories = getAllFrom('*', 'categories', '', 'ID');
                      foreach ($categories as $category) {
                        echo "<option value='" . $category['ID'] . "'>" . $category['Naming'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <!-- end categories field  -->
                <!-- start Tags field  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Tags</label>
                  <div class="col-sm-10 col-md-9">
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
              <!-- image -->
            </div>
            <div class="col-md-4">
              <div class="card item-box live-preview">
                <span class="price-tag">
                  $<span class="live-price">0</span>
                </span>
                <img src="./layout/images/3.jpg" alt="" class="image-resposive card-img-top">
                <div class="card-body">
                  <h3 class="card-title live-title">Title</h3>
                  <p class="card-text live-desc">Description</p>
                </div>
              </div>
            </div>
          </div>
          <!-- start looping throgh errors -->
          <?php
          if (!empty($formErrors)) {
            foreach ($formErrors as $error) {
              echo "<div class='alert alert-danger'>" . $error . "</div> <br>";
            }
          }
          if (isset($sucessMessage)) {
            echo $sucessMessage;
          }
          ?>
          <!-- end looping throgh errors -->
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