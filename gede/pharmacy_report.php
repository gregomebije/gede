<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
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


if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview')) {
  print_menu($con);
} else {
  main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   'Pharmacy Report', true, $con);
}

$html_header = "<div class=\"container\" style=\"margin-top: 10px\">
     <div class=\"page-header text-center\" style='margin-bottom:5px;
      margin-top:-10px;'>
      <h3 style='margin-bottom:-15px;'> Pharmacy Report </h3>
     </div>";

list($year, $month, $day) = explode("-", "{$_REQUEST['year']}-01-01");
if (!checkdate($month, $day, $year)) {
  echo $html_header;
  msg_box("Please specify the year in the correct format YYYY-DD-MM",
        "choose_pharmacy_report.php", "Back");
  main_footer();
  exit;
}

if (empty($_REQUEST['table'])) {
  echo $html_header;

  msg_box("Please specify correct parameters for Pharmacy 
      Report", "choose_pharmacy_report.php", "Back");
     main_footer();
     exit;
  }

?>
<div class="container" style="margin-top: 10px">
 <div class="page-header text-center" style='margin-bottom:5px; 
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   <?php echo strtoupper($_REQUEST['table']); ?> Report</h3>

  <?php
  $url ="pharmacy_report.php?action=Preview&table={$_REQUEST['table']}";
  $url .= "&year={$_REQUEST['year']}";

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
  class="table table-striped table-bordered table-hover" id="example" >
 <thead>
  <tr>
   <th>From</th>
   <th>To</th>
   <?php
   
   $grand_total = array();
   $skip = array('id', 'gdr_id', 'gngf', 'client_type', 
     'date_of_submission');
   $sql = "describe {$_REQUEST['table']}";
   $result = mysql_query($sql, $con) or die(mysql_error());
   $number_of_columns= mysql_num_rows($result);
   while ($row = mysql_fetch_array($result))  {
     if (in_array($row[0], $skip))
       continue;
     echo "<th>" . format_label($row[0]) . "</th>";
    }

    //Initialize Grand Totals to 0
    for ($j = 0; $j < $number_of_columns; $j++)
      $grand_total[$j] = 0;
  ?>

  </tr>
 </thead>

 <tbody id="example1">
  <?php
   $mth = array('January'=>'01', 'February'=>'02', 
    'March'=>'03', 'April'=>'04', 'May'=>'05', 'June'=>'06', 
    'July'=>'07', 'August'=>'08', 'September'=>'09', 'October'=>'10', 
    'November'=>'11', 'December'=>'12');
   foreach ($mth as $x => $y) {

     $total = array();
     echo "<tr>
            <td colspan='2'>{$x}</td>";

     for ($j = 0; $j < $number_of_columns; $j++) {
       $total[$j] = 0;

       if ($j < 5)
         continue;
       echo "<td></td>";
     }
     echo "</tr>";

     for ($k = 0; $k <= 31; $k+= 7) {
       if ($k == 21)  {
         $k1 = $k + 1;
         $k7 = $k + 10;
         $k = 31;
       } else {
         $k1 = $k + 1;
         $k7 = $k + 7;
       }

       echo "<tr>
         <td>{$k1}</td>
         <td>{$k7}</td>";

       $sql = "select * from {$_REQUEST['table']} where 
         date_of_submission between '{$_REQUEST['year']}-{$y}-{$k1}'
          and '{$_REQUEST['year']}-{$y}-{$k7}'";

       $result = mysql_query($sql, $con) or die(mysql_error());
       if (mysql_num_rows($result) > 0) {
         $row = mysql_fetch_array($result);
          
         for ($l = 0; $l < $number_of_columns; $l = $l + 1) {
           if ($l < 5)
             continue;

           $total[$l] += $row[$l];
           $grand_total[$l] += $row[$l]; 
           echo " <td>{$row[$l]}</td>";
         }
       } else {
         for ($l = 0; $l < $number_of_columns ; $l = $l + 1) {
           if ($l < 5)
             continue;
           echo " <td>0</td>";
         }
       }
       echo "</tr>";
     }     
       
     echo "
       <tr>
         <td style='text-align:center' colspan='2'>{$x} TOTALS</td>";

     for ($l = 0; $l < $number_of_columns ; $l = $l + 1) {
       if ($l < 5)
         continue;
       //echo " <td style='background-color:yellow;'>$total[$l]</td>";
       echo " <td>$total[$l]</td>";
     }
     echo "</tr>";
   } 
   echo "
     <tr>
       <td style='text-align:center' colspan='2'>GRAND TOTALS</td>";

   for ($l = 0; $l < $number_of_columns ; $l = $l + 1) {
     if ($l < 5)
       continue;
     echo " <td>$grand_total[$l]</td>";
   }
   echo "</tr>";
   ?>
  </tbody>
  <tfoot>
   <tr></tr>
  </tfoot>
</table>

<?php
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview'))
  print_footer();
else
  main_footer();
?>

