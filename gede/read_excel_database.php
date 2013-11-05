<?php
session_start();

error_reporting(E_ALL ^ E_NOTICE);
require_once 'excel_reader2.php';
require_once 'form.inc';
require_once 'config.inc';

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "form.inc";
require_once "menu.inc";

$con = connect();

main_menu($_SESSION['uid'],
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], '', true, $con);

?>
<div class="container" style="margin-top: 10px; text-align:center;">


<?php
if (empty($_POST['table'])) {
  echo msg_box('Please contact your vendor', '', '');
}

list($year, $month, $day) = explode("-", $_REQUEST['from_date']);
if (!checkdate($month, $day, $year)) {
  msg_box("Please specify the year in the correct format YYYY-DD-MM",
        "upload.php?table={$_REQUEST['table']}", "Back");
}

if (!empty($_FILES['file']['name'])) {

  $file1 = $_FILES["file"]["name"];
  $path_parts = pathinfo($file1);
  $ext = $path_parts['extension'];

  $newfile = 'upload/1-1temp.'.$ext.'';
  if (!is_dir("upload")) {
    mkdir("upload");
    chmod("upload", 0705);
  }

  if (!move_uploaded_file($_FILES['file']['tmp_name'], $newfile)) {
    echo "failed to copy: $newfile<br />";
  } else {
    echo 'file copied<br />';
  }

  $excel = new Spreadsheet_Excel_Reader("$newfile");

  // attempt a connection
  try {
    $pdo = 
    new PDO("mysql:dbname={$database};host={$dbserver}", 
     $dbusername, $dbpassword);
  } catch (PDOException $e) {
    die("ERROR: Could not connect: " . $e->getMessage());
  }

  //$sql = "truncate table {$_POST['table']}";
  $sql = "delete from {$_POST['table']} where date='{$_REQUEST['from_date']}'";
  mysql_query($sql, $con) or die(mysql_error());

  $columns = array();
  $columns_count = array();

  $sql = "describe {$_POST['table']}";
  $result = mysql_query($sql, $con) or die(mysql_error());
  while($row = mysql_fetch_array($result)) {
    if (($row[0] == 'id') or ($row[0] == 'date'))
      continue;
    $columns[] = $row[0];
    $columns_count[] = "?";
  }
    
  $sql = "INSERT INTO {$_REQUEST['table']} (date, " . implode(", ", $columns) 
   . ") VALUES ('{$_REQUEST['from_date']}', " . implode(", ", $columns_count) 
   . ")";


  if ($stmt = $pdo->prepare($sql)) {
    $x = 1;
    /*
    if ($_REQUEST['table'] == 'summary_statistics_nomis')
      $x = 2;
    else if ($_REQUEST['table'] == 'm_e')
      $x = 6;
    else
      $x=8;
    */
    while($x<=$excel->sheets[0]['numRows']) {
      for($i = 1; $i <= count($columns); $i++) {
        $temp = $excel->sheets[0]['cells'][$x][$i];
        if (is_numeric($temp))
          $stmt->bindParam($i, number_format($temp));
        else
          $stmt->bindParam($i, htmlentities($temp, ENT_QUOTES));
 
        //$stmt->bindParam($i, number_format($excel->sheets[0]['cells'][$x][$i]));
      }
      if (!$stmt->execute()) {
        echo msg_box("ERROR: Could not execute query: $sql. " . 
          print_r($pdo->errorInfo()), '', '');
      }  
      $x++;
    }
  } else {
    echo msg_box("ERROR: Could not prepare query: $sql. " . 
        print_r($pdo->errorInfo()), '', '');
  }

  // close connection
  unset($pdo);

  echo msg_box("Database updated", '', '');
}
main_footer(); 
?>

