<?php 
session_start();

if (!isset($_SESSION['uid'])) {
    header('Location: index.php');
    exit;
}
error_reporting(E_ALL);

require_once "form.inc";
require_once "menu.inc";

$con = connect(); 

ini_set('upload_max_filesize', '20M');

//echo "<h1>" . ini_get('upload_max_filesize') . "</h1>";

if (isset($_REQUEST['action']) && 
  (($_REQUEST['action'] == 'Backup') || ($_REQUEST['action'] == 'Restore'))) {
  if (empty($_REQUEST['file'])) {
     
    main_menu($_SESSION['uid'], 
      $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
      'Backup/Restore', true, $con);

    echo '
     <div class="container" style="margin-top: -15px; background-color:white;">
      <div class="page-header text-center" style="margin-bottom:5px;
       margin-top:-10px;">
       <h3 style="margin-bottom:-15px;"> Backup And Restore</h3>
      </div>';

    msg_box('Please enter the file to backup or restore from', 
     'backup_restore.php', 'Back To Backup/Restore');
    
    main_footer();
    exit;
  }
  
  $filename = "backup.sql";  

  if ($_REQUEST['action'] == 'Restore') {
    if(empty($_FILES['file']['name'])) {

      main_menu($_SESSION['uid'], 
      $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
      'Backup/Restore', true,  $con);

    echo '
     <div class="container" style="margin-top: -15px; background-color:white;">
      <div class="page-header text-center" style="margin-bottom:5px;
       margin-top:-10px;">
       <h3 style="margin-bottom:-15px;"> Backup And Restore</h3>
      </div>';

	  msg_box('Please enter the file to backup or restore from', 
       'backup_restore.php', 'Back To Backup/Restore');

       main_footer();
      exit;
    }
    $dir = '.'; 
    $url="backup_restore.php";
    if (!empty($_FILES['file']['name'])) {
      if ($_FILES['file']['error'] != 4) {  
        //Lets upload the file
        if ($_FILES['file']['error'] > 0) {
          switch($_FILES['file']['error']) {
            case 1: msg_box('File exceeded upload max_filesize', 
              $url, 'OK'); break;
            case 2: msg_box('File exceeded max_file_size', 
              $url, 'OK'); break;
            case 3: msg_box('File only partially uploaded', 
              $url, 'OK'); break;
          }
          exit;
        } else {
	      $upfile = $_FILES['file']['name'];
          if(is_uploaded_file($_FILES['file']['tmp_name'])) {
            if(!move_uploaded_file($_FILES['file']['tmp_name'], $upfile)) {
              msg_box('Problem: Could not move file to destination
                 directory', $url, 'OK');
              exit;
            }
          } else {
            msg_box("Problem: Possible file upload attack. Filename: " .
              $_FILES['file']['name'], $url, 'OK');
            exit;
          } 
        } 
      }
    }

    $file_handle = fopen("{$_FILES['file']['name']}", "r");
    $un = "";

    while (!feof($file_handle)) {
      $line = fgets($file_handle);
      $end_i = substr($line, -3, 2); 
      $end_t = substr($line, -2, 1);
      $start_i = substr($line, 0, 6);
      $start_t = substr($line, 0, 8);
      if ((($start_i == 'INSERT') && ($end_i == ");")) 
	  || (($start_t == 'TRUNCATE') &&($end_t == ";"))) { 
	    //echo "Executing $line <br>End is $end_i or $end_t<br>";
          ; //Do nothing
      } 
      $result = mysql_query($line, $con);
      if (!$result) {
        $un = $un . $line;
        $endx = substr($un, -3, 2);
        //echo "Error Executing: $line<br>End is $endx<br><br>";		
        if($endx == ");") {
          //echo "Complete line $un <br><br>";
	  mysql_query($un, $con);
	 //echo "Executed completed line $un<br>";
	 $un = "";
        }
      }
    }
    fclose($file_handle);

	//Authentication and display of Menu
 main_menu($_SESSION['uid'], 
      $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   'Backup/Restore', true, $con);

    echo '
     <div class="container" style="margin-top: -15px; background-color:white;">
      <div class="page-header text-center" style="margin-bottom:5px;
       margin-top:-10px;">
       <h3 style="margin-bottom:-15px;"> Backup And Restore</h3>
      </div>';
	  
	  //Delete backup file after updating database
	  unlink($_FILES['file']['name']);
	  msg_box("Database has been restored from {$_FILES['file']['name']}", 'backup_restore.php', 'Continue');
          main_footer();
	  
  } else if ($_REQUEST['action'] == 'Backup') {
    if (file_exists($filename)) {
      unlink($filename);
    }
    $fp = fopen($filename, "w+");
    $sql = "";
	$result = mysql_query("show tables", $con) or die(mysql_error());
    //$result = mysql_list_tables($database, $con);
    for ($i = 0; $i < mysql_num_rows($result); $i++) {
      $table_name = mysql_tablename($result, $i);
      $result2 = mysql_query("select * from $table_name", $con);
      if (!$result2) 
        die('Query failed: ' . mysql_error());
      $sql ="TRUNCATE table $table_name;\n";
      //echo "<br>";

      $num_rows = mysql_num_rows($result2);
      if ($num_rows == 0) 
        continue;
      else {
        while($row = mysql_fetch_row($result2)) {
          $x = mysql_num_fields($result2);
	  $sql .="INSERT INTO $table_name(";
	  for($j = 0; $j < $x; $j++) {
	    if ($j == ($x - 1)) 
	      $sql .= mysql_field_name($result2, $j);
	    else
	      $sql .= mysql_field_name($result2, $j) . ", ";
	  }
	  $sql .= ") values (";
	  for($k = 0; $k < $x; $k++) {
	    if ($k == ($x - 1))
              if (mysql_field_type($result2, $k) == 'int') 
	        $sql .= htmlspecialchars($row[$k], ENT_QUOTES);
	      else 
	        $sql .= "'" . htmlspecialchars($row[$k], ENT_QUOTES) . "'";
		else {
		  if (mysql_field_type($result2, $k) == 'int') 
		    $sql .= htmlspecialchars($row[$k], ENT_QUOTES) . ", ";
		  else 
		  $sql .= " '" . htmlspecialchars($row[$k], ENT_QUOTES) . "', ";
	        }
	  }
	  $sql .= ");";
	  fwrite($fp, "$sql\n");
          //echo "$sql<br>";
          $sql="";
        }
      }
    }
    mysql_free_result($result);
    fclose($fp);

    header("Content-disposition:attachement; filename={$_REQUEST['file']}");
    header('Content-type: application/x-download; charset:iso-8859-1');
    header('Content-length: ' . filesize($filename)); 
    readfile($filename);
    
	//Delete backup file after backup has been made
	unlink($filename);
  }
  exit;
}
 $filename = "backup_" . date('Y_m_d_H_i') . ".sql";

$user_perm = get_user_perm($_SESSION['uid'], $con);
	
//Check the permission
if (!(in_array('backup restore', $user_perm) 
   || in_array('administrator', $user_perm))) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " 
     . $_SESSION['lastname'], 'Backup/Restore', true, $con);

    echo '
     <div class="container" style="margin-top: -15px;">
      <div class="page-header text-center" style="margin-bottom:5px;
       margin-top:-10px;">
       <h3 style="margin-bottom:-15px;"> Backup And Restore</h3>
      </div>';

  msg_box('Access Denied!', 'index.php?action=logout', 'Continue');
  main_footer();

  exit;
} else {
    main_menu($_SESSION['uid'], 
      $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
      'Backup/Restore', true, $con);

    echo '
     <div class="container" style="margin-top: -15px; background-color:white;">
      <div class="page-header text-center" style="margin-bottom:5px;
       margin-top:-10px;">
       <h3 style="margin-bottom:-15px;"> Backup And Restore</h3>
      </div>';
}
?>
 <table>
     <form action="backup_restore.php" method="post" 
       enctype='multipart/form-data'>
   <tr>
    <td>
     <fieldset>
      <legend>Backup</legend>
       <table>
        <tr>
         <td>Filename</td>
         <td><input type='text' name='file' 
          value='<?php echo $filename; ?>' size='30'></td>
         <td><input type="submit" name="action" value="Backup"></td>
        </tr>
       </table>
     </fieldset>
    </td>
   </tr>
   <tr>
    <td> 
     <fieldset>
      <legend>Restore</legend>
       <table>
        <tr>
         <td>Filename</td>
          <td><input type='file' name='file'></td>
          <td><input type="submit" name="action" value="Restore"></td>
         </tr>
        </td>
     </fieldset>  
    </td>
   </tr>
   </form>
  </table>
<?
  main_footer();
?>
