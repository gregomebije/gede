<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "menu.inc";
require_once "form.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array('pitt report1', $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'PITT Report', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

$state = get_value('state', 'name', 'id', $_REQUEST['state_id'], $con);
$lga = get_value('lga', 'name', 'id', $_REQUEST['lga_id'], $con);

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview')) {
  print_menu($con);
} else {
  main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   "PITT Report for {$state}/{$lga} for {$_REQUEST['to_date']}", 
   true, $con);
}
if (empty($_REQUEST['from_date']) || empty($_REQUEST['to_date']) 
  || empty($_REQUEST['state_id']) 
  || empty($_REQUEST['lga_id'])) {

  ?>
  <div class="container" style="margin-top: 10px">
   <div class="page-header text-center" style='margin-bottom:5px;
    margin-top:-10px;'>
    <h3 style='margin-bottom:-15px;'>PITT Report</h3>
   </div>
  <?php
     msg_box("Please specify correct parameters for PITT Report",
       "choose_pitt.php?url=pitt_report1", "Back");
     main_footer();
     exit;
  }
  ?>

<div class="container" style="margin-top: 10px">
 <div class="page-header text-center" style='margin-bottom:5px; 
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   PITT Report for 
   <?php echo "{$state}/{$lga} between {$_REQUEST['from_date']}
    and {$_REQUEST['to_date']}"; ?></h3>

   <?php
  $url ="pitt_report1.php?action=Preview";
  $url .= "&from_date={$_REQUEST['from_date']}";
  $url .= "&to_date={$_REQUEST['to_date']}";
  $url .= "&state_id={$_REQUEST['state_id']}";
  $url .= "&lga_id={$_REQUEST['lga_id']}";

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
 <table
  class="table">
  <tr>
   <td>
    <table> 

 <?php
 $target = array('Out Of School Youths','In School Youths',
   'Transport Workers','CSWs','PLWHA','Uniform Service Men/Women','MSM',
   'IDUs','Others');

 foreach($target as $index => $name) { 
   $sql = "select count(*) as 'total' from pitt where 
    state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
    and target_group='{$name}' and (date_of_compilation between 
    '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')";

   $result = mysql_query($sql, $con) or die(mysql_error());
   $row = mysql_fetch_array($result);

   echo "<tr><td>{$name}</td><td>";
   echo ($row['total'] == '0') ? "0" : $row['total'];
   echo "</td></tr>";
 }
 /* New Code */
<?php 
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
}
require_once "menu.inc";
require_once "form.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array('pitt report2', $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'PITT Report2', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

$state = get_value('state', 'name', 'id', $_REQUEST['state_id'], $con);
$lga = get_value('lga', 'name', 'id', $_REQUEST['lga_id'], $con);

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview')) {
  print_menu($con);
} else {
  main_menu($_SESSION['uid'],
   $_SESSION['firstname'] . " " . $_SESSION['lastname'],
   "PITT Report for {$state}/{$lga} for {$_REQUEST['to_date']}",
   true, $con);
}
if (empty($_REQUEST['from_date']) || empty($_REQUEST['to_date'])
  || empty($_REQUEST['state_id'])
  || empty($_REQUEST['lga_id'])) {

  ?>
  <div class="container" style="margin-top: 10px">
   <div class="page-header text-center" style='margin-bottom:5px;
    margin-top:-10px;'>
    <h3 style='margin-bottom:-15px;'>PITT Report</h3>
   </div>
  <?php
     msg_box("Please specify correct parameters for PITT Report",
       "choose_pitt.php?url=pitt_report2", "Back");
     main_footer();
     exit;
  }
  ?>

