<?php
require_once "form.inc";
$con = connect();

// no term passed - just exit early with no response
if (empty($_GET['term'])) exit ;
$q = strtolower($_GET["term"]);
// remove slashes if they were magically added
if (get_magic_quotes_gpc()) $q = stripslashes($q);

$result = array();
$sql = "select * from lga where name like '%{$q}%'";
$result1= mysql_query($sql, $con) or die(mysql_error());
while($row = mysql_fetch_array($result1)) { 
  array_push($result, array("id"=>$row['name'], "label"=>$row['name'], 
      "value" => strip_tags($row['name'])));
}
echo json_encode($result);
?>
