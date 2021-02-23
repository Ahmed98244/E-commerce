<?php
session_start();
$pageTitle = 'show-items';
include 'init.php';

$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

$stmt = $con->prepare("SELECT
                                items.*, categories.Naming As category_name,
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
                            WHERE 
                                Items_ID= ?
                            AND
                                Approve = 1");
$stmt->execute(array($itemid));
$count = $stmt->rowCount();

if ($count > 0) {
  $row = $stmt->fetch();

?>
  <h1 class="text-center"> <?php echo $row['Naming']; ?></h1>
  <div class="container items">
    <div class="row">
      <div class="col-md-3">
        <img src="./layout/images/3.jpg" alt="" class="image-resposive img-thumbnail card-img-top">
      </div>
      <div class="col-md-9 item-info">
        <h2><?php echo $row['Naming']; ?></h2>
        <p><?php echo $row['Descripe']; ?></p>
        <ul class="list-unstyled">
          <li>
            <i class="fas fa-calendar fa-fw"></i>
            <span>Added Date</span> : <?php echo $row['Add_Date']; ?>
          </li>
          <li>
            <i class="fas fa-money-bill fa-fw"></i>
            <span>Price </span> : $<?php echo $row['Price']; ?>
          </li>
          <li>
            <i class="fas fa-building fa-fw"></i>
            <span>Made In</span> : <?php echo $row['Country']; ?>
          </li>
          <li>
            <i class="fas fa-tags fa-fw"></i>
            <span>Category</span> : <a href="categories.php?pageid=<?php echo $row['Cat_ID'] ?>"><?php echo $row['category_name']; ?></a>
          </li>
          <li>
            <i class="fas fa-user fa-fw"></i>
            <span>Added By</span> : <a href="#"><?php echo $row['userName']; ?></a>
          </li>
          <li>
            <i class="fas fa-tags fa-fw"></i>
            <span>Tags</span> :
            <?php
            $allTags = explode(",", $row['tags']);
            foreach ($allTags as $tag) {
              $tag = str_replace(' ', ' | ', $tag);
              $tag = strtolower($tag);
              if (!empty($tag)) {
                echo  $tag;
              }
            }
            ?>
          </li>
        </ul>
      </div>
    </div>
    <hr class="custom-hr">
    <?php
    if (isset($_SESSION['user'])) { ?>
      <!-- start add-comment  -->
      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
          <div class="add-comment">
            <h3>Add your comment</h3>
            <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $row['Items_ID'] ?>" method="POST">
              <textarea name="comment" class="form-control" required></textarea>
              <input class="btn btn-primary" type="submit" value="Add Comment">
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

              $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
              $userid  = $_SESSION['uid'];
              $item    = $row['Items_ID'];
              $date     = date("y/m/d");

              if (!empty($comment)) {

                $stmt = $con->prepare("INSERT INTO
                                              comments(comment, situation, comment_Date, item_ID, user_ID)
                                          VALUES
                                              (:zcomment, 0, :zdate, :zitemid, :zuserid)");

                $stmt->execute(array(
                  'zcomment'  => $comment,
                  'zdate'     => $date,
                  'zitemid'   => $item,
                  'zuserid'   => $userid
                ));

                if ($stmt) {
                  echo "<div class='alert alert-success'>Comment Added</div>";
                }
              }
            }

            ?>
          </div>
        </div>
      </div>
      <!-- end add-comment  -->
    <?php } else {

      echo "<div class='alert alert-danger'><a href='login.php'>login</a> or register to add comment </div>";
    } ?>
    <hr class="custom-hr">
    <?php
    $stmt = $con->prepare("SELECT
                                  comments.*, users.userName AS Member
                                FROM
                                    comments
                                INNER JOIN
                                    users
                                ON  
                                    users.userID = comments.user_ID
                                WHERE
                                    item_ID =?
                                AND 
                                    situation = 1
                                  ORDER BY 
                                    comm_ID DESC");

    $stmt->execute(array($row['Items_ID']));
    $comments = $stmt->fetchAll();

    // show the comments 
    foreach ($comments as $comment) { ?>
      <div class="comment-box">
        <div class='row'>
          <div class='col-sm-2 text-center'>
            <img src="./layout/images/3.jpg" alt="" class="img-rotate image-resposive img-thumbnail">
            <?php echo $comment['Member'] ?>
          </div>
          <div class='col-sm-10'>
            <p class="lead"><?php echo $comment['comment'] ?></p>
          </div>
        </div>
      </div>
      <hr class="custom-hr">
    <?php } ?>
  </div>

<?php
} else {
  echo "<div class='container'>";
  echo "<div class='alert alert-danger'>there is no such ID or this Item is watting to Approved </div>";
  echo "</div>";
}
include $tbl . 'footer.php';
?>