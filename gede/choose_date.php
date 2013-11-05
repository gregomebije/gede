<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "form.inc";
require_once "menu.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array(format_label2($_REQUEST['url']), $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    format_label($_REQUEST['url']), true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

main_menu($_SESSION['uid'],
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   format_label($_REQUEST['url']), true, $con);

?>
 <div class="container" style="margin-top: 10px; text-align:center;">
 <div class="page-header text-center" style='margin-bottom:5px;
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   <?php echo format_label($_REQUEST['url']); ?></h3>
 </div>

<script>
  $(function() {
    $( "#from_date" ).datepicker({ dateFormat: "yy-mm-dd" });
    $( "#to_date" ).datepicker({ dateFormat: "yy-mm-dd" });
		
   });
</script>

<form action='<?php echo "{$_REQUEST['url']}.php";?>' method='POST' />
 <fieldset>
  <label>From</label>   
  <input type="text" name="from_date" id="from_date" 
    value ='<?php echo date("Y-m-d", mktime(0, 0, 0, 01, 01, date('y')));?>'/>

  <label>To</label>
  <input type="text" name="to_date" id="to_date" 
     value ='<?php echo date("Y-m-d");?>'/>
 </fieldset>
 <div class="form-actions">
  <button type="submit" class="btn btn-primary" name="action">Submit</button>
 </div>
</form>
<?php main_footer(); ?>
