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

if (!in_array('pharmacy report', $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'Pharmacy Report', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

main_menu($_SESSION['uid'],  
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], '', true, $con);

?>

<div class="container" style="margin-top: 10px; text-align:center;">
 <div class="page-header text-center" style='margin-bottom:5px;
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   Pharmacy Report</h3>
 </div>

<script>
  $(function() {
    $( "#from_date" ).datepicker({ dateFormat: "yy-mm-dd" });
    $( "#to_date" ).datepicker();
		
   });
</script>

<form action='pharmacy_report.php' method='POST' />
  <select name='table'>
   <option value='arv'>ARV's</option>
   <option value='non_arv'>NON ARV's</option> 
   <option value='pediatrics'>PEDIATRICS</option>
  </select>
  <p>
   <label>Year</label>   
   <input type="year" name="year" value='<?php echo date("Y");?>' />
  </p>
 <div class="form-actions">
  <button type='submit' class="btn btn-primary" name='action'>Submit</button>
 </div>
</form>

<?php main_footer(); ?>
