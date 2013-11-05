<?php
date_default_timezone_set('Africa/Lagos');
require_once("config.inc");

$con = connect();


function can_create_update($uid, $permission) { 
  global $con;
  $sql = sprintf("select * from user_permissions up join permissions p
   on up.pid = p.id 
   where up.uid='%s' and p.name='%s'",
   mysql_real_escape_string($uid),
   mysql_real_escape_string(format_label($permission)));
 
  $result = mysql_query($sql, $con) or die(mysql_error());
  $row = mysql_fetch_array($result); 
  
  return ($row['rw'] == '1') ? true : false;
} 

function can_delete($uid, $permission) { 
  global $con;
  $sql = sprintf("select * from user_permissions up join permissions p
   on up.pid = p.id 
   where up.uid='%s' and p.name='%s'",
   mysql_real_escape_string($uid),
   mysql_real_escape_string($permission));
 
  $result = mysql_query($sql, $con) or die(mysql_error());
  $row = mysql_fetch_array($result); 
  
  return ($row['d'] == '1') ? true : false;
} 

function is_fk($name) {
  return (strpos($name, "_id") !== false) ? true : false;
}

function get_table_name($name) {
  return substr($name, 0, stripos($name, "_id")); 
}

function get_second_col($table) {
  global $con;
  $temp = array();
  $sql = "describe $table";
  $result_k = mysql_query($sql, $con) or die(mysql_error());
  while($row_k = mysql_fetch_array($result_k))
    $temp[] = $row_k[0];
  return $temp[1];
}

function in_user_perm($arr1, $arr2) {
  foreach($arr1 as $perm) {
    if ($perm == 'Divider') 
      $menu['Divider'] = '';
    else if (array_key_exists($perm, $arr2)) { 
      $menu[$arr2[$perm]] = $perm;
    } 
  }
  return $menu;
}
 
function in_array_i($arr1, $arr2) {
  foreach($arr1 as $perm) {
    if (array_key_exists($perm, $arr2))
      return true;
  }
  return false;
}

function rorz($var) {
    return empty($var) ? 0 : $var;
  }

function rollback($id, $con) {
  $sql="select * from audit_trail where id=$id";
  $result = mysql_query($sql, $con) or die(mysql_error());  
  $row = mysql_fetch_array($result);
  $data = explode("|", $row['ot']);
  if (isset($data)) {
    foreach($data as $value) {
      $data2 = explode(" ", $value);
      if ((count($data2) > 1) && (!empty($data2[0]))) {
        
        $sql="delete from {$data2[0]} where ";
		unset($data2[0]);
		foreach($data2 as $value2) {
		   if(empty($value2))
		     continue;
            $sql .= " id=$value2 or ";
        }  
        $sql = substr($sql, 0, -4);
        mysql_query($sql, $con) or die(mysql_error());
      }
    }
  } 
  //Now delete entry in audit_trail
  $sql="delete from audit_trail where id=$id";
  mysql_query($sql, $con) or die(mysql_error());
}
function run_backup_restore($host, $url) {
  //Setup a basic HTTP Connection to run Restore on the foreign server
  $fp = fsockopen($host, 80, $errno, $errstr, 30);
  if (!$fp) {
    echo "$errstr ($errno)<br />\n";
  } else {	
    echo "Connected to HTTP Connection<br>";
    echo "Running backup program on $host: $url<br>";
	
	$out = "GET  $url HTTP/1.1\r\n";
    $out .= "Host: $host\r\n";
    $out .= "Connection: Close\r\n\r\n";
	
    fwrite($fp, $out);
	 while (!feof($fp)) {
        echo fgets($fp, 128);
    }
    fclose($fp);
  }
}

function upload_download($command, $conn_id) {
  if ($command == 'download') {
  
    if (file_exists('backup')) 
	  unlink('backup');
	  
    //f (ftp_get($conn_id, "backup", "backup", FTP_BINARY)) {
	if (ftp_get($conn_id, "backup", "backup", FTP_ASCII)) {
      echo "Successfully downloaded backup<br>";
    } else {
      echo "There was a problem: Please re-run schrnoziation again<br>
	   Or Contact Technical Support";
    }
  } 
  if ($command == 'upload') {
    // try to delete $file
    if (ftp_delete($conn_id, 'backup')) {
     echo "backup deleted successful<br>";
    } else {
     echo "could not delete backup <br>";
    }
	
	$fp = fopen('backup', 'r');
 
    // upload the file
    $upload = ftp_fput($conn_id, 'backup', $fp, FTP_BINARY); 

    // check upload status
    if (!$upload) { 
     echo "FTP upload has failed!<br>";
    } else {
     echo "Uploaded backup file <br>";
    }
  }
}