<div class="container" style="margin-top: 10px">
 <div class="page-header text-center" style='margin-bottom:5px;
   margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   PITT Report for
   <?php echo "{$state}/{$lga} between {$_REQUEST['from_date']}
    and {$_REQUEST['to_date']}"; ?></h3>

   <?php

 $url ="pitt_report2.php?action=Preview";
  $url .= "&from_date={$_REQUEST['from_date']}";
  $url .= "&to_date={$_REQUEST['to_date']}";
  $url .= "&state_id={$_REQUEST['state_id']}";
  $url .= "&lga_id={$_REQUEST['lga_id']}";

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

 <hr style='border:1px solid #664104;'>
 <?php
 $target_male = array(
    'Cohort Total'		=> 0,
    'Reached 1'			=> 0,
    'Reached 2'			=> 0,
    'Reached 3'			=> 0,
    'Out Of School Youths'      => 0,
    'In School Youths'          => 0,
    'Transport Workers'         => 0,
    'CSWs'                      => 0, 
    'PLWHA'                     => 0,
    'Uniform Service Men/Women' => 0,
    'MSM'			=> 0,
    'IDUs'			=> 0,
    'Others'			=> 0);

 $target_female = array(
    'Cohort Total'		=> 0,
    'Reached 1'			=> 0,
    'Reached 2'			=> 0,
    'Reached 3'			=> 0,
    'Out Of School Youths'      => 0,
    'In School Youths'          => 0,
    'Transport Workers'         => 0,
    'CSWs'                      => 0, 
    'PLWHA'                     => 0,
    'Uniform Service Men/Women' => 0,
    'MSM'			=> 0,
    'IDUs'			=> 0,
    'Others'			=> 0);

 foreach($target_male as $name => $total) {
   $sql = "select count(*) as 'total' from pitt where
    state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
    and target_group='{$name}' and (date_of_compilation between
    '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
    and sex='Male'";

   $result = mysql_query($sql, $con) or die(mysql_error());
   $row = mysql_fetch_array($result);

   $target_male[$name] = ($row['total'] == '0') ? "0" : $row['total'];
 }

 foreach($target_female as $name=> $total) {
   $sql = "select count(*) as 'total' from pitt where
    state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
    and target_group='{$name}' and (date_of_compilation between
    '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
    and sex='Female'";

   $result = mysql_query($sql, $con) or die(mysql_error());
   $row = mysql_fetch_array($result);

   $target_female[$name] = ($row['total'] == '0') ? "0" : $row['total'];
 }
  

 //Reached minimum of 3 interventions for males
 $sql = "select count(*) as 'total' from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and comments='Valid' and sex='Male'";
 $result = mysql_query($sql, $con) or die(mysql_error());
 $row = mysql_fetch_array($result);
 $target_male['Reached 3'] = $row['total'];

 //Reached 1 or Reached 2
 $arr = array('community_awareness', 'peer_education_model',
      'school_based_approach', 'peer_education_model_plus',
      'work_place', 'specific_population_awareness_cam', 'community_outreach',
      'infection_control_management_in_clinical_setting_interventions',
      'provision_of_STI_management_interventions',
      'vulnerability_interventions', 'strategy_reached');

 $sql = "select * from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and sex='Male'";
 $result = mysql_query($sql, $con) or die(mysql_error());

 $reached_1 = 0;
 $reached_2 = 0;
 $reached_3 = 0;
 while($row = mysql_fetch_array($result)) {  
   $min_intervention = 0;
   foreach($arr as $intervention) {
     if (!empty($row[$intervention]))
       $min_intervention += 1;
   }

   if ($min_intervention == 1) {
     $reached_1 += 1;
   } else if ($min_intervention == 2) {
     $reached_2 += 1;
   } else if ($min_intervention >= 3) {
     $reached_3 += 1;
   }
 }
 $target_male['Reached 1'] = $reached_1;
 $target_male['Reached 2'] = $reached_2;
 $target_male['Cohort Total'] = $target_male['Reached 1'] + 
   $target_male['Reached 2'] + $target_male['Reached 3']; 

 //Reached minimum of 3 interventions for females
 $sql = "select count(*) as 'total' from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and comments='Valid' and sex='Female'";
 $result = mysql_query($sql, $con) or die(mysql_error());
 $row = mysql_fetch_array($result);
 $target_female['Reached 3'] = $row['total'];

 //Reached 1 or Reached 2
 $sql = "select * from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and sex='Female'";

 $reached_1 = 0; 
 $reached_2 = 0; 
 $reached_3 = 0; 
 $result = mysql_query($sql, $con) or die(mysql_error());
 while($row = mysql_fetch_array($result)) {  
   $min_intervention = 0;
   foreach($arr as $intervention) {
     if (!empty($row[$intervention]))
       $min_intervention += 1;
   }

   if ($min_intervention == 1) {
     $reached_1 += 1;
   } else if ($min_intervention <= 2) {
     $reached_2 += 1;
   } else if ($min_intervention >= 3) {
     $reached_3 += 1;
   }
 }
 $target_female['Reached 1'] = $reached_1;
 $target_female['Reached 2'] = $reached_2;
 $target_female['Cohort Total'] = $target_female['Reached 1'] + 
   $target_female['Reached 2'] + $target_female['Reached 3']; 

?>

     <script>
	var data = [
	    { sales:"10.5", component:"dhxGrid",color:"#c7e1f3" },
		{ sales:"8.7", component:"dhxScheduler",color:"#45abf5" },
	    { sales:"6.3", component:"dhxTree",color:"#3590d0" },
		{ sales:"5.3", component:"dhxTabbar",color:"#BDDBF9" },
		{ sales:"4.5", component:"dhxLayout",color:"#d9e5ed" },
		{ sales:"4.2", component:"dhxMenu",color:"#aed7f4" }
	];
	
	window.onload = function(){
		var chart =  new dhtmlXChart({
			view:"pie3D",
			container:"chart",
			value:"#sales#",
			label:function(obj){
				var sum = chart.sum("#sales#");
				return obj.component+" ("+Math.round(parseFloat(obj.sales)/sum*100)+"%)";
			},
			color:"#color#",
			radius:110
		});
		chart.parse(data,"json");
	}

	</script>

     <script type="text/javascript">
      $(function () {

        $('#container').highcharts({
            chart: {
                type: 'column'
            },
    
            title: {
                text: 'Graphic presentation of #s reached'
            },
    
            xAxis: {
                <?php
                echo "categories: [";
                $count = 0;
                foreach($target_male as $name => $value) {
                  echo "'{$name}'";
                  $count += 1;
                  if ($count < count($target_male))
                  echo ",";
                }
                echo "]";
                ?> 
            },
    
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: '#s reached'
                }
            },
            /*
            xAxis: {
                title: {
                    text: 'STRATEGIES'
                }
            },
            */ 
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },
    
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
    
            series: [{
                name: 'Male',
                <?php
                echo "data: [";
                $count = 0;
                foreach($target_male as $name => $value) {
                  echo "{$value}";
                  $count += 1;
                  if ($count < count($target_male))
                  echo ",";
                }
                echo "],";
                ?>
                stack: 'male'
            }, {
                name: 'Female',
                <?php
                echo "data: [";
                $count = 0;
                foreach($target_female as $name => $value) {
                  echo "{$value}";
                  $count += 1;
                  if ($count < count($target_male))
                  echo ",";
                }
                echo "],";
                ?>
                stack: 'female'
            }] 
        });

        $('#container2').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Disaggregation into Target Population'
            },
    
            xAxis: {
                
                <?php
                echo "categories: [";
                $count = 0;
                foreach($target_male as $name => $value) {
                  if (($name == 'Cohort Total') || ($name == 'Reached 1') ||
                      ($name == 'Reached 2') || ($name == 'Reached 3')) {
                    continue;
                  }
                  echo "'{$name}'";
                  $count += 1;
                  if ($count < count($target_male))
                  echo ",";
                }
                echo "]";
                ?> 
            },
    
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: '#s reached'
                }
            },
            /*
            xAxis: {
                title: {
                    text: 'Target Population'
                }
            },
            */
    
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },
    
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
    
            series: [{
                name: 'Male',
                <?php
                echo "data: [";
                $count = 0;
                foreach($target_male as $name => $value) {
                  if (($name == 'Cohort Total') || ($name == 'Reached 1') ||
                      ($name == 'Reached 2') || ($name == 'Reached 3')) {
                    continue;
                  }
                  echo "{$value}";
                  $count += 1;
                  if ($count < count($target_male))
                  echo ",";
                }
                echo "],";
                ?>
                stack: 'male'
            }, {
                name: 'Female',
                <?php
                echo "data: [";
                $count = 0;

                foreach($target_female as $name => $value) {
                  if (($name == 'Cohort Total') || ($name == 'Reached 1') ||
                      ($name == 'Reached 2') || ($name == 'Reached 3')) {
                    continue;
                  }
                  echo "{$value}";
                  $count += 1;
                  if ($count < count($target_male))
                  echo ",";
                }
               echo "],";
                ?>
                stack: 'female'
            }] 
        });

        <?php
        $strategy = array('community_awareness' => 0, 
          'peer_education_model'      		=> 0,
          'school_based_approach'     		=> 0,
          'peer_education_model_plus' 		=> 0,
          'work_place' 		      		=> 0,
          'specific_population_awareness_cam'   => 0,
          'community_outreach'                  => 0,
          'infection_control_management_in_clinical_setting_interventions' 
                  => 0,
          'provision_of_STI_management_interventions' => 0,
          'vulnerability_interventions' => 0);

        $sql = "select * from pitt where
          state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
          and (date_of_compilation between
          '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')";

        $result = mysql_query($sql, $con) or die(mysql_error());
        while($row = mysql_fetch_array($result)) {  
          foreach($strategy as $intervention => $total) {
            if (!empty($row[$intervention]))
              $strategy[$intervention] += 1;
          }
        }
        ?>
        $('#container3').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Frequency distribution of activities per strategy'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Strategy',
                data: [

                <?php
                $count = 0;
                foreach ($strategy as $name => $value) {
                  echo "['{$name}', {$value}]";
                  if ($count < count($strategy))
                    echo ",";
                  $count += 1;
                }
                ?>
               
                ]
            }]
        });

        $('#container4').highcharts({
            chart: {
                type: 'column',
                 margin: [ 50, 50, 100, 80]

            },
            title: {
                text: 'Total Male/Total Female Cohort'
            },
            xAxis: {
                categories: [
                    'Total Male Cohort',
                    'Total Female Cohort'
                ],
                labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '#s Reached'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        '#s reached: '+ Highcharts.numberFormat(this.y, 1) +
                        ' ';
                }
            },
            series: [{
                name: 'Total Cohort',
                data: [<?php echo $target_male['Cohort Total']; ?>, 
                      <?php echo $target_female['Cohort Total']; ?>],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: 2,
                    y: 1,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });

        $('#container5').highcharts({
            chart: {
                type: 'column',
                 margin: [ 50, 50, 100, 80]

            },
            title: {
                text: 'Male/Female reached with 3 min'
            },
            xAxis: {
                categories: [
                    'Male reached with 3 min',
                    'Female reached with 3 min'
                ],
                labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '#s Reached'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        '#s reached: '+ Highcharts.numberFormat(this.y, 1) +
                        ' ';
                }
            },
            series: [{
                name: 'Male/Female reached with 3 min',
                data: [<?php echo $target_male['Reached 3']; ?>,
                      <?php echo $target_female['Reached 3']; ?>],

                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: 2,
                    y: 1,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });


    });
    

     </script>
    <script src="assets/js/highcharts.js"></script>
    <script src="assets/js/modules/exporting.js"></script>
    <div id="container" style="min-width: 400px; height: 400px; 
      margin: 0 auto"></div>

    <table cellpadding="0" cellspacing="0" border="0" 
       class="table table-striped table-bordered table-hover">
     <thead>
      <tr>
       <th></th>
       <?php
       foreach($target_male as $name => $value) 
          echo "<td>{$name}</td>";
       ?>
      </tr> 
     </thead>
     <tbody>
      <tr>
       <td>Male</td> 
       <?php
       foreach($target_male as $name => $value) 
          echo "<td>{$value}</td>";
       ?>
      </tr>
      <tr>
       <td>Female</td> 
       <?php
       foreach($target_female as $name => $value) 
          echo "<td>{$value}</td>";
       ?>
      </tr>
     </tbody>
     </table> 
    <br />
    <br />

    <hr style='border:1px solid #664104;'>
    <div id="container2" style="min-width: 400px; height: 400px; 
      margin: 0 auto"></div>
    <table cellpadding="0" cellspacing="0" border="0" 
       class="table table-striped table-bordered table-hover">
     <thead>
      <tr>
       <th></th>
       <?php
       foreach($target_male as $name => $value) {
         if (($name == 'Cohort Total') || ($name == 'Reached 1') ||
             ($name == 'Reached 2') || ($name == 'Reached 3')) {
           continue;
         }
         echo "<td>{$name}</td>";
       }
       ?>
      </tr> 
     </thead>
     <tbody>
      <tr>
       <td>Male</td> 
       <?php
       foreach($target_male as $name => $value) {
         if (($name == 'Cohort Total') || ($name == 'Reached 1') ||
             ($name == 'Reached 2') || ($name == 'Reached 3')) {
           continue;
         }
         echo "<td>{$value}</td>";
       }
       ?>
      </tr>
      <tr>
       <td>Female</td> 
       <?php
       foreach($target_female as $name => $value) {
         if (($name == 'Cohort Total') || ($name == 'Reached 1') ||
             ($name == 'Reached 2') || ($name == 'Reached 3')) {
           continue;
         }
          echo "<td>{$value}</td>";
       }
       ?>
      </tr>
     </tbody>
     </table> 
     <br />
     <br />
     <hr style='border:1px solid #664104;'>

     <div id="container3" style="min-width: 400px; height: 400px; 
        margin: 0 auto"></div>

     <div id="chart" 
       style="width:500px;height:300px;border:1px solid #A4BED4;"></div>

     <!--<h3>Frequency of activities per strategy</h3>-->
     <table cellpadding="0" cellspacing="0" border="0" 
       class="table table-striped table-bordered table-hover">
     <thead>
      <tr>
       <?php
       foreach($strategy as $name => $value) 
          echo "<td>{$name}</td>";
       ?>
      </tr> 
     </thead>
     <tbody>
      <tr>
       <?php
       foreach($strategy as $name => $value) 
          echo "<td>{$value}</td>";
       ?>
      </tr>
     </tbody>
     </table>  

     <br />
     <br />
     <hr style='border:1px solid #664104;'>
     <div id="container4" style="min-width: 400px; height: 400px; 
         margin: 0 auto"></div>

     <br />
     <br />
     <hr style='border:1px solid #664104;'>
     <div id="container5" style="min-width: 400px; height: 400px; 
         margin: 0 auto"></div>
