<?php

/* 
** get All function function v2.0
** function to get all the Records from ant DB table 
*/
function getAllFrom($field, $table, $where = NULL, $orderBy, $ordering = "DESC")
{

  global $con;

  $stmt = $con->prepare("SELECT $field FROM $table $where ORDER BY $orderBy $ordering");
  $stmt->execute();

  $getAll = $stmt->fetchAll();

  return $getAll;
}
/* 
** get categories function function v1.0
** function to get the Records from DB 
*/
function getCat()
{

  global $con;

  $stmt = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
  $stmt->execute();

  $cats = $stmt->fetchAll();

  return $cats;
}
/* 
** get items function function v1.0
** function to get ad-item the items from DB 
*/
function getItem($where, $value, $approve = NULL)
{

  global $con;

  $sql = $approve == NULL ? 'AND Approve = 1' : '';

  $stmt = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Items_ID DESC");

  $stmt->execute(array($value));

  $items = $stmt->fetchAll();

  return $items;
}

/* 
** function to check item in data base 
** $select = item to select [ex-> user / item / category]
** $from = like [users / categories]
** $value = the value of select [osama , box]
  */
function checkItem($select, $place, $value)
{
  global $con;

  $statment = $con->prepare("SELECT $select FROM $place WHERE $select = ?");
  $statment->execute(array($value));

  $count = $statment->rowCount();

  return $count;
}


















// echo page title

function getTitle()
{

  global $pageTitle;

  if (isset($pageTitle)) {

    echo $pageTitle;
  } else {
    echo 'Default';
  }
}

/*  
** home redirect function v2.0 
** $errorMsg = echo the error message
** $url = the link that u want to make redirect
** $seconds = seconds before Redirecting
*/
function redirectHome($theMsg, $url = null, $secondes = 3)
{

  if ($url === null) {

    $url = 'index.php';
    $link = 'Homepage';
  } else {

    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

      $url = $_SERVER['HTTP_REFERER'];
      $link = 'Previous Page';
    } else {
      $url = 'index.php';
      $link = 'Homepage';
    }
  }

  echo $theMsg;
  echo "<div class='alert alert-info'>you will be redirected to $link  after $secondes secondes.</div>";

  header("refresh:$secondes;url= $url");
  exit();
}

/* 
** count number of items v1.0
** function to count number of columns 
** $item => item to count 
** $table => table that i chosse from
*/
function countItems($item, $table)
{

  global $con;

  $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
  $stmt2->execute();
  return $stmt2->fetchColumn();
}