//Borrowed from data_valid_fns.php 
function clean($string) {
  $string = trim($string);
  $string = htmlentities($string);
  $string = strip_tags($string);
  return $string;
}

function audit_trail($uid, $desc, $others, $con) {
  #use current_date instead of curr_date_time to allow searching by sd and ed
  $result = mysql_query("insert into audit_trail
   (dt, staff_id, descr, ot, dt2) values(NOW(), '$uid', '$desc', '$others', CURDATE())", $con) 
   or die("Cannot execute SQL audit_trail " . mysql_error());
  return mysql_insert_id();
}
// seed with microseconds
function make_seed() {
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}

function check($value, $msg, $url, $back='Back') {
  if (empty($value)) {
    msg_box($msg, $url, $back);
    main_footer();
    exit;
  }
}
function my_check_date($value, $msg, $url, $back='Back') {
  check($value, $msg, $url, $back);

  list($year, $month, $day) = explode("-", $value);
  if (!checkdate($month, $day, $year)) {
    msg_box($msg, $url, $back);
    main_footer();
    exit;
  }
}

function send_mail($school_id, $email, $subject, $msg, $con) {
  // multiple recipients
  $to  = "$email" . ', '; // note the comma
  //$to .= 'wez@example.com';

  $school_name = get_value('school', 'name', 'id', $school_id, $con);

  // message
  $message = " 
   <html>
    <body> $msg </body>
   </html>
  ";

  // To send HTML mail, the Content-type header must be set
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

  // Additional headers
  $headers .= "To: {$school_name}<{$email}>" . "\r\n";
  $headers .= 'From: Acadbase <contact@acadbase.com>' . "\r\n";
  $headers .= 'Bcc: all@acadbase.com' . "\r\n";

  // Mail it
  mail($to, $subject, $message, $headers);
}
function validate_email($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function update($table, $sql, $con) {
  //Since this is an upgrade, we need to check if this table exist
 //If it doesn't exist then create it automatically
 $result = mysql_query("show tables", $con) or die(mysql_error());
 while($row = mysql_fetch_array($result))
   if($row['Tables_in_school'] == $table)
	 return;
 mysql_query($sql, $con) or die(mysql_error());
}
 
function connect() {
  global $dbserver, $dbusername, $dbpassword, $database;
  
  $con = mysql_connect($dbserver, $dbusername, $dbpassword) 
     or die("Cannot connect to database server " . mysql_error());
    mysql_select_db($database, $con) 
     or die("Cannot select database" . mysql_error());
  return $con;
}
function connect_pdo() {
  global $dbserver, $dbusername, $dbpassword, $database;
  $dbh = null;
  try {
    $dbh = new PDO("mysql:host=$dbserver;dbname=$database", "$dbusername", "$dbpassword");
  } catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  return $dbh;
}
function msg_box($msg, $url, $urlname) {
  echo "<div class=\"alert\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\">
               &times;</button> 
               $msg
               <a href='{$url}'>{$urlname}</a></div>";
}

function my_redirect($url, $text) {
 echo " 
   <html>
   <body>
    <form name='x' action='$url' method='post'>
     $text
    </form>
    <script language='javascript'>
      document.x.submit();
    </script>
   </body>
  </html>
  ";
}


function my_query() {
  $temp = array();
  $arg_list = func_get_args();
  $result = mysql_query($arg_list[0]) or die(mysql_error());
  while($row = mysql_fetch_array($result)) {
    if (func_num_args() > 4) {
      $temp[$row[$arg_list[1]]] =
       $row[$arg_list[2]] . " " . $row[$arg_list[3]] . " " . $row[$arg_list[4]];
    } else if (func_num_args() > 3) {
      $temp[$row[$arg_list[1]]] =
       $row[$arg_list[2]] . " " . $row[$arg_list[3]];
    } else {
      $temp[$row[$arg_list[1]]] = $row[$arg_list[2]];
    }
  }
  return $temp;
}

function get_values($table, $field1, $field2, $field3) {
  $a = array();
  $sql="select $field1 from $table where $field2=$field3";
  
  $result = mysql_query($sql);
  while($row = mysql_fetch_array($result)) {
    $a[$field2] = $row[$field1];
  }
  return $a;
}
function get_value($table, $field1, $field2, $field3, $con) {
  $sql="select $field1 from $table where $field2 = '$field3'";
  $result = mysql_query($sql);
  $row = mysql_fetch_array($result);
  return $row[$field1];
}

function gen_insert_sql($table, $skip, $con) {
  $sql1 = "Insert into $table(";
  $sql2="describe $table";
  $result = mysql_query($sql2, $con) or die(mysql_error());
  while($field = mysql_fetch_array($result)) {
    if (($field[0] == 'id') || (in_array($field[0], $skip)))
          continue;
	if (isset($_REQUEST[$field[0]]))
      $sql1 .= "{$field[0]}, ";
  }
  $sql1 = substr($sql1, 0, -2);
  $sql1 .= ") values(";

  $result = mysql_query($sql2) or die(mysql_error());
  while($field = mysql_fetch_array($result))  {
    if (($field[0] == 'id') || (in_array($field[0], $skip)))
          continue;
    else if (strpos($field[0], "_image") !== false) { //eregi("_image", $field[0]))
	  if(!empty($_FILES[$field[0]]['name'])) 
        $sql1 .= "'{$_FILES[$field[0]]['name']}', ";
	  else if (isset($_REQUEST[$field[0]]))
	    $sql1 .= "'{$_REQUEST[$field[0]]}', ";
	}
    else if (isset($_REQUEST[$field[0]]))
	  $sql1 .= "'{$_REQUEST[$field[0]]}', ";
  }
  $sql1 = substr($sql1, 0, -2);
  $sql1 .= ")";
  return $sql1;
}
function gen_update_sql($table, $first_col, $first_col_value, $skip, $con) {
  $sql="update $table set ";
  $sql1 = "show columns from $table";
  $result1 = mysql_query($sql1) or die(mysq_error());
  while($field = mysql_fetch_array($result1)) {
    if (($field[0] == 'id') || (in_array($field[0], $skip)))
      continue;
	else if (strpos($field[0], "_image") !== false) { //else if (eregi("_image", $field[0])) {
	  if(!empty($_FILES[$field[0]]['name'])) 
        $sql .= "$field[0] = '{$_FILES[$field[0]]['name']}', ";
	  else
	    $sql .= "$field[0] = '{$_REQUEST[$field[0]]}', ";
    } else
      $sql .= "$field[0]='{$_REQUEST[$field[0]]}', ";
  }

  $sql = substr($sql, 0, -2);
  $sql .= " where {$first_col}={$first_col_value}";
  return $sql;
}
function gen_delete_sql($table, $id, $id_value, $con) {
  return "delete from $table where $id={$id_value}";
}
function parse_enum($field) {
  $label = preg_replace(array('/enum/'), array(''), $field);
  $label = str_replace('(', '', $label);
  $label = str_replace(')', '', $label);
  $label = str_replace("'", '', $label);
  $arr = explode(",", $label);
  foreach($arr as $index => $value)
    $arr2[$value] = $value;
  return $arr2;
}

function format_label($label) { 
  //Strip of _image and _id from any label that has it
  $arr = array('_image', '_id');
  foreach ($arr as $term) {
    if (strpos($label, $term) !== false)
      $label = substr($label, 0, stripos($label, $term));
  }
  return ucwords(strtolower(str_replace('_', ' ', $label))); 
}

function format_label2($label) { 
  //Strip of _image and _id from any label that has it
  $arr = array('_image', '_id');
  foreach ($arr as $term) {
    if (strpos($label, $term) !== false)
      $label = substr($label, 0, stripos($label, $term));
  }
  return strtolower(str_replace('_', ' ', $label)); 
}

function format_label3($label) { 
  //Strip of _image and _id from any label that has it
  $arr = array('_image', '_id');
  foreach ($arr as $term) {
    if (strpos($label, $term) !== false)
      $label = substr($label, 0, stripos($label, $term));
  }
  return strtoupper(str_replace('_', ' ', $label)); 
}


function gen_list($table, $url, $first_col, $cols, $skip=array(), $sql, $con) {
  if (isset($_REQUEST['count'])) {
    $count = $_REQUEST['count'];
    $count += 20;
  } else 
    $count = 0;

  //Display Column titles
  echo "<thead><tr>";
  foreach($cols as $col) {
    if (in_array($col, $skip)) 
      continue;
    echo "<th>" . format_label($col). "</th>";
  }
  echo "</tr></thead><tbody>";

  $sql .= " limit $count, 20 "; 
  $result = mysql_query($sql, $con);
  while($row = mysql_fetch_array($result)) {
    echo "<tr>";
    $sql="describe $table";
    $result2 = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result2) <= 0) {
      echo "<tr style='text-align:center;'>
         <td colspan='6'><h4>No {$table} Found</h4></td></tr></form></table>";
       exit;
    }
    while($field = mysql_fetch_array($result2)) {
      if (in_array($field[0], $skip)) 
        continue;

      echo "<td class='style2'>";
      if ((!empty($first_col)) && ($first_col == $field[0])) {
        $url1 = "<a href='$url?action=Edit&table=$table&{$first_col}=";
        $url1 .= "{$row[$first_col]}&first_col={$first_col}'>";
        echo "{$url1}";
        echo $row[$field[0]];
        echo "</a>";
      } else if (strpos($field[0], "_id") !== false) {
        //lets get the table name
	$t = substr($field[0], 0, stripos($field[0], "_id")); 
		
	//Get all the column names for this table
	$sql = "describe $t";
	$arr = array();
	$rslt = mysql_query($sql, $con) or die(mysql_error());
	while($r = mysql_fetch_array($rslt))
	  $arr[] = $r[0];
		
	//Now lets get some column details from this table
	$sql = "select * from $t where {$arr[0]}={$row[$field[0]]} 
          order by {$arr[0]}";
	$rslt2 = mysql_query($sql, $con) or die(mysql_error());
	$r2 = mysql_fetch_array($rslt2);
	echo "{$r2[$arr[1]]} {$r2[$arr[2]]}";
	   //$arr = my_query($sql, 'id', 'first_name', 'last_name');
      } else {
        if (strlen($row[$field[0]]) > 20)
          echo substr($row[$field[0]], 0, 20) . " ..."; 
	else
	  echo $row[$field[0]];
      }
      echo "</td>";
    }
    echo "</tr>";
  } 
  echo "</tbody></table>";
  
  $sql = "select count(*) as 'count' from {$table}";
  $result = mysql_query($sql, $con) or die(mysql_error());
  $row = mysql_fetch_array($result);

  //echo "<tr class='class1'><td colspan='2'> Page " . (($count/20) + 1);
  //if (($row['count'] > 20) && ($row['count'] > ($count + 20))) { 
  //echo "<a href='{$url}?table={$table}&count={$count}'>Next>></a>";

  echo "<p class='class1'>";
  if (($row['count'] > 20) && ($count >= 20))
    echo "&nbsp;<a href='{$url}?table={$table}'><|</a>";

  if (($row['count'] > 20) && ($count >= 20) && ($count <= $row['count'])) {
    echo "&nbsp;<a href='{$url}?table={$table}";
    if (($count > 20) && ($count <= 40))
      echo "&count=0";
    else if ($count >= 40)
      echo "&count=" . ($count - 40);
    echo "'><<</a>";
  } 
  if (($row['count'] > 20) && ($row['count'] > ($count + 20)))
    echo "&nbsp;<a href='{$url}?table={$table}&count={$count}'>>></a>";

  if (($row['count'] > 20) && (
   ((($count + 20) < $row['count']) || (($count + 40) < $row['count']))))  {
    echo "&nbsp;<a href='{$url}?table={$table}&count=";

    if (($count + 40) < $row['count'])
     echo $row['count'] - 40;
    else if (($count + 20) < $row['count'])
      echo "0";

    echo "'>>|</a>";
  }

  echo "</td></tr>";
}
function generate_form($action, $id, $table, $row, $skip, $con, 
  $hidden_value=null, $extra=null) {
  echo "<form method='post' action='$action'>";
  
  $sql = "describe {$table}";
  $result = mysql_query($sql, $con) or die(mysql_error());

  while($field = mysql_fetch_array($result)) {
    if ($field[0] == $id) { //Lets store the id
      echo inputfield('id', 'first_col_value', 'name','first_col_value',
         'value', $row[$id], 'type','hidden');
      continue;
    }	
    if (in_array($field[0], $skip))
      continue;
	  
    echo "<div id='new1'><label for='{$field[0]}'>" 
      . ucwords(strtolower(str_replace('_', ' ', $field[0]))) . "</label>";
	
    if ($field[1] == 'text')
      echo textarea($field[0], $row[$field[0]]) . "</div>\n";
    else if ($field[1] == 'date') {
	  /*
      echo "
      	<script>
	$(function() {
	  $( \"#{$field[0]}\").datepicker({ dateFormat: \"yy-mm-dd\" });
	});
	
	</script>";
	*/
      $date = empty($row[$field[0]]) ? date('Y-m-d') : $row[$field[0]];
      echo inputfield('id', $field[0], 'name', $field[0] ,'value', 
        $date,'size','10','type','text') . "</div>\n";

    } else if ($field[1] == 'time') {
      $date = empty($row[$field[0]]) ? date('H:i:s') : $row[$field[0]];
      echo inputfield('id', $field[0], 'name',$field[0],'value', 
         $date,'size','8','type','text') . "</div>\n";

    } else if (strpos($field[1], "enum") !== false) {
      echo selectfield(parse_enum($field[1]), $field[0], 
        $row[$field[0]]) . "</div>\n";

    } else if (strpos($field[0], "_id") !== false) {
      $t = substr($field[0], 0, stripos($field[0], "_id"));
	  
      //Get all the column names for this table
      $sql = "describe $t";
      $arr = array();
      $rslt = mysql_query($sql, $con) or die(mysql_error());

	    while($r = mysql_fetch_array($rslt))
	      $arr[] = $r[0];
		
		  //Now lets get some column details from this table
		  if (empty($row[$field[0]])) 
		    $sql = "select * from $t order by {$arr[0]}";
		  else 
		    $sql = "select * from $t where {$arr[0]}={$row[$field[0]]} order by {$arr[0]}";
		    //echo "$sql<br>";
		    //$rslt2 = mysql_query($sql, $con) or die(mysql_error());
		    //$r2 = mysql_fetch_array($rslt2);
		    //echo "{$r2[$arr[1]]} {$r2[$arr[2]]}";
	      //$arr = my_query($sql, $arr[0], $arr[1], $arr[2], $arr[3]);
        echo inputfield('id', $field[0], 'name', $field[0],'value', $row[$field[0]], 'type','text') . "</div>\n";
	      //echo selectfield($arr, $field[0], $row[$field[0]]) . "</div>\n"; 
	  } else {
	    echo "<input type='text' id='{$field[0]}' name='{$field[0]}' value='{$row[$field[0]]}' size='30'></div>\n";
	  }
  }
  
  echo "<div class='buttons'>
   <input name='action' value='{$hidden_value}' type='submit'>
   <input type='hidden' name='table' value='{$table}' />
   <input type='hidden' name='first_col' value='{$id}' />
   <input type='hidden' name='id' value='{$id}' />";
   
  if ($hidden_value == 'Update')
    echo "&nbsp;<input class='button2' name='action' value='Delete' type='submit'>";
  
  echo $extra;
  echo "</div></form>";
  
   
}