<?php
//if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview'))
// print_footer();
//else
  main_footer();
?>

  $target_male = array(
    'Cohort Total'              => 0,
    'Reached 1'                 => 0,
    'Reached 2'                 => 0,
    'Reached 3'                 => 0,
    'Out Of School Youths'      => 0,
    'In School Youths'          => 0,
    'Transport Workers'         => 0,
    'CSWs'                      => 0,
    'PLWHA'                     => 0,
    'Uniform Service Men/Women' => 0,
    'MSM'                       => 0,
    'IDUs'                      => 0,
    'Others'                    => 0);

 $target_female = array(
    'Cohort Total'              => 0,
    'Reached 1'                 => 0,
    'Reached 2'                 => 0,
    'Reached 3'                 => 0,
    'Out Of School Youths'      => 0,
    'In School Youths'          => 0,
    'Transport Workers'         => 0,
    'CSWs'                      => 0,
    'PLWHA'                     => 0,
    'Uniform Service Men/Women' => 0,
    'MSM'                       => 0,
    'IDUs'                      => 0,
    'Others'                    => 0);

  foreach($target_male as $name => $total) {
   $sql = "select count(*) as 'total' from pitt where
    state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
    and target_group='{$name}' and (date_of_compilation between
    '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
    and sex='Male'";

   $result = mysql_query($sql, $con) or die(mysql_error());
   $row = mysql_fetch_array($result);

   $target_male[$name] = ($row['total'] == '0') ? "0" : $row['total'];
 }

 foreach($target_female as $name=> $total) {
   $sql = "select count(*) as 'total' from pitt where
    state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
    and target_group='{$name}' and (date_of_compilation between
    '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
    and sex='Female'";

   $result = mysql_query($sql, $con) or die(mysql_error());
   $row = mysql_fetch_array($result);

   $target_female[$name] = ($row['total'] == '0') ? "0" : $row['total'];
 }

 //Reached minimum of 3 interventions for males
 $sql = "select count(*) as 'total' from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and comments='Valid' and sex='Male'";
 $result = mysql_query($sql, $con) or die(mysql_error());
 $row = mysql_fetch_array($result);
 $target_male['Reached 3'] = $row['total'];

 //Reached 1 or Reached 2
 $arr = array('community_awareness', 'peer_education_model',
      'school_based_approach', 'peer_education_model_plus',
      'work_place', 'specific_population_awareness_cam', 'community_outreach',
      'infection_control_management_in_clinical_setting_interventions',
      'provision_of_STI_management_interventions',
      'vulnerability_interventions', 'strategy_reached');

 $sql = "select * from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and sex='Male'";
 $result = mysql_query($sql, $con) or die(mysql_error());

 $reached_1 = 0;
 $reached_2 = 0;
 $reached_3 = 0;

 while($row = mysql_fetch_array($result)) {
   $min_intervention = 0;
   foreach($arr as $intervention) {
     if (!empty($row[$intervention]))
       $min_intervention += 1;
   }

   if ($min_intervention == 1) {
     $reached_1 += 1;
   } else if ($min_intervention == 2) {
     $reached_2 += 1;
   } else if ($min_intervention >= 3) {
     $reached_3 += 1;
   }
 }
 $target_male['Reached 1'] = $reached_1;
 $target_male['Reached 2'] = $reached_2;
 $target_male['Cohort Total'] = $target_male['Reached 1'] +
   $target_male['Reached 2'] + $target_male['Reached 3'];

 //Reached minimum of 3 interventions for females
 $sql = "select count(*) as 'total' from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and comments='Valid' and sex='Female'";
 $result = mysql_query($sql, $con) or die(mysql_error());
 $row = mysql_fetch_array($result);
 $target_female['Reached 3'] = $row['total'];

 //Reached 1 or Reached 2
 $sql = "select * from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and sex='Female'";

 $reached_1 = 0;
 $reached_2 = 0;
 $reached_3 = 0;
 
  $result = mysql_query($sql, $con) or die(mysql_error());
 while($row = mysql_fetch_array($result)) {
   $min_intervention = 0;
   foreach($arr as $intervention) {
     if (!empty($row[$intervention]))
       $min_intervention += 1;
   }

   if ($min_intervention == 1) {
     $reached_1 += 1;
   } else if ($min_intervention <= 2) {
     $reached_2 += 1;
   } else if ($min_intervention >= 3) {
     $reached_3 += 1;
   }
 }
 $target_female['Reached 1'] = $reached_1;
 $target_female['Reached 2'] = $reached_2;
 $target_female['Cohort Total'] = $target_female['Reached 1'] +
 $target_female['Reached 2'] + $target_female['Reached 3'];
 
 ?>
   </table>
  </td>
  <td style='vertical-align:top;'>
   <table>
    <tr>
     <td>Total minimum reached using minimum of 3 interventions</td>
     <td>

     <?php 
      echo ($target_male['Reached 3'] + $target_female['Reached 3']);
      
      /*
      $sql = "select count(*) as 'total' from pitt where
       state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
       and (date_of_compilation between
       '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
       and comments='Valid'";
      $result = mysql_query($sql, $con) or die(mysql_error());
      $row = mysql_fetch_array($result);
      echo $row['total'];
      */
    ?>

     </td>
    </tr>

    <tr>
     <td>Total minimum reached using minimum of 2 interventions</td>
     <td>
      <?php echo ($target_male['Reached 2'] + $target_female['Reached 2']); ?>
     </td>
    </tr>

    <tr>
     <td>Total minimum reached using minimum of 1 intervention</td>
     <td>
      <?php echo ($target_male['Reached 1'] + $target_female['Reached 1']); ?>
     </td>
    </tr>

    <tr>
     <td>Male reached with minimum of 3 interventions</td>
     <td>
     <?php 
       echo $target_male['Reached 3'];
      /*
      $sql = "select count(*) as 'total' from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and comments='Valid' and sex='Male'";
      $result = mysql_query($sql, $con) or die(mysql_error());
      $row = mysql_fetch_array($result);
      echo $row['total'];
      */
     ?>
     </td>
    </tr>

    <tr>
     <td>Female reached with minimum of 3 interventions</td>
     <td>
     <?php 
       echo $target_female['Reached 3'];
      /*
      $sql = "select count(*) as 'total' from pitt where
        state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
        and (date_of_compilation between
        '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')
        and comments='Valid' and sex='Female'";
      $result = mysql_query($sql, $con) or die(mysql_error());
      $row = mysql_fetch_array($result);
      echo $row['total'];
      */
     ?>
     </td>
    </tr>

    <tr>
     <td>Male reached with 2:</td>
     <td>
      <?php echo $target_male['Reached 2']; ?>
     </td>
    </tr>

    <tr>
     <td>Female reached with 2:</td>
     <td>
      <?php echo $target_female['Reached 2']; ?>
     </td>
    </tr>

    <tr>
     <td>Male reached with 1:</td>
     <td>
      <?php echo $target_male['Reached 1']; ?>
     </td>
    </tr>

    <tr>
     <td>Female reached with 1:</td>
     <td>
      <?php echo $target_female['Reached 1']; ?>
     </td>
    </tr>
   </table>
  </td>
  <td style='vertical-align:top;'>
   <table>

    <tr>
     <td>Total Male in Cohort</td>
     <td><?php echo $target_male['Cohort Total']; ?></td>
    </tr>

    <tr>
     <td>Total Female in Cohort</td>
     <td><?php echo $target_female['Cohort Total']; ?></td>
    </tr>

   </table>
  </td>
 </tr> 
</table>
 
 <table cellpadding="0" cellspacing="0" border="0" 
  class="table table-striped table-bordered table-hover">
  <thead>
   <tr>
   <?php
    $result = mysql_query("describe pitt", $con) or die(mysql_error());
    while($row = mysql_fetch_array($result)) {
      if ($row[0] == 'id')
        continue;
      $col[] = $row[0];
      if (strpos($row[0], "_id") !== false) {
        $temp = substr($row[0], 0, stripos($row[0], "_id")); 
        $temp = ucwords(strtolower(str_replace('_', ' ', $temp)));
        echo "<th>{$temp}</th>";
      }
      else {
        echo "<th>" . ucwords(str_replace('_', '&nbsp;', $row[0])) . "</th>";
      }
    }
   ?>
   </tr>
  </thead> 
  <tbody id="example1">
  <?php
   $sql = "select * from pitt where 
    state_id='{$_REQUEST['state_id']}' and lga_id='{$_REQUEST['lga_id']}'
    and (date_of_compilation between 
    '{$_REQUEST['from_date']}' and '{$_REQUEST['to_date']}')";

   $result = mysql_query($sql, $con) or die(mysql_error());
   while($row = mysql_fetch_array($result)) {
     echo "<tr>";
     foreach($col as $col_name) {
       echo "<td>";
       if (($col_name == 'lga_id') || ($col_name ==  'state_id')) {
         echo get_value(str_replace('_id', '', $col_name), 'name', 'id', 
           $row[$col_name], $con);
       } else 
         echo "$row[$col_name]";
       echo "</td>";
     }
     echo "</tr>";
   } 
  ?>
  </tbody>
 </table>

<?php 
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Preview'))
  print_footer();
else
  main_footer();
?>
