<?php
session_start();
error_reporting(E_ALL);
require_once("signin.inc");
require_once("form.inc");

$con = connect();
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'logout')) {
  unset($_SESSION);
  session_destroy();
  login_form("<p>Please enter username and password</p>", 'index.php', $con); 
  exit;

} else if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'login')) {
  if (empty($_REQUEST['u']) || empty($_REQUEST['p'])) {
    login_form("<p>Please enter username/password", 'index.php', $con);
    exit;
  }
  $sql = "select * from user where password=md5('{$_REQUEST['p']}')
      and name='{$_REQUEST['u']}'";
    $result = mysql_query($sql, $con) or die(mysql_error()); 
  if (mysql_num_rows($result) > 0) {
    $row = mysql_fetch_array($result);
    $_SESSION['firstname'] = $row['firstname'];  
    $_SESSION['lastname'] = $row['lastname'];  
    $_SESSION['uid'] = $row['id'];
    //my_redirect('form.php?table=products', '');

    $sql = "select p.url, p.name from permissions p join user_permissions up
      on p.id = up.pid where up.uid={$row['id']} order by up.id";
    $result2 = mysql_query($sql, $con) or die(mysql_error());
    $row2 = mysql_fetch_array($result2);

    if ($row2['name'] == 'Administrator')
      my_redirect('user.php', '');
    else 
      my_redirect($row2['url'], '');

  } else {
    login_form("<p>Wrong username/password</p>", 'index.php', $con);
    exit;
  } 
}  
login_form("<p>Please enter username and password</p>", 'index.php', $con); 
?>
