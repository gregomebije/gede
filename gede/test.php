<?php
 require_once "form.inc";
 $con = connect();

 $sql = "select * from pediatrics where id=1";
 $result = mysql_query($sql, $con) or die (mysql_error());
 while($row = mysql_fetch_array($result)) { 
   $c = count($row)/2;
   for ($i = 0; $i < $c ; $i = $i + 1)
     echo "{$row[$i]}\n";
 }
?>
