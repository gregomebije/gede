<?
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: index.php');
    exit;
}
error_reporting(E_ALL);

require_once "form.inc";

$con = connect();

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Delete')) {
  if(empty($_REQUEST['first_col']) && empty($_REQUEST['first_col_value']) && empty($_REQUEST['table'])) {
    echo msg_box("Please specify First Column and Table to update", "form.php", "Back");
    exit;
  }
  
  $sql="select * from {$_REQUEST['table']} where {$_REQUEST['first_col']}={$_REQUEST['first_col_value']}";
  $result = mysql_query($sql) or die(mysql_error());
  if (mysql_num_rows($result) <= 0) {
    echo msg_box("Deletion denied<br> No row in {$_REQUEST['table']} with such {$_REQUEST['first_col']}", 'form.php', 'Back');
    exit;
  }
  echo msg_box("Are you sure you want to delete? ", 
    "form.php?action=confirm_delete&first_col={$_REQUEST['first_col']}&first_col_value={$_REQUEST['first_col_value']}&table={$_REQUEST['table']}", 
    'Continue to Delete');
    exit;
}
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'confirm_delete')) {
  if(empty($_REQUEST['first_col']) && empty($_REQUEST['first_col_value']) && empty($_REQUEST['table'])) {
    echo msg_box("Please specify First Column and Table to update", "form.php", "Back");
    exit;
  }
  
  $sql="select * from {$_REQUEST['table']} where {$_REQUEST['first_col']}={$_REQUEST['first_col_value']}";
  $result = mysql_query($sql) or die(mysql_error());
  if (mysql_num_rows($result) <= 0) {
    echo msg_box("Deletion denied<br> No row in {$_REQUEST['table']} with such {$_REQUEST['first_col']}", 'form.php', 'Back');
    exit;
  }
  $sql = gen_delete_sql($_REQUEST['table'], $_REQUEST['first_col'], $_REQUEST['first_col_value'], $con);
  //echo "$sql<br>";
  //function gen_delete_sql($table, $id, $con) {
  mysql_query($sql) or die(mysql_error());
	
} 
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'create')) {

  
  $arr = array('passport_image','thumbprint_image');
  foreach($arr as $ar) 
    if(!empty($_FILES[$ar]['name']))
      upload_file($ar, 'form.php?action=Add');

   //function gen_insert_sql($table, $skip, $con)
  $sql = gen_insert_sql($_REQUEST['table'], array($_REQUEST['id']), $con);
  //echo "{$sql}<br>";

  mysql_query($sql) or die(mysql_error());
  //echo "{$sql}<br>";
  
  $output = array(
     "id" => "-1",
     "fieldErrors" => array()
  );
   
  echo json_encode( $output );

  /*
  
  // Client sends:
{
    "id": -1,
    "table": "",
    "action": "create",
    "data": {
        "browser": "",
        "engine": "Webkit",
        "platform": "Win / Mac / Linux",
        "version": "535.1",
        "grade": "A"
    }
}
  //Return from server
  
  {
    "id": -1,
    "fieldErrors": [
        {
            "name": "browser",
            "status": "This field is required."
        }
    ]
  }
  */
} else if (isset($_REQUEST['action']) &&  ($_REQUEST['action'] == 'Update')) {

  if(empty($_REQUEST['first_col']) && empty($_REQUEST['first_col_value']) && empty($_REQUEST['table'])) {
    echo msg_box("Please specify First Column and Table to update", "form.php", "Back");
    exit;
  }

  $arr = array('passport_image','thumbprint_image');
  foreach($arr as $ar) 
    if(!empty($_FILES[$ar]['name']))
     upload_file($ar, 'form.php?action=Add');
 
  $sql = gen_update_sql($_REQUEST['table'], $_REQUEST['first_col'], $_REQUEST['first_col_value'], array($_REQUEST['first_col']), $con);
  //function gen_update_sql($table, $first_col, $first_col_value, $skip, $con) {
  mysql_query($sql, $con) or die(mysq_error());
  //echo "{$sql}<br>";
}
?>