<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "menu.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array('clinical monthly', $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'Clinical Monthly', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview')) {
  print_menu($con);
} else {
  main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   "Clinical Monthly Report by {$_REQUEST['type']} for {$_REQUEST['year']}", 
   true, $con);
}

$html_header = "<div class=\"container\" style=\"margin-top: 10px\">
     <div class=\"page-header text-center\" style='margin-bottom:5px;
      margin-top:-10px;'>
      <h3 style='margin-bottom:-15px;'> Clinical Monthly Report </h3>
     </div>";

list($year, $month, $day) = explode("-", "{$_REQUEST['year']}-01-01");
if (!checkdate($month, $day, $year)) {
  echo $html_header;
  msg_box("Please specify the year in the correct format YYYY-DD-MM", 
        "choose_clinical_monthly_report.php", "Back");
  main_footer();
  exit;
} 

if (empty($_REQUEST['type'])) {
  echo $html_header;

  msg_box("Please specify correct parameters for Clinical Monthly
      Report", "choose_clinical_monthly_report.php", "Back");
     main_footer();
     exit;
  }
?>

  <div class="container" style="margin-top: 10px">
   <div class="page-header text-center" style='margin-bottom:5px;
    margin-top:-10px;'>
    <h3 style='margin-bottom:-15px;'> Clinical Monthly Report

 by 
 <?php echo format_label($_REQUEST['type']) . 
     " for {$_REQUEST['year']}"; ?></h3>

<?php 
  $url ="clinical_monthly_report.php?action=Preview&type={$_REQUEST['type']}";
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

 <?php 
 $colspan = ($_REQUEST['type'] == 'age') ? "4" : "3";

 echo "
  <tr>
   <th>From</th>
   <th>To</th>
   <th colspan='{$colspan}'>Clients Seen By Nurse</th>
   <th colspan='{$colspan}'>New Clients In Care</th>
   <th colspan='{$colspan}'>New Clients Under Treatment</th>
   <th colspan='{$colspan}'>Clients Under Treatment</th>
   <th colspan='{$colspan}'>Clients reffered for testing by Counselor</th>
   <th colspan='{$colspan}'>Clients reffered to Counselor</th>
   <th colspan='{$colspan}'>Clients attending adherence sessions</th>
 </tr>

 <thead>
  <tr>
   <th></th>
   <th></th> ";

   if ($_REQUEST['type'] == 'age') {
     for ($i = 0; $i < 7; $i++) { 
       echo "
         <th>Below 15</th>
         <th>15-39</th>
         <th>Above 39</th>
         <th>Total</th>
       ";
     }
   } else {
     for ($i = 0; $i < 7; $i++) { 
       echo "
         <th>Male</th>
         <th>Female</th>
         <th>Total</th>
       ";
     }
   }
   ?>
  </tr>
 </thead>

 <tbody id="example1">
  <?php
   $monthly_total = array();

  
   $cols = array('client_seen_by_nurse', 'new_client_in_care', 
        'new_client_under_treatment', 'client_under_treatment');

   $filter = array('clients_seen_by_nurse', "new_client_in_care='Yes'", 
        "new_client_under_treatment='Yes'", "client_under_treatment='Yes'");

   $mth = array('January'=>'01', 'February'=>'02', 
    'March'=>'03', 'April'=>'04', 'May'=>'05', 'June'=>'06', 
    'July'=>'07', 'August'=>'08', 'September'=>'09', 'October'=>'10', 
    'November'=>'11', 'December'=>'12');
   foreach ($mth as $x => $y) {

     if ($_REQUEST['type'] == 'age') {
       foreach ($filter as $i => $j) {
         
         $monthly_total[$x] = array($j => array(
                  'below_15'  =>0, 
                  'between_15_39'     => 0,
                  'above_39'  => 0,
                  'total'     => 0));
       }
     } else {
       foreach ($filter as $i => $j) {
         $monthly_total[$x] = array($j => array(
           'male'  => 0, 'female' => 0, 'total' => 0));
       }
     }

     echo "<tr>
       <td colspan='2'><b>{$x}</b></td>";

     if ($_REQUEST['type'] == 'age') {
       for ($i = 0; $i < 7; $i++) { 
         echo "
          <td></td>
          <td></td>
          <td></td>
          <td></td>
         ";
       }
     } else {
       for ($i = 0; $i < 7; $i++) { 
         echo "
          <td></td>
          <td></td>
          <td></td>
         ";
       }
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
        <td>" . substr($x, 0, 3) . " {$k1}</td>
        <td>" . substr($x, 0, 3) . " {$k7}</td>";


       foreach ($filter as $criteria) { 
         if ($_REQUEST['type'] == 'age') {
           $arr = array('below_15' =>'g.age < 15', 
                  'between_15_39'     => 'g.age >= 15 and g.age <= 39',
                  'above_39'   => 'g.age > 39');
         } else {
           $arr = array('male'  => "g.sex = 'M'",
                        'female' => "g.sex = 'F'");
         }
         $total = 0;
         foreach($arr as $name => $value) {
           $sql = "select count(*) as '{$name}'  from nurse_m_and_e n
             join gdr g on (n.gdr_id = g.id) where 
             n.date_of_visit between '{$_REQUEST['year']}-{$y}-{$k1}'
             and '{$_REQUEST['year']}-{$y}-{$k7}'
             and ($value) ";
                
           //if (!empty($criteria))
           if ($criteria != 'clients_seen_by_nurse')
             $sql .= " and {$criteria}";

           $result = mysql_query($sql, $con) or die(mysql_error());
           if (mysql_num_rows($result) > 0) {
             $row = mysql_fetch_array($result);
             $total += $row[$name];

             $monthly_total[$x][$criteria][$name] += $row[$name];

             echo " <td>{$row[$name]}</td>";
           } else {
             echo " <td>0</td>";
           }
         }
         echo "<td>{$total}</td>";
         $monthly_total[$x][$criteria]['total'] += $total;
       }
       echo "</tr>";
     }

     //Totals for the Month
     echo "<tr>
       <td colspan='2'><b>{$x} TOTALS</b></td>";

     foreach ($filter as $criteria) { 
       if ($_REQUEST['type'] == 'age') {
         echo "
          <td><b>{$monthly_total[$x][$criteria]['below_15']}</b></td>
          <td><b>{$monthly_total[$x][$criteria]['between_15_39']}</b></td>
          <td><b>{$monthly_total[$x][$criteria]['above_39']}</b></td>
          <td><b>{$monthly_total[$x][$criteria]['total']}</b></td>
         ";
       } else {
         echo "
          <td><b>{$monthly_total[$x][$criteria]['male']}</b></td>
          <td><b>{$monthly_total[$x][$criteria]['female']}</b></td>
          <td><b>{$monthly_total[$x][$criteria]['total']}</b></td>
         ";
       }
     }
     echo "</tr>";
   } 
   //print_r($monthly_total);
   ?>
   <tr>
    <th colspan='2'><b>Grand Total</b></th>
    <?php  
     foreach ($filter as $criteria) { 
       if ($_REQUEST['type'] == 'age') {
         $below_15 = 0; $between_15_13 = 0; $above_39 = 0; $total = 0;
         foreach($monthly_total as $mth => $totals) { 
           $below_15 += $monthly_total[$mth][$criteria]['below_15'];
           $between_15_13 += $monthly_total[$mth][$criteria]['between_15_39'];
           $above_39 += $monthly_total[$mth][$criteria]['above_39'];
           $total += $monthly_total[$mth][$criteria]['total'];
         }
         echo "
          <td><b>{$below_15}</b></td>
          <td><b>{$between_15_13}</b></td>
          <td><b>{$above_39}</b></td>
          <td><b>{$total}</b></td>
         ";
       } else {
         $male = 0; $female = 0; $total = 0;
         foreach($monthly_total as $mth => $totals) { 
           $male += $monthly_total[$mth][$criteria]['male'];
           $female += $monthly_total[$mth][$criteria]['female'];
           $total += $monthly_total[$mth][$criteria]['total'];
         }
         echo "
          <td><b>{$male}</b></td>
          <td><b>{$female}</b></td>
          <td><b>{$total}</b></td>
         ";
       }
     }
    ?>
    </tr>
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