function tr($arr) {
  $output = "<tr>";
  foreach($arr as $td) {
    $output .= "<td>$td</td>";
  }
  $output .= "</tr>";
  return $output;
}

function inputfield() {
  $numargs = func_num_args();
  if ($numargs < 2) die("textfield second argument is missing");
  
  $output = "<input ";
  $arg_list = func_get_args();
  for ($i = 0; $i < $numargs; $i+=2) {
    $output .= " {$arg_list[$i]}='{$arg_list[$i+1]}' ";
  }
  $output .= ">";
  return $output;
}

function textarea($name, $value, $rows='2', $cols='35') {
  $numargs = func_num_args();
  if ($numargs < 2) die("textarea second argument is missing");
  return "<textarea name='$name' rows='$rows' cols='$cols'>$value</textarea>";
}

function selectfield($arr, $name, $default_id, $style="display:inline;", $code="") {
  $output = "<select name='$name' id='$name' style='$style' onchange='$code'>";
  foreach($arr as $id => $value) {
    if ($id == $default_id)
      $output .= "<option value='$id' selected='selected'>$value</option>";
    else
      $output .= "<option value='$id'>$value</option>";
  }
  $output .="</select>";
  return $output;
}


function upload_file($filename, $url, $type='image') {
  if ($_FILES[$filename]['error'] != 4) {  
  //Lets upload the file
    if ($_FILES[$filename]['error'] > 0) {
      switch($_FILES[$filename]['error']) {
        case 1: echo msg_box('File exceeded upload max_filesize', 
          $url, 'OK'); break;
        case 2: echo msg_box('File exceeded max_file_size', 
          $url, 'OK'); break;
        case 3: echo msg_box('File only partially uploaded', 
          $url, 'OK'); break;
      }
      exit;
    } elseif ($type == 'image') {
      if ($_FILES[$filename]['type']
        != ('image/jpeg' || 'image/gif' || 'image/png')) {
        echo msg_box('Problem: file is not an image', $url, 'OK');
        exit;
      }
    } else if ($type == 'excel') {
      if ($_FILES[$filename]['type'] != 'application/vnd.ms-excel') {
        echo msg_box('Problem: file is not an Excel File', $url, 'OK');
        exit;
      } 
    } else {
      $upfile = "upload/". $_FILES[$filename]['name'];
      if(is_uploaded_file($_FILES[$filename]['tmp_name'])) {
        if(!move_uploaded_file($_FILES[$filename]['tmp_name'], $upfile)) {
          echo msg_box('Problem: Could not move file to destination directory', $url, 'OK');
          exit;
        }
      } else {
        echo msg_box("Problem: Possible file upload attack. Filename: " .
          $_FILES[$filename]['name'], $url, 'OK');
        exit;
      }
    }
  }
}

