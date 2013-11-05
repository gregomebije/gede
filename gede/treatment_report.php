<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "menu.inc";

$con = connect();
main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   "Client Treatment Report by {$_REQUEST['type']} for {$_REQUEST['year']}", 
   true, $con);


if (empty($_REQUEST['year'])) {
  ?>
  <div class="container" style="margin-top: 10px">
   <div class="page-header text-center" style='margin-bottom:5px;
    margin-top:-10px;'>
    <h3 style='margin-bottom:-15px;'>Client Treatment Report</h3>
   </div>
  <?php
  msg_box("Please specify correct parameters for Client Treatment Report",
    "choose_treatment_year.php", "Back");
  main_footer();
  exit;
}
?>
<div class="container" style="margin-top: 10px">
 <div class="page-header text-center" style='margin-bottom:5px; 
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   Client Treatment Report for <?php echo $_REQUEST['year']; ?></h3>
 </div>

 <table>
  <tr>
   <td style='vertical-align:top;'>

 <table cellpadding="0" cellspacing="0" border="0" 
  class="table table-striped table-bordered table-hover" id="example">

 <tr><td colspan='19' style='text-align:center;'>
  <h4>DATE OF VISIT</h4></td></tr>

 <thead>
  <tr>
   <th>Name</th>
   <th>GDR</th>
   <th>CRU</th>
   <th>GNGF</th>
   <th>Sex</th>
   <th>Age</th>
   <th>Occupation</th>
  

  <?php
   $total_clients = array(); 
   $total_frequency = array();

   $mth = array('January'=>'01', 'February'=>'02', 
    'March'=>'03', 'April'=>'04', 'May'=>'05', 'June'=>'06', 
    'July'=>'07', 'August'=>'08', 'September'=>'09', 'October'=>'10', 
    'November'=>'11', 'December'=>'12');

   foreach ($mth as $x => $y)  {
     echo "<th>{$x}</th>";

     $total_clients[substr($x, 0, 3)] = 0;
     $total_frequency[substr($x, 0, 3)] = 0;
   }
  ?>
   <th>Total</th>
   <th>Equivalent</th>
  </tr>
 <thead>

 <tbody id="example1">
 <?php
 $sql = "select gdr.id, name, gdr, cru, gngf, sex, age 
    from gdr join nurse_m_and_e on (gdr.id = nurse_m_and_e.gdr_id)
    group by gdr.id";
 $result = mysql_query($sql, $con) or die(mysql_error());
 while($row = mysql_fetch_array($result)) { 
   $total_visits = 0;

    echo "
     <tr>
      <td>{$row['name']}</td>
      <td>{$row['gdr']}</td>
      <td>{$row['cru']}</td>
      <td>{$row['gngf']}</td>
      <td>{$row['sex']}</td>
      <td>{$row['age']}</td>
      <th>{$row['occupation']}</th>";

   foreach ($mth as $x => $y) {

     $sql = "select count(*) as 'frequency' from nurse_m_and_e 
       where gdr_id='{$row['id']}' and date_of_visit between 
         '{$_REQUEST['year']}-{$y}-01' and '{$_REQUEST['year']}-{$y}-31'";

     $result2 = mysql_query($sql, $con) or die(mysql_error());
     $row2 = mysql_fetch_array($result2);
     if ($row2['frequency'] > 0) {
       echo "<td>{$row2['frequency']}</td>";
       $total_visits += 1;
       $total_clients[substr($x, 0, 3)] += 1;
       $total_frequency[substr($x, 0, 3)] += $row2['frequency']; 
     } else
       echo "<td>0</td>";

   }
 
   echo "<td>{$total_visits}</td><td>";
   echo ($total_vists < 3) ? 0 : 1;
   echo "</td>
     </tr>"; 
 }
 ?>
   </tbody>
  </table>
 </td>

 <td style='vertical-align:top;'>
  <table cellpadding="0" cellspacing="0" border="0" 
   class="table table-striped table-bordered table-hover" id="example"
   style="width:20em;">
   <tbody>
    <tr>
     <td>Total # of Clients on Treatment</td>
     <td>WIP</td>
    </tr>
    <?php
    $total_c = 0;
    //$total_f = 0;
    foreach ($total_clients as $x => $y) {
      //echo "<tr><td>{$x} Frequency</td><td>{$total_frequency[$x]}</td></tr>";
      echo "<tr><td>Total # of Clients ({$x})</td><td>{$y}</td></tr>"; 	
      $total_c += $y;
      //$total_f += $total_frequency[$x];
    }
    ?>
    <tr>
     <td>Total # of Clients given drugs</td>
     <td>WIP</td>
    </tr>
    <tr>
     <td>Total # of Clients visit for drugs</td>
     <td>WIP</td>
    </tr>
   </tbody>
  </table>
 </td>
</tr>
</table>
<?php main_footer(); ?>
