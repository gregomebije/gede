<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "menu.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array('consultation report', $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'Consultation Report', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview')) {
  print_menu($con);
} else {
  main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   "Consultation Report for {$_REQUEST['year']}", 
   true, $con);
}

if (empty($_REQUEST['year'])) {
  ?> 
  <div class="container" style="margin-top: 10px">
   <div class="page-header text-center" style='margin-bottom:5px;
    margin-top:-10px;'>
    <h3 style='margin-bottom:-15px;'>Consultation Report</h3>
   </div>
  <?php
     msg_box("Please specify correct parameters for Consultation Report", 
       "choose_consultation_year.php", "Back");
     main_footer();
     exit;
   }
?>

<div class="container" style="margin-top: 10px">
 <div class="page-header text-center" style='margin-bottom:5px; 
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   Client Consultation Report for <?php echo $_REQUEST['year']; ?></h3>

   <?php
  $url ="consultation_report.php?action=Preview";
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

 <table>
  <tr>
   <td style='vertical-align:top;'>

 <table cellpadding="0" cellspacing="0" border="0" 
  class="table table-striped table-bordered table-hover" id="example">
 <thead>
  <tr>
   <th>Name</th>
   <th>GDR</th>
   <th>CRU</th>
   <!--<th>GNGF</th>-->
   <th>Sex</th>
   <th>Age</th>

  <?php

   $total_clients = array(); 
   $total_frequency = array(); 
   $total_new_clients = array();

   $mth = array('January'=>'01', 'February'=>'02', 
    'March'=>'03', 'April'=>'04', 'May'=>'05', 'June'=>'06', 
    'July'=>'07', 'August'=>'08', 'September'=>'09', 'October'=>'10', 
    'November'=>'11', 'December'=>'12');

   foreach ($mth as $x => $y)  {
     echo "<th>{$x}</th><th>Frequency</th>";
     //$total_clients[substr($x, 0, 3)] = array(0, 0);

     $total_clients[substr($x, 0, 3)] = 0;
     $total_frequency[substr($x, 0, 3)] = 0;
     $total_new_clients[substr($x, 0, 3)] = 0;
   }
  ?>
   <th>Total # of visits</th>
   <th>Equivalent # of Regular Client</th>
  </tr>
 <thead>

 <tbody id="example1">
 <?php
 $total_regular_client = 0;
 $total_male = 0; $total_female = 0;
 $under_15 = 0; $between_15_39 = 0; $above_39 = 0;  

 $sql = "select gdr.id, name, gdr, cru, sex, age 
    from gdr join (nurse_m_and_e, gdr_cru) 
    on (gdr.id = nurse_m_and_e.gdr_id and gdr.id = gdr_cru.gdr_id)
    group by gdr.id";
 $result = mysql_query($sql, $con) or die(mysql_error());
 while($row = mysql_fetch_array($result)) { 
   $total_visits = 0;

   if ($row['sex'] == 'F')
     $total_female += 1;
   else
     $total_male += 1;

   if ($row['age'] < 15)
     $under_15 += 1;
   else if (($row['age'] > 14) && ($row['age'] < 40))
     $between_15_39 += 1;
   else if ($row['age'] > 39) 
     $above_39 += 1;

    echo "
     <tr>
      <td>{$row['name']}</td>
      <td>{$row['gdr']}</td>
      <td>{$row['cru']}</td>
      <td>{$row['sex']}</td>
      <td>{$row['age']}</td>";

   foreach ($mth as $x => $y) {
   

     $sql = "select count(*) as frequency from nurse_m_and_e 
       where gdr_id='{$row['id']}' and date_of_visit between 
         '{$_REQUEST['year']}-{$y}-01' and '{$_REQUEST['year']}-{$y}-31'";

     $result2 = mysql_query($sql, $con) or die(mysql_error());
     $row2 = mysql_fetch_array($result2);
     if ($row2['frequency'] > 0) {
       echo "<td>Yes</td><td>{$row2['frequency']}</td>";
       $total_visits += 1;
       $total_clients[substr($x, 0, 3)] += 1;
       $total_frequency[substr($x, 0, 3)] += $row2['frequency']; 
     } else
       echo "<td>No</td><td>";

   }
 
   echo "<td>{$total_visits}</td><td>";
   if ($total_visits < 3) 
     echo "0";
   else if ($total_visits > 3) {
     $total_regular_client += 1;
     echo "1";
   }
   //echo ($total_vists < 3) ? 0 : 1;
   echo "</td>
     </tr>"; 
 }
 ?>
   </tbody>
  </table>
 </td>

 <td>
  <table cellpadding="0" cellspacing="0" border="0" 
   class="table table-striped table-bordered table-hover" id="example"
   style="width:30em;">
   <tbody>
    <?php
    $total_c = 0;
    $total_f = 0;
    foreach ($total_clients as $x => $y) {
      echo "<tr><td><b>Total # of Clients ({$x})</b></td><td>{$y}</td></tr>"; 	
      echo "<tr><td>{$x} Frequency</td><td>{$total_frequency[$x]}</td></tr>";
      $total_c += $y;
      $total_f += $total_frequency[$x];
    }
    ?>
    <tr><td></td><td></td></tr>
    <tr>
     <td><b>Total # of clients (YEAR)</b></td>
     <td><?php echo $total_c;?></td>
    </tr>
    <tr><td></td><td></td></tr>
    <tr>
     <td><b>Frequency of Visitations (YEAR)</b></td>
     <td><?php echo $total_f; ?></td>
    </tr>
    <tr><td></td><td></td></tr>
    <tr>
     <td><b>Total # of Male Clients</b></td>
     <td><?php echo $total_male; ?></td>
    </tr>
    <tr>
     <td><b>Total # of Female Clients</b></td>
     <td><?php echo $total_female; ?></td>
    </tr>
    <tr><td></td><td></td></tr>
    <tr>
     <td><b>Age under 15</b></td>
     <td><?php echo $under_15; ?></td>
    </tr>
    <tr>
     <td><b>Age 15 - 39</b></td>
     <td><?php echo $between_15_39; ?></td>
    </tr>
    <tr>
     <td><b>Age above 39</b></td>
     <td><?php echo $above_39; ?></td>
    </tr>
    <tr><td></td><td></td></tr>

    <?php
      $total_new_clients = 0;
      foreach ($mth as $x => $y) {
        $sql = "select * from nurse_m_and_e join
          gdr on nurse_m_and_e.gdr_id = gdr.id
          where  gdr.date_of_first_visit between
          '{$_REQUEST['year']}-{$y}-01' and '{$_REQUEST['year']}-{$y}-31'
          group by gdr.id";
   
        $result = mysql_query($sql, $con) or die(mysql_error());
        //$row = mysql_fetch_array($result);
        echo  "<tr><td>New Clients {$x}</td>";

        $columns = mysql_num_rows($result);

        if ($columns > 0) {
          echo "<td>{$columns}</td>";
          $total_new_clients += $columns;
        } else
          echo "<td>0</td>";
      }
    ?>
    <tr><td></td><td></td></tr>
    <tr>
     <td>Total # of New Clients</td>
     <td><?php echo $total_new_clients; ?></td>
    </tr>
    <tr>
     <td><b>Total # of Actual Clients (YEAR)</b></td>
     <td><?php echo $total_male + $total_female; ?></td>
    </tr>
    <tr>
     <td><b>Total # of Regular Clients (YEAR)</b></td>
     <td><?php echo $total_regular_client; ?></td>
    </tr>
    <tr>
     <td><b>Total # of Occassional Clients (YEAR)</b></td>
     <td>
      <?php echo ($total_male + $total_female) - $total_regular_client; ?></td>
    </tr>
   </tbody>
  </table>
 </td>
</tr>
</table>
<?php 
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview'))
  print_footer();
else
  main_footer();
?>