function calc_age($curr_secs, $prev_secs) {
  $secs = $curr_secs - $prev_secs;
  
  
  $years = floor($secs / 31449600);
  $secs = $secs % 31449600;

  //1 year = 52 weeks
  $weeks = floor($secs / 604800);
  $secs = $secs % 604800;
   
  $days = floor($secs / 86400);
  $secs = $secs % 86400;

  $hours = floor($secs / 3600);
  $secs = $secs % 3600;

  $mins = floor($secs / 60);
  $secs = $secs % 60;
  

  $temp = 0;
  
  if ($years > 0) {
    if ($years == 1) {
      $temp = "$years year ";
    } else {
      $temp = "$years years ";
    }
  } 
  else if ($weeks > 0) {
    if ($weeks == 1) {
	$temp = "$weeks week ";
    } else {
      $temp = "$weeks weeks ";
    }
  } else if ($days > 0) {
    if ($days == 1) {
      $temp = "$days day ";
    } else {
      $temp = "$days days ";
    }
  } 
  
  /*
  elseif ($hours > 0) {
    if ($hours == 1) {
      $temp = "$hours hour ";
    } else {
        $temp = "$hours hours ";
    }
  } elseif ($mins > 0) {
    if ($mins == 1) {
      $temp = "$mins min ";
    } else {
      $temp = "$mins mins ";
    }
  } else {
    if (($secs == 1) || ($secs == 0)) {
      $temp = "$secs second ";
    } else {
      $temp = "$secs seconds ";
    }
  }
  */
  return $temp;
}

