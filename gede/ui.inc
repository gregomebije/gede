<?php
require_once("form.inc");


function main_menu($con) {
  echo "
   <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
    'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
    <html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en'>
    <head>
     <title>" . 
   get_value('entity', 'name', 'id', '1', $con) 
   . "</title>
   ";
   ?>
     <link rel="stylesheet" type="text/css" 
       href="themes/base/jquery.ui.all.css">
	
     <script src="js/jquery-1.4.2.min.js"></script>
     <script src="ui/jquery.ui.core.js"></script>
     <script src="ui/jquery.ui.widget.js"></script>
     <script src="ui/jquery.ui.datepicker.js"></script>
     <script src="ui/jquery.ui.autocomplete.js"></script>
     <script src="ui/jquery.ui.mouse.js"></script>
     <script src="ui/jquery.ui.button.js"></script>
     <script src="ui/jquery.ui.draggable.js"></script>
     <script src="ui/jquery.ui.position.js"></script>
     <script src="ui/jquery.ui.resizable.js"></script>
     <script src="ui/jquery.ui.dialog.js"></script>
     <script src="ui/jquery.effects.core.js"></script>
     <script type="text/javascript" src="js/jquery-ui-1.8.custom.min.js">
      </script>
     <link rel='stylesheet' type='text/css' href='form.css'>
    </head>
    <?php
    echo "
    <body>
     <div class='header'>
      <p> " . get_value('entity', 'name', 'id', '1', $con) . "</p>
      <p>" . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . " 
      <a href='index.php?action=logout'>Logout</a>
     </div>
     <div class='bar'></div>
     <div class='navigation'>
      <ul>
     ";
    //Lets generate our menu items from tables in the database
 
  $skip = array('entity', 'user', 'user_permissions');
  $sql = "show tables";
  $result = mysql_query($sql, $con) or die(mysql_error());
  while($row = mysql_fetch_array($result)) {
    if (in_array($row[0], $skip))
      continue;
    $menu["form.php?table={$row[0]}"] = 
      ucwords(strtolower(str_replace('_', ' ', $row[0])));
  }
  generate_menu("Manage", $menu);
  unset($menu);

  $menu["clinical_monthly_report.php"] = "Clinical Monthly Report";
  $menu["nurse_weekly_report.php"] = "Nurse Weekly Report";
  $menu["client_treatment_report.php"] = "Consultation and Treatment Report";
  $menu["nomis.php"] = "NOMIS";
  $menu["pitt.php"] = "PITT";
  $menu["dhis.php"] = "DHIS";
  generate_menu("Reports", $menu);
  unset($menu);

  $menu["backup_restore.php"] = "Backup/Restore";
  $menu["user.php"] = "Users";
  $menu["form.php?table=entity"] = "Entity";
  generate_menu("Settings", $menu);
  unset($menu);

  
  echo "
    </ul>
   </div>
   <div class='content'>";
}

function main_footer() {
  echo "
     </div> <!-- End of content -->
   </body>
  </html>";
}

function login_form($message, $con) { 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
 <title>
   <?php echo get_value('entity', 'name', 'id', '1', $con); ?>
 </title>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 <link rel="stylesheet" type="text/css" href="form2.css">
</head>
<body>
 <form name='form1' action='index.php' method='post'>
 <table border="0" class='table-style'>
  <tr class='class1'>
   <td colspan='2' style='text-align:center;font-size:1.5em;'>
   <?php echo get_value('entity', 'name', 'id', '1', $con); ?>
   </td></tr>
  <tr class='class1'><td colspan='2' style='text-align:center; font-size:0.7em; font-weight:normal;'></td></tr>
	 
  <?php
  if (!empty($message)) 
    echo "<tr><td style='text-align:center;' colspan='2'><b>$message</b></td></tr>";
  else 
    echo "<tr><td style='text-align:center;' colspan='2'><b>Log in</b></td></tr>";
  ?>
  <tr>
   <td>Username</td>
   <td><input id='u' name='u' autocomplete='off' type='text' size='40'></td>
  </tr>
  <tr>
   <td>Password</td>
   <td><input id='p' name='p' autocomplete='off' type='password' size='40'></td>
  </tr>
  <input type='hidden' name='action' value='login'>
  <tr>
   <td style='text-align:center;' colspan='2'><input type='submit'   value='     Login      '></td>
  </tr>
 </table>
 </form>
</body>
</html>
<?php
}
?>
