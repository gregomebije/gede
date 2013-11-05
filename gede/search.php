<?php
require_once "form.inc";
$con = connect();

// no term passed - just exit early with no response
if (empty($_GET['term'])) exit ;
$q = strtolower($_GET["term"]);

if (empty($_GET['table'])) exit ;

// remove slashes if they were magically added
if (get_magic_quotes_gpc()) $q = stripslashes($q);

$columns = array();
$sql = "describe {$_GET['table']}";
$result1 = mysql_query($sql, $con) or die(mysql_error());
while ($row = mysql_fetch_array($result1)) {
  if ($row[0] == 'id')
   continue;
  $columns[] = $row[0]; 
}

$sql = "select * from {$_GET['table']} where ";
$count = 0;
foreach($columns as $col) {
  $sql .= " {$col} like '%{$q}%'";
  $count += 1;
  if ($count < count($columns))
    $sql .= " or ";
}

//echo "{$sql}<br>";

$result = array();
$result1 = mysql_query($sql, $con) or die(mysql_error());
while($row = mysql_fetch_array($result1)) { 
  foreach($columns as $col) {
     array_push($result, array("id"=>$row[$col], "label"=>$row[$col], 
      "value" => strip_tags($row[$col])));
  }
}
echo json_encode($result);
?>
