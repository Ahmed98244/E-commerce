<?php

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

/* 
** get latest record function v1.0
** function to get latest item from DB 
** $select => filed to select 
** $place => table
** numcer of records
*/
function getLatest($select, $place, $order, $limit = 5)
{

  global $con;

  $stmt = $con->prepare("SELECT $select FROM $place ORDER BY $order DESC LIMIT $limit");
  $stmt->execute();
  $row = $stmt->fetchAll();

  return $row;
}
