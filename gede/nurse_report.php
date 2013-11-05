<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "menu.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array('nurse report', $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'Nurse Report', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview')) {
  print_menu($con);
} else {
  main_menu($_SESSION['uid'], 
    $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
    '', true, $con);
}

$html_header = "<div class=\"container\" style=\"margin-top: 10px\">
     <div class=\"page-header text-center\" style='margin-bottom:5px;
      margin-top:-10px;'>
      <h3 style='margin-bottom:-15px;'> Nurse Report </h3>
     </div>";

list($year, $month, $day) = explode("-", $_REQUEST['from_date']);
list($year1, $month1, $day1) = explode("-", $_REQUEST['to_date']);
if ((!checkdate($month, $day, $year)) || (!checkdate($month1, $day1, $year1))) {
  echo $html_header;
  msg_box("Please specify the year in the correct format YYYY-DD-MM",     
        "choose_date.php?url=nurse_report", "Back");
  main_footer();
  exit;
}  

?>

<div class="container" style="margin-top: 10px">
 <div class="page-header text-center" style='margin-bottom:5px; 
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   Nurse Report <?php echo "{$_REQUEST['from_date']} - 
    {$_REQUEST['to_date']}";?> </h3>

  <?php
  $url ="nurse_report.php?action=Preview&from_date={$_REQUEST['from_date']}";
  $url .= "&to_date={$_REQUEST['to_date']}";

  if (isset($_REQUEST['action']) && ($_REQUEST['action'] != 'Preview')) {
  ?>
  <span>
     <a
   onClick='window.open("<?php echo $url; ?>",
    "smallwin",
   "width=800,height=600,status=yes,resizable=yes,menubar=yes,toolbar=yes,scrollbars=yes");'>
   <img src='images/icon_printer.gif'></a>
    </span>
  <?php
  }
  ?>

 </div>

<table cellpadding="0" cellspacing="0" border="0" 
  class="table table-striped table-bordered table-hover"" id="example" >
 <thead>
  <tr>
   <th>Name</th> 
   <th>GDR</th>
   <!--<th>GNGF</th>-->
   <th>Sex</th>
   <th>Age</th>
   <?php
   $skip = array('id', 'gdr_id'); 
   $columns = array();
   $sql = "describe nurse_m_and_e";
   $result = mysql_query($sql, $con) or die(mysql_error());
   while ($row = mysql_fetch_array($result))  {
     if (in_array($row[0], $skip))
       continue;
     $columns[] = $row[0];
     echo "<th>" . format_label($row[0]) . "</th>";
    }
  ?>
  </tr>
 </thead>

 <tbody id="example1">
 <?php
 $sql = "select name, gdr, cru, sex, age, " . 
    implode(",", $columns) . "
    from gdr join (nurse_m_and_e, gdr_cru) 
    on (gdr.id = nurse_m_and_e.gdr_id and gdr.id = gdr_cru.gdr_id) 
    where date_of_visit between '{$_REQUEST['from_date']}'
   and '{$_REQUEST['to_date']}'";

 $result = mysql_query($sql, $con) or die(mysql_error());
 while($row = mysql_fetch_array($result)) {
   echo "
    <tr>
     <td>{$row['name']}</td>
     <td>{$row['gdr']}</td>
     <!--<td>{$row['gngf']}</td>-->
     <td>{$row['sex']}</td>
     <td>{$row['age']}</td>
   ";
   foreach ($columns as $col) {
     echo "
     <td>{$row[$col]}</td>";
   }   
   echo "</tr>";
 }
 ?>
  
        </tbody>
        <tfoot>
         <tr>
         </tr>
        </tfoot>
       </table>

<?php
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview'))
  print_footer();
else
  main_footer();
?>