function make_date($y, $m, $d) {
  if ($d < 10) {
    $d = "0$d";
  }
  if ($m < 10) {
    $m = "0$m";
  }
  return "$y-$m-$d";
}

function is_leap_year($y) {
  if (($y % 400) == 0) {
    return 1;
  } elseif (($y % 100) == 0) {
    return 0;
  } elseif (($y % 4) == 0) {
    return 1;
  } else {
    return 0;
  }
}

function generate_menu($heading, $menu) {  
  $arr = array('registration', 'pharmacy drugs', 
    'nurse m and e', 'pharmacy report', 'analysis of gngf supplements',
    'pediatrics', 'summary statistics nomis');
  echo "
  <li class=\"dropdown\">
  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">
   <span style='color:#664104;'>{$heading}</span><b class=\"caret\"></b></a>
   <ul class=\"dropdown-menu\">";

    foreach($menu as $url => $name) {
      //echo "<li><a href=\"{$url}&permission={$name}\">$name</a></li>\n";
      echo "<li>
        <a href=\"{$url}\">" . strtoupper($name) . "</a></li>\n";
      //if (in_array($name, $arr))
      //  echo "<li class=\"divider\"></li>";
    } 
   echo "
   </ul>
  </li>";
} 


function get_user_perm($uid, $con) {
  $sql="select p.name as 'name' from permissions p left join 
    (user u, user_permissions up) on (u.id = up.uid and up.pid = p.id) 
    where u.id = '$uid' order by p.id";
  $result = mysql_query($sql) or die(mysql_error());
  $temp = array();
  while($row = mysql_fetch_array($result))
    $temp[] = strtolower($row['name']);
  return $temp;
}

?>
