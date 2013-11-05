<?php
require_once "form.inc";
$con = connect();
echo selectfield(my_query("select * from lga 
  where state_id={$_GET['state_id']} order by id asc",
  'id', 'name'), 'lga_id','');
?>
