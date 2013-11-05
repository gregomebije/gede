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
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], '', true, $con);

?>
<div class="container" style="margin-top: 10px; text-align:center;">
<script>
  $(function() {
    $( "#from_date" ).datepicker({ dateFormat: "yy-mm-dd" });
    $( "#to_date" ).datepicker();
		
   });
</script>

<form action='read_excel_database.php' method='POST' 
  enctype='multipart/form-data'/>
 <input type='hidden' name='table' value='<?php echo $_REQUEST['table'];?>' />
 <fieldset>
  <legend>Choose File for 
   <?php echo format_label($_REQUEST['table']); ?></legend>
   <input type="file" name="file" id="file" />

  <label>Date</label>   
  <input type="text" name="from_date" id="from_date" 
    value ='<?php echo date("Y-m-d");?>'/>

 </fieldset>
 <div class="form-actions">
  <button type="submit" class="btn btn-primary">OK</button>
 </div>
</form>
<?php main_footer(); ?>
