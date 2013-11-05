<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "menu.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);
if (!in_array('cash collection summary', $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'Cash Collection Summary', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview')) {
  print_menu($con);
} else {
  main_menu($_SESSION['uid'],
   $_SESSION['firstname'] . " " . $_SESSION['lastname'],
   'Cash Collection Summary', true, $con);
}  

$html_header = "<div class=\"container\" style=\"margin-top: 10px\">
     <div class=\"page-header text-center\" style='margin-bottom:5px;
      margin-top:-10px;'>
      <h3 style='margin-bottom:-15px;'> Cash Collection Summary</h3>
     </div>";

list($year, $month, $day) = explode("-", $_REQUEST['from_date']);
list($year1, $month1, $day1) = explode("-", $_REQUEST['to_date']);
if ((!checkdate($month, $day, $year)) || (!checkdate($month1, $day1, $year1))) {
  echo $html_header;
  msg_box("Please specify the year in the correct format YYYY-DD-MM",
        "choose_date.php?url=cash_collection_summary", "Back");
  main_footer();
  exit;
}
?>
<div class="container" style="margin-top: 10px">
 <div class="page-header text-center" style='margin-bottom:5px; 
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   Cash Collection Summary Report 
   <?php echo "{$_REQUEST['from_date']} - {$_REQUEST['to_date']}";?> </h3>

  <?php
  $url = "cash_collection_summary.php?action=Preview";
  $url .= "&from_date={$_REQUEST['from_date']}";
  $url .= "&to_date={$_REQUEST['to_date']}";

  $type = "width=800,height=600,status=yes,resizable=yes,";
  $type .= "menubar=yes,toolbar=yes,scrollbars=yes";

  if (isset($_REQUEST['action']) && ($_REQUEST['action'] != 'Preview')) {
  ?>
  <span>
     <a
   onClick='window.open("<?php echo $url; ?>",
    "smallwin", "<?php echo $type; ?>");'>
   <img src='images/icon_printer.gif'></a>
    </span>
  <?php
  }
  ?>

 </div>

 <table cellpadding="0" cellspacing="0" border="0" 
  class="table table-striped table-bordered table-hover" id="example" >
  <thead>
   <tr>
    <th>Date</th>
    <th>Total</th>
    <th>Lab</th>
    <th>Drugs</th>
    <th>Consult</th>
    <th>Registration</th>
    <th>Others</th>
    <th>Test Code</th>
    <th>Frequency</th>
   </tr>
  </thead>

  <tbody id="example1">
  <?php
  $sum_total = array('lab'=>'0', 'drugs'=>'0',  
    'consult'=>'0', 'registration'=>'0', 'others'=>'0');

  $sql = "select * from daily_income where  date_of_visit between 
   '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}'";
  $result = mysql_query($sql, $con) or die(mysql_error());
  while($row = mysql_fetch_array($result)) {
    $total = $row['lab'] + $row['drugs'] + $row['consult'] + 
      $row['registration'] + $row['others'];
    
    foreach($sum_total as $name => $value)  
      $sum_total[$name] += $row[$name];

    echo "
     <tr>  
      <td>{$row['date_of_visit']}</td>
      <td>" . number_format($total, 2) . "</td>
      <td>" . number_format($row['lab'], 2) . "</td>
      <td>" . number_format($row['drugs'], 2) . "</td>
      <td>" . number_format($row['consult'], 2) . "</td>
      <td>" . number_format($row['registration'], 2) . "</td>
      <td>" . number_format($row['others'], 2) . "</td>
      <td>{$row['test_code']}</td>
      <td>{$row['frequency']}</td>
     </tr>";
  }
 ?>
 </tbody>
 <tfoot>
  <tr>
   <td><b>TOTAL</b></td>
  <?php
   $total = 0; 
   foreach($sum_total as $name => $value) 
     $total += $sum_total[$name];
   
   echo "<td><b>" . number_format($total, 2) . "</b></td>";

   foreach($sum_total as $name => $value) 
     echo "<td><b>" . number_format($sum_total[$name], 2) . "</b></td>";
   
  ?>
  </tr>
 </tfoot>
</table>
<?php main_footer(); ?>
