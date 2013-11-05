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

if ((!in_array('pitt report1', $temp))
  && (!in_array('pitt report2', $temp))) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'PITT Report', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

main_menu($_SESSION['uid'],
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   'PITT Report', true, $con);

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
    $( "#to_date" ).datepicker();
		
   });
   function get_lgas(target_id) {
     var sIndex = document.form1.state_id.selectedIndex;
     var state_id= document.form1.state_id.options[sIndex].value;
     var host = window.location.host;
     var url = "http://" + host + "/gede/get_lgas.php?state_id=";
     url +=  state_id + "&rand=" + Math.random();
     get_objects(url, target_id);
   }
</script>

<form name='form1' id='form1' action='<?php echo $_REQUEST['url'];?>.php'
   method='POST' />
 <fieldset>
  <label>State</label>   
  <?php  
  echo selectfield(
    array('0'=> '') + my_query("select * from state", "id", "name"), 
    'state_id', '',
    'display:inline', "get_lgas(\"lga\");");
  ?>

  <label>LGA</label>
  <div id='lga'></div>

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
