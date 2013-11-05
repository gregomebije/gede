<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "form.inc";
require_once "menu.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);


if (!in_array(format_label2($_REQUEST['table']), $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    $_REQUEST['table'], true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'],
   $_REQUEST['table'], true, $con);

?>

 <div class="container" style="margin-top: -15px;">
  <div class="page-header text-center" style='margin-bottom:5px; 
      margin-top:-10px;'>
   <h3 style='margin-bottom:-15px;'>
    <?php echo format_label3($_REQUEST['table']) ;?></h3>
  </div>

<?php 
if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == 'Update')) {
  if (!can_create_update($_SESSION['uid'], format_label($_REQUEST['table']))) {
    msg_box("Access Denied", "index.php?action=logout", "Logout");
    main_footer();
    exit;
  }
  $skip = array('id');
  if ($_REQUEST['table'] == 'gdr')
    $skip[] = 'gdr';

  //General Validation for empty GDR
  if (array_key_exists('gdr_id', $_REQUEST)) {
    check($_REQUEST['gdr_id'], 'Please enter a valid GDR',
   "add_edit.php?action=Edit&table={$_REQUEST['table']}&id={$_REQUEST['id']}");
  }

  //Validation rules for Client
  if ($_REQUEST['table'] == 'gdr') {

    check($_REQUEST['name'], 'Please enter Name', 
      "add_edit.php?action=Edit&table=gdr&id={$_REQUEST['id']}");

    my_check_date($_REQUEST['date_of_first_visit'], 
     'Please enter valid Date Of First Visit. Date should be in the
      format YYYY-MM-DD', 
      "add_edit.php?action=Edit&table=gdr&id={$_REQUEST['id']}");
  }

  //Validation logic for client_visit
  if ($_REQUEST['table'] == 'client_visit') {
    if ((!is_numeric($_REQUEST['gdr_id'])) || 
      empty($_REQUEST['purpose_of_visit'])) {
      msg_box("You have to enter a valid value for GDR
       and Purpose Of Visit",
       "add_edit.php?action=Edit&table=client_visit&id={$_REQUEST['id']}", 
       'Back');
      main_footer();
      exit;
    }
  }
  //Validation code for daily_income
  if ($_REQUEST['table'] == 'daily_income') {
    if (empty($_REQUEST['name']) && empty($_REQUEST['gdr'])) {
      echo msg_box("You have to enter either a Client's Name or a GDR",
        "add_edit.php?action=Edit&table=daily_income&id={$_REQUEST['id']}",
        'Back');
      main_footer();
      exit;
    }
  }
  //Validation code for GNGF 
  if ($_REQUEST['table'] == 'gngf_supplements') {
    if (empty($_REQUEST['name']) || empty($_REQUEST['receipt_number'])) {
      echo msg_box("You have to enter a Client's Name 
        and a Receipt Number",
      "add_edit.php?action=Edit&table=gngf_supplements&id={$_REQUEST['id']}", 
      'Back');
      main_footer();
      exit;
    }
  }
  //Validation code for credit_lab_income 
  if ($_REQUEST['table'] == 'credit_lab_income') {
    if (empty($_REQUEST['description']) || (!is_numeric($_REQUEST['amount']))) {
      echo msg_box("You have to enter a valid Description and Amount",
       "add_edit.php?action=Edit&table=credit_lab_income&id={$_REQUEST['id']}", 
       'Back');
      main_footer();
      exit;
    }
  }
  //Validation code for PITT
  if ($_REQUEST['table'] == 'pitt') {
    $skip[] = "comments";

    check($_REQUEST['name'], 'Please enter Name',
      "add_edit.php?action=Edit&table=pitt&id={$_REQUEST['id']}");

    my_check_date($_REQUEST['date_of_compilation'],
     'Please enter valid Date Of Compilation. Date should be in the
      format YYYY-MM-DD',
      "add_edit.php?action=Edit&table=pitt&id={$_REQUEST['id']}");
  }

  //General Validation date_of_visit
  if (array_key_exists('date_of_visit', $_REQUEST)) {
    my_check_date($_REQUEST['date_of_visit'],
     'Please enter valid Date Of Visit. Date should be in the
      format YYYY-MM-DD',
   "add_edit.php?action=Edit&table={$_REQUEST['table']}&id={$_REQUEST['id']}");
  } 

  //General Validation for date_of_registration
  if (array_key_exists('date_of_registration', $_REQUEST)) {
    my_check_date($_REQUEST['date_of_registration'],
     'Please enter valid Date Of Visit. Date should be in the
      format YYYY-MM-DD',
   "add_edit.php?action=Edit&table={$_REQUEST['table']}&id={$_REQUEST['id']}");
  } 
  $fkeys = array(); 

  //Check if foreign key was found
  foreach($_REQUEST as $name => $value) {
    if (is_fk($name)) { //Found a foreign Key
      $t = get_table_name($name); //Get table name

      //Get the id from the second col
      $fkeys[$name] = get_value($t, 'id', get_second_col($t),  
             $value, $con);  
      $skip[] = $name;

      //If ID wasn't found
      if (empty($fkeys[$name])) { 
        echo msg_box("There is no " . format_label($name) . " with " 
        .  strtoupper(get_second_col($t)) . " {$_REQUEST[$name]}", 
    "add_edit.php?action=Edit&table={$_REQUEST['table']}&id={$_REQUEST['id']}", 
           "Back");
        main_footer();
        exit;
      }
    }
  }

  $sql = gen_update_sql($_REQUEST['table'], 'id', 
     $_REQUEST['id'], $skip, $con);
  mysql_query($sql, $con) or die(mysql_error());

  //Table: pitt. Checking if a particular record is valid or not
  //If number of intervention is less than 3 then record is invalid
  if ($_REQUEST['table'] == 'pitt') {
    $min_intervention = 0;
    $arr = array('community_awareness', 'peer_education_model', 
      'school_based_approach', 'peer_education_model_plus', 
      'work_place', 'specific_population_awareness_cam', 'community_outreach',
      'infection_control_management_in_clinical_setting_interventions',
      'provision_of_STI_management_interventions',
      'vulnerability_interventions', 'strategy_reached');
    foreach($arr as $intervention) {
      if (!empty($_REQUEST[$intervention]))
        $min_intervention += 1;
    }
    if ($min_intervention < 3) {
      $sql = "update pitt set comments = 'Invalid' where id={$_REQUEST['id']}";
      mysql_query($sql, $con) or die(mysql_error());
    } else {
      $sql = "update pitt set comments = 'Valid' where id={$_REQUEST['id']}";
      mysql_query($sql, $con) or die(mysql_error());
    }
  }
  
  //Update foreign keys
  if (count($fkeys) > 0) { 
    foreach($_REQUEST as $name => $value) {
      if (is_fk($name)) { //Found a foreign Key
        $sql = "Update {$_REQUEST['table']} set $name='{$fkeys[$name]}'
          where id='{$_REQUEST['id']}'";
        mysql_query($sql, $con) or die(mysql_error());
      }
    }
  }
  echo msg_box("Successfully Updated", "", "");

} else if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == 'Insert')) {
  if (!can_create_update($_SESSION['uid'], format_label($_REQUEST['table']))) {
    msg_box("Access Denied", "index.php?action=logout", "Logout");
    main_footer();
    exit;
  }

  $skip = array('id');

  //General Validation for empty GDR
  if (array_key_exists('gdr_id', $_REQUEST)) {
    check($_REQUEST['gdr_id'], 'Please enter a valid GDR',
      "add_edit.php?action=New&table={$_REQUEST['table']}");
  }

  //Validation code for Client
  if ($_REQUEST['table'] == 'gdr') {

    check($_REQUEST['name'], 'Please enter Name', 
      'add_edit.php?action=New&table=gdr');

    my_check_date($_REQUEST['date_of_first_visit'], 
     'Please enter valid Date Of First Visit. Date should be in the
      format YYYY-MM-DD', 
      'add_edit.php?action=New&table=gdr');

  }

  //Validation logic for client_visit
  if ($_REQUEST['table'] == 'client_visit') {
    if ((!is_numeric($_REQUEST['gdr_id'])) || 
      empty($_REQUEST['purpose_of_visit'])) {

      msg_box("You have to enter a valid value for GDR
       and Purpose Of Visit",
        'add_edit.php?action=New&table=client_visit', 'Back');
      main_footer();
      exit;
    }
  }

  //Validation code for daily_income
  if ($_REQUEST['table'] == 'daily_income') {
    if (empty($_REQUEST['name']) && empty($_REQUEST['gdr'])) {

      echo msg_box("You have to enter either a Client's Name or a GDR",
      'add_edit.php?action=New&table=daily_income', 'Back');
      main_footer();
      exit;
    }
  }

  //Validation code for GNGF 
  if ($_REQUEST['table'] == 'gngf_supplements') {
    if (empty($_REQUEST['name']) || empty($_REQUEST['receipt_number'])) {
      echo msg_box("You have to enter a Client's Name 
        and a Receipt Number",
      'add_edit.php?action=New&table=gngf_supplements', 'Back');
      main_footer();
      exit;
    }
  }

  //Validation code for credit_lab_income 
  if ($_REQUEST['table'] == 'credit_lab_income') {
    if (empty($_REQUEST['description']) || (!is_numeric($_REQUEST['amount']))) {
      echo msg_box("You have to enter a valid Description and Amount",
      'add_edit.php?action=New&table=credit_lab_income', 'Back');
      main_footer();
      exit;
    }
  }

  //Validation code for PITT
  if ($_REQUEST['table'] == 'pitt') {
    $skip[] = "comments";

    check($_REQUEST['name'], 'Please enter Name',
      'add_edit.php?action=New&table=pitt');

    my_check_date($_REQUEST['date_of_compilation'],
     'Please enter valid Date Of Compilation. Date should be in the
      format YYYY-MM-DD',
      'add_edit.php?action=New&table=pitt');
  }

  //General Validation date_of_visit
  if (array_key_exists('date_of_visit', $_REQUEST)) {
    my_check_date($_REQUEST['date_of_visit'],
     'Please enter valid Date Of Visit. Date should be in the
      format YYYY-MM-DD',
      "add_edit.php?action=New&table={$_REQUEST['table']}");
  }

  //General Validation date_of_registration_
  if (array_key_exists('date_of_registration', $_REQUEST)) {
    my_check_date($_REQUEST['date_of_registration'],
     'Please enter valid Date Of Registration. Date should be in the
      format YYYY-MM-DD',
      "add_edit.php?action=New&table={$_REQUEST['table']}");
  }


  //Validation Code for foreing keys, which most of the time is gdr_id
  $fkeys = array(); 

  //Check if foreign key was found
  foreach($_REQUEST as $name => $value) {
    if (is_fk($name)) { //Found a foreign Key
      $t = get_table_name($name); //Get table name

      //Get the id from the second col
      $fkeys[$name] = get_value($t, 'id', get_second_col($t),  
             $value, $con);  
      $skip[] = $name;

      //If ID wasn't found
      if (empty($fkeys[$name])) { 
        echo msg_box("There is no " . format_label($name) . " with " 
          . strtoupper(get_second_col($t)) . " {$_REQUEST[$name]}", 
          "add_edit.php?action=New&table={$_REQUEST['table']}", "Back");
        main_footer();
        exit;
      }
    }
  }

  $sql = gen_insert_sql($_REQUEST['table'], $skip, $con);
  mysql_query($sql, $con) or die(mysql_error());
  $id = mysql_insert_id();

  //Make the GDR auto-generated
  if ($_REQUEST['table'] == 'gdr') {
    $sql = "update gdr set gdr='{$id}' where id={$id}";
    mysql_query($sql, $con) or die(mysql_error());
  }

  //Table: pitt. Checking if a particular record is valid or not
  //If number of intervention is less than 3 then record is invalid
  if ($_REQUEST['table'] == 'pitt') {
    $min_intervention = 0;
    $arr = array('community_awareness', 'peer_education_model', 
      'school_based_approach', 'peer_education_model_plus', 
      'work_place', 'specific_population_awareness_cam', 'community_outreach',
      'infection_control_management_in_clinical_setting_interventions',
      'provision_of_STI_management_interventions',
      'vulnerability_interventions', 'strategy_reached');
    foreach($arr as $intervention) {
      if (!empty($_REQUEST[$intervention]))
        $min_intervention += 1;
    }
    if ($min_intervention < 3) {
      $sql = "update pitt set comments = 'Invalid' where id={$id}";
      mysql_query($sql, $con) or die(mysql_error());
    } else {
      $sql = "update pitt set comments = 'Valid' where id={$id}";
      mysql_query($sql, $con) or die(mysql_error());
    }
  }
  

  //Update foreign keys
  if (count($fkeys) > 0) { 
    foreach($_REQUEST as $name => $value) {
      if (is_fk($name)) { //Found a foreign Key
        $sql = "Update {$_REQUEST['table']} set $name='{$fkeys[$name]}'
          where id='{$id}'";
        mysql_query($sql, $con) or die(mysql_error());
      }
    }
  }
  echo msg_box("Successfully Inserted", "", "");

} else if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == 'Delete')) {
  if (!can_delete($_SESSION['uid'], format_label($_REQUEST['table']))) {
    msg_box("Access Denied", "index.php?action=logout", "Logout");
    main_footer();
    exit;
  }
  if (!isset($_REQUEST['id'])) {
    echo msg_box("Please choose a record to delete", "", "");
  } else {
    $arr = array('arv', 'clinical_exam', 'counselling', 'gdr_cru',
      'lab_cd4_viral_load_drug_resistance', 'lab_chemistry', 'lab_heamatology',
      'lab_other_tests', 'lab_serology', 'non_arv', 'nurse_m_and_e', 
      'pediatrics', 'pharmacy_drugs', 'registration', 'credit_lab_income',
      'client_visit');

    $found = array();

    $msg = "Cannot delete this GDR, because there 
            are still records tired to this GDR in table ";

    $url = "add_edit.php?table={$_REQUEST['table']}&id={$_REQUEST['id']}";
    $url .= "&action=confirm_delete";

    if ($_REQUEST['table'] == 'gdr') {
      foreach ($arr as $t) {
        /*
        $sql = "select * from $t t join gdr g on t.gdr_id = g.gdr
          where g.id='{$_REQUEST['id']}'";
        */

        $sql = "select * from $t t join gdr g on t.gdr_id = g.id
          where g.id='{$_REQUEST['id']}'";

        $result = mysql_query($sql, $con) or die(mysql_error());
        if (mysql_num_rows($result) > 0)
          $found[] = $t;
      }
      if (count($found) > 0) 
        echo msg_box($msg . implode(", ", $found), "", ""); 
      else  {
        echo msg_box("Are you sure you want to delete this record?", 
          $url, "Yes");
      } 
    } else 
      echo msg_box("Are you sure you want to delete this record?", $url, "Yes");
  }

} else if ((isset($_REQUEST['action'])) && 
   ($_REQUEST['action'] == 'confirm_delete')) {

  if (!can_delete($_SESSION['uid'], format_label($_REQUEST['table']))) {
    msg_box("Access Denied", "index.php?action=logout", "Logout");
    main_footer();
    exit;
  }

  //Todo: Check again if a GDR is involved
  if (empty($_REQUEST['id'])) {
    echo msg_box("Please choose a record to delete", "", "");
  } else {
    $sql = "delete from {$_REQUEST['table']} 
      where id='{$_REQUEST['id']}'";
    mysql_query($sql, $con) or die(mysql_error());
    echo msg_box("Successfully Deleted", "", "");
  }
} else if ((isset($_REQUEST['action'])) && (($_REQUEST['action'] == 'New') ||
  $_REQUEST['action'] == 'Edit')) {

   if (!can_create_update($_SESSION['uid'], format_label($_REQUEST['table']))) {
      msg_box("Access Denied", "index.php?action=logout", "Logout");
      main_footer();
      exit;
    }
?>
 <form method='POST' action='add_edit.php'>
  <table width='100%'>
   <tr>
    <td>
     <table>

     <?php
     $sql = "describe {$_REQUEST['table']}";
     $result = mysql_query($sql, $con) or die(mysql_error());
     $column_count = mysql_num_rows($result);

     if ($_REQUEST['table'] == 'gdr') 
       $column_count -= 1;

     if ($_REQUEST['table'] == 'pitt') 
       $column_count -= 1;

     if (!isset($_REQUEST['id'])) 
       $id = "-1";
     else
       $id = $_REQUEST['id'];

     $sql = "select * from {$_REQUEST['table']} where id='{$id}'";
     $result1 = mysql_query($sql) or die(mysql_error());
     $row = mysql_fetch_array($result1);

     $count = 1;
     while($field = mysql_fetch_array($result)) {
       if (($field[0] == 'id') || 
         (($_REQUEST['table'] == 'gdr') && ($field[0] == 'gdr'))) {
         continue;
       }

       if (($_REQUEST['table'] == 'pitt') && ($field[0] == 'comments')) 
         continue;

       echo "<tr><td>" . format_label($field[0]) . "</td><td>";

       if ($field[1] == 'text') {
         echo textarea($field[0], $row[$field[0]], '5', '70')
              . "</td>\n";

       } else if ($field[1] == 'date') {
         echo "
         <script>
          $(function() {
            $( \"#{$field[0]}\" ).datepicker({ dateFormat: \"yy-mm-dd\" });
          });
          </script>";
         $date = empty($row[$field[0]]) ? date('Y-m-d') : $row[$field[0]];
         echo inputfield('id', $field[0], 'name', $field[0] ,
           'value', $date,'size','10','type','text') . "</td>\n";

       } else if ($field[1] == 'time') {
         $date = empty($row[$field[0]]) ? date('H:i:s') : $row[$field[0]];
         echo inputfield('id', $field[0], 'name',$field[0],'value', 
           $date,'size','8','type','text') . "</td>\n";

       } else if (strpos($field[1], "enum") !== false) {
         echo selectfield(parse_enum($field[1]), $field[0], 
            $row[$field[0]]) . "</td>\n";

       } else if (is_fk($field[0])) {
         $t = get_table_name($field[0]);
	  
          //Get all the column names for this table
          $arr = array();
          $rslt = mysql_query("describe $t", $con) or die(mysql_error());
	  while($r = mysql_fetch_array($rslt))
	    $arr[] = $r[0];
		
	  if (empty($row[$field[0]])) 
	    $sql = "select * from $t where id = -1 order by {$arr[0]}";
	  else {
	    $sql = "select * from $t where {$arr[0]}={$row[$field[0]]} 
             order by {$arr[0]}";
          }
          $result1 =  mysql_query($sql, $con) or die(mysql_error());
          $row1 = mysql_fetch_array($result1);
          echo inputfield('id', $field[0], 'name', $field[0],
            'value', $row1[get_second_col($t)], 'type','text') . "</td>\n";
       } else {
         echo "<input type='text' id='{$field[0]}' name='{$field[0]}' 
           value='{$row[$field[0]]}' size='30'></td>\n";
       }
       echo "</tr>";
       $count = $count + 1;

       if (!$flag && ((abs($column_count / 2)) < $count)) {
         echo "</table>
               </td>
               <td>
              <table>";
         $flag = true;
       } else if ($column_count == $count) { 
         echo "
           </table>
           </td> ";
       } 
     } 
     echo "</tr></table>";
     echo '<div class="form-actions">';
       if ($id == -1) {
         echo '<button name = "action" 
              type="submit" value="Insert" class="btn btn-primary">
              Insert</button>';
       } else {
         echo '<button name="action" 
              type="submit" value="Update" class="btn btn-primary"> 
               Update</button>';

          if (can_delete($_SESSION['uid'], format_label($_REQUEST['table']))) {
            echo '&nbsp;&nbsp;&nbsp;<button name="action" type="submit" 
                value="Delete" class="btn">Delete</button>';
           }
         echo " <input type=\"hidden\" name=\"id\" value=\"{$id}\" />";
       }
       echo "<input type='hidden' name='table' 
            value='{$_REQUEST['table']}' />";
     ?>
     </div> 
    </form>
<?php 
}
main_footer();
?>


