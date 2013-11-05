<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "menu.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array('analysis of credit lab income', $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'Analysis Of Credit Lab Income', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview')) {
  print_menu($con);
} else {
  main_menu($_SESSION['uid'],
   $_SESSION['firstname'] . " " . $_SESSION['lastname'],
   'Analysis Of Credit Lab Income', true, $con);
}

$html_header = "<div class=\"container\" style=\"margin-top: 10px\">
     <div class=\"page-header text-center\" style='margin-bottom:5px;
      margin-top:-10px;'>
      <h3 style='margin-bottom:-15px;'>Analysis Of Credit Lab Income</h3>
     </div>";

list($year, $month, $day) = explode("-", $_REQUEST['from_date']);
list($year1, $month1, $day1) = explode("-", $_REQUEST['to_date']);
if ((!checkdate($month, $day, $year)) || (!checkdate($month1, $day1, $year1))) {
  echo $html_header;
  msg_box("Please specify the year in the correct format YYYY-DD-MM",
        "choose_date.php?url=analysis_of_credit_lab_income", "Back");
  main_footer();
  exit;
}

?>
<div class="container" style="margin-top: 10px">
 <div class="page-header text-center" style='margin-bottom:5px; 
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   Analysis Of Credit Lab Income
   <?php echo "{$_REQUEST['from_date']} - {$_REQUEST['to_date']}";?> </h3>
   
   <?php
     $url = "analysis_of_credit_lab_income.php?action=Preview";
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
    <th>Date Of Visit</th>
    <th>GDR/Name</th>
    <th>Description</th>
    <th>Amount</th>
   </tr>
  </thead>

  <tbody id="example1">
  <?php
  $total = 0;

  $sql = "select cli.date_of_visit, g.gdr, g.name, cli.description, 
    cli.amount from credit_lab_income cli join gdr g on (cli.gdr_id = g.id)
     where cli.date_of_visit between 
   '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}'";
  $result = mysql_query($sql, $con) or die(mysql_error());
  while($row = mysql_fetch_array($result)) {
    $total += $row['amount'];
    
    echo "
     <tr>  
      <td>{$row['date_of_visit']}</td>
      <td>{$row['gdr']} {$row['name']}</td>
      <td>{$row['description']}</td>
      <td>" . number_format($row['amount'], 2) . "</td>
     </tr>";
  }
 ?>
 </tbody>
 <tfoot>
  <tr>
   <td><b>TOTAL</b></td>
   <td></td>
   <td></td>
   <?php echo "<td><b>" . number_format($total, 2) ."</b></td>"; ?>
  </tr>
 </tfoot>
</table>
<?php main_footer(); ?>
