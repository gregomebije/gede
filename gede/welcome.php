<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "form.inc";
require_once "menu.inc";

$con = connect();

main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   'Data Management Application', true, $con);

?>

<div class="container" style="margin-top: -15px;">
 <div class="page-header text-center"
   style='margin-bottom:5px; margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'></h3>
  </div>

  <div class="hero-unit">
   <img src='images/logo.gif' />
   <br />
   <br />
   <br />
   <br />
   <br />
  </div>

<?php main_footer(); ?>
