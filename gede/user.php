<?
session_start();

if (!isset($_SESSION['uid'])) {
    header('Location: index.php');
    exit;
}
error_reporting(E_ALL);

require_once "form.inc";
require_once "menu.inc";

$con = connect(); 

$temp = get_user_perm($_SESSION['uid'], $con);
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Print')) {
  print_header('User List', 'user.php', '', $con);
} else {
  main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'],
   'User Management', true, $con);
}
?>

 <div class="container" style="margin-top: -15px;">
  <div class="page-header text-center" style='margin-bottom:5px;
      margin-top:-10px;'>
   <h3 style='margin-bottom:-15px;'>
    User Management</h3>
  </div>

<?php

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Delete')) {

  if (empty($_REQUEST['id'])) {
    msg_box("Please choose a user", 'user.php', 'Back');
  main_footer();
    exit;
  }
  if ($_REQUEST['id'] == $_SESSION['uid']) {
    msg_box("Deletion denied<br>
       You cannot delete this user while logged in ", 'user.php', 'Back');
  main_footer();
    exit;
  }
  if ( (!in_array('administrator', $temp))
    && ($_SESSION['uid'] != $_REQUEST['id'])) {
    msg_box("Deletion denied<br>
     Security Alert***You cannot delete someone elses account ", 
     'user.php', 'Back');
  main_footer();
    exit;
  }
  msg_box("Are you sure you want to delete " . 
    get_value('user', 'name', 'id', $_REQUEST['id'], $con)
     . " User?" , 
     "user.php?action=confirm_delete&id={$_REQUEST['id']}", 
     'Continue to Delete');
  main_footer();
  exit;
  

} else if (isset($_REQUEST['action']) && 
  ($_REQUEST['action'] == 'confirm_delete')) {

  if (empty($_REQUEST['id'])) {
    msg_box("Please choose a User", 'user.php', 'Back');
  main_footer();
    exit;
  }
  if ($_REQUEST['id'] == $_SESSION['uid']) {
    msg_box("Deletion denied<br>
      You cannot delete this user while logged in ", 'user.php', 'Back');
  main_footer();
    exit;
  }
  if ((!in_array('administrator', $temp))
    && ($_SESSION['uid'] != $_REQUEST['id'])) {
    msg_box("Deletion denied<br>
       Security Alert***You cannot delete someone elses account ", 
       'user.php', 'Back');
  main_footer();
    exit;
  }
  $sql="select * from user where id={$_REQUEST['id']}";
  $result = mysql_query($sql, $con) or die(mysql_error());
  if (mysql_num_rows($result) <= 0) {
    msg_box("User does not exist in the database", 'user.php', 'OK');
  main_footer();
    exit;
  }
  mysql_query("DELETE FROM user_permissions where uid=". $_REQUEST['id']) 
     or die(mysql_error());

  $sql="delete from user where id={$_REQUEST['id']}";
  $result = mysql_query($sql) or die(mysql_error());
	
  msg_box("User has been deleted", 'user.php', 'OK');

} else if (isset($_REQUEST['action']) 
  && ($_REQUEST['action'] == 'Change Password')) {

  ?>
  <form name='form1' action="user.php" method="post">
   <table> 
    <tr class='class1'>
     <td colspan='3'><h3><?php echo $_REQUEST['action']; ?> User</h3></td>
    </tr>
    <tr>
     <td>Password</td>
     <td><input type="password" name="password1"></td>
    </tr>
    <tr>
     <td>Retype Password</td>
     <td><input type="password" name="password2"></td>
    </tr>
    <?php echo "<input type='hidden' name='id' value='{$_REQUEST['id']}'>"; ?>	
    <tr>
     <td><input type='submit' name='action' value='Update Password'></td></tr>
   </table>
  </form>
  <?php
  main_footer();
  exit;

} else if (isset($_REQUEST['action']) 
  && ($_REQUEST['action'] == 'Update Password')) {

  if (empty($_REQUEST['id'])) {
    msg_box("Please choose a user", 'user.php', 'Back');
    exit;
  }
  if ((!in_array('administrator', $temp)) && 
    ($_SESSION['uid'] != $_REQUEST['id'])) {
    msg_box("Security Alert: You cannot change someone elses password ", 
      'user.php', 'Back');
    exit;
  }
  if (empty($_REQUEST['password1']) || empty($_REQUEST['password2'])) {
    msg_box('Please enter correct passwords', "user.php", 'Back');
    exit;
  }
  if ($_REQUEST['password1'] != $_REQUEST['password2']) {
    msg_box('Passwords are not equal', "user.php", 'Back');
    exit;
  }
  $sql="update user set password = md5('{$_REQUEST['password1']}') 
    where id='{$_REQUEST['id']}'";
  mysql_query($sql, $con) or die(mysql_error());
	
  msg_box("Password Changed", 'user.php', 'Continue');

} else if (isset($_REQUEST['action']) && ($_REQUEST['action']=='Update User')) {

  if (empty($_REQUEST['id'])) {
    msg_box("Please choose a user", 'user.php', 'Back');
    main_footer();
    exit;
  }
  //Change users firstname and lastname
  $sql="update user set firstname='{$_REQUEST['firstname']}', 
       lastname='{$_REQUEST['lastname']}' where id={$_REQUEST['id']}";
  mysql_query($sql, $con) or die(mysql_error());
 
  //First delete all the permissions for this user
  //$sql="delete from user_permissions where uid={$_REQUEST['id']}";
  //mysql_query($sql, $con) or die(mysql_error());
    
  //echo "{$_REQUEST['u_permissions_members']}<br />";
	
  //Make sure that user was given at least one permission
  if (empty($_REQUEST['u_permissions_members'])) {
    msg_box("A User must have at least one permision", 
      "user.php?action=Edit&id={$_REQUEST['id']}", 'Back');
    exit;
  }

  if (!empty($_REQUEST['u_permissions_members'])) {
    //Then loop through the permissions
    $data = explode("|", $_REQUEST['u_permissions_members']); 

    foreach ($data as $pid) {

      //If the permission doesn't exist then add it
      $sql = "select * from user_permissions where uid='{$_REQUEST['id']}'
        and pid='{$pid}'";
      if (mysql_num_rows(mysql_query($sql, $con)) == 0) { 
        $sql="insert into user_permissions(uid, pid, rw, d) values 
          ('{$_REQUEST['id']}', '$pid', '0', '0')";
        mysql_query($sql) or die(mysql_error());
      }
    }
    $sql = "delete from user_permissions where uid='{$_REQUEST['id']}' 
      and pid not in (" . implode(", ", $data) . ")";
    mysql_query($sql, $con) or die(mysql_error());

  }
  $id = $_REQUEST['id'];
  ?>
  <form method='post' action='user.php'>
  <table cellpadding="0" cellspacing="0" border="0"
   class="table table-striped table-bordered table-hover" id="example">
   <caption>Specify What 
    <b><?php echo get_value('user', 'name', 'id', $id, $con);?></b>
     can access</caption>
    <thead>
     <tr>
      <th>Permission</th>
      <th>Create/Update</th>
      <th>Delete</th>
     </tr>
   </thead>
   <tbody>
  <?php 
  $sql = "select up.pid, up.rw, up.d, p.name from user_permissions 
    up join permissions p on 
   up.pid = p.id where uid='{$id}' and p.name != 'administrator'";
  $result = mysql_query($sql, $con) or die(mysql_error());
  while($row = mysql_fetch_array($result)) { 
    echo "
     <tr>
      <td>{$row['name']}</td>
      <td><input type='checkbox' name='rw_{$row['pid']}' ";
   echo ($row['rw'] == '1') ? "checked='checked'" : "";

   echo "/></td>
      <td><input type='checkbox' name='d_{$row['pid']}'";
   echo ($row['d'] == '1') ? "checked='checked'" : "";

   echo "
    /> </td>
     </tr>";
  }
  ?>
  </tbody>
  </table>
  <input type='hidden' name='id' value='<?php echo $id;?>' />  
  <input type='submit' name='action' value='Finish' />  
 </form>
 <?php 
   main_footer();
   exit;

} else if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Finish')) {
  if (empty($_POST['id'])) {
    msg_box("Please choose a user", 'user.php', 'Back');
    main_footer();
    exit;
  }
  $sql = sprintf("select * from user_permissions where uid='%s'", 
    mysql_real_escape_string($_POST['id']));
  $result = mysql_query($sql, $con) or die(mysql_error());
  
  while($row = mysql_fetch_array($result)) {
    $rw = "rw_{$row['pid']}";
    $d = "d_{$row['pid']}";

    if (isset($_POST[$rw])) {
      $sql = "update user_permissions set rw='1' where id='{$row['id']}'";
      mysql_query($sql, $con) or die(mysql_error());
    } else {
      $sql = "update user_permissions set rw='0' where id='{$row['id']}'";
      mysql_query($sql, $con) or die(mysql_error());
    } 

    if (isset($_POST[$d])) {
      $sql = "update user_permissions set d='1' where id='{$row['id']}'";
      mysql_query($sql, $con) or die(mysql_error());
    } else { 
      $sql = "update user_permissions set d='0' where id='{$row['id']}'";
      mysql_query($sql, $con) or die(mysql_error());
    }
  }
  msg_box("Successfully Updated", '', '');

} else if (isset($_REQUEST['action']) 
   && ($_REQUEST['action'] == 'Add User')) {

  if (empty($_REQUEST['username']) || empty($_REQUEST['firstname']) 
      || empty($_REQUEST['lastname'])) {
    msg_box("Please fill out the form", 'user.php?action=Add', 'Back');
    exit;
  }
  if (empty($_REQUEST['password1']) || empty($_REQUEST['password2'])) {
    msg_box('Please enter the passwords','user.php?action=Add', 'Back');
    exit;
  }
  if ($_REQUEST['password1'] != $_REQUEST['password2']) {
    msg_box('Passwords are not equal', 'user.php?action=Add', 'Back');
    exit;
  }
  $sql = "select * from user where name='{$_REQUEST['username']}'";
  $result = mysql_query($sql) or die(mysql_error());
  if (mysql_num_rows($result) > 0) {
    msg_box("There is already another user with the same username
     Please choose another user", 'user.php?action=Add', 'Back');
    exit;
  }


  //Make sure that user was given at least one permission
  if (empty($_REQUEST['u_permissions_members'])) {
    msg_box("A User must have at least one permision", 
      'user.php?action=Add', 'Back');
    exit;
  }
	
  $sql="insert into user(name, password, entity_id, firstname, lastname)
       values('{$_REQUEST['username']}', md5('{$_REQUEST['password1']}'), 
       '1', '{$_REQUEST['firstname']}', '{$_REQUEST['lastname']}')";
  $result = mysql_query($sql, $con) or die(mysql_error());
  $uid = mysql_insert_id();
    
  //Now add the permissions
  $data = explode("|", $_REQUEST['u_permissions_members']);
  foreach ($data as $pid) {
    $sql="insert into user_permissions(uid, pid, rw, d) 
          values ($uid, $pid, '0', '0')";
    mysql_query($sql, $con) or die(mysql_error());
  }
  ?>
  <form method='post' action='user.php'>
  <table cellpadding="0" cellspacing="0" border="0"
   class="table table-striped table-bordered table-hover" id="example">
   <caption>Specify What 
    <b><?php echo get_value('user', 'name', 'id', $uid, $con);?></b>
     can access</caption>
    <thead>
     <tr>
      <th>Permission</th>
      <th>Create/Update</th>
      <th>Delete</th>
     </tr>
   </thead>
   <tbody>
  <?php 
  $sql = "select up.pid, up.rw, up.d, p.name from user_permissions 
    up join permissions p on 
   up.pid = p.id where uid='{$uid}' and p.name != 'administrator'";
  $result = mysql_query($sql, $con) or die(mysql_error());
  while($row = mysql_fetch_array($result)) { 
    echo "
     <tr>
      <td>{$row['name']}</td>
      <td><input type='checkbox' name='rw_{$row['pid']}' ";
   echo ($row['rw'] == '1') ? "checked='checked'" : "";

   echo "/></td>
      <td><input type='checkbox' name='d_{$row['pid']}'";
   echo ($row['d'] == '1') ? "checked='checked'" : "";

   echo "
    /> </td>
     </tr>";
  }
  ?>
  </tbody>
  </table>
  <input type='hidden' name='id' value='<?php echo $uid;?>' />  
  <input type='submit' name='action' value='Finish' />  
 </form>
 <?php 
   main_footer();
   exit;

}  else if (isset($_REQUEST['action']) &&  (($_REQUEST['action'] == 'Add')  
  || ($_REQUEST['action'] == 'Edit'))) {


  //Only admin can access this part of the module
  if (!in_array('administrator', $temp)){
    msg_box('Access Denied', 'user.php', 'Back');
    exit;
  }
  $id = 0;
  if ($_REQUEST['action'] != 'Add') {
    $id = $_REQUEST['id'];
    if (empty($_REQUEST['id'])) {
      msg_box("Please choose a user", 'user.php', 'Back');
      exit;
    }
  }
  $sql = sprintf("select * from user where id='%s'", 
    mysql_real_escape_string($id));
  $result = mysql_query($sql, $con) or die(mysql_error());
  $row = mysql_fetch_array($result);
  ?>
  <form name='form1' action="user.php" method="post">
   <table style='width:200%;'> 
    <tr class='class1'>
     <td colspan='3'><h3><?php echo $_REQUEST['action']; ?> User</h3></td>
    </tr>
    <tr>
     <td>Username</td>
     <td><input type="text" name="username"
     <?php 
     if (($_REQUEST['action'] == 'Edit') || ($_REQUEST['action'] == 'View')) 
       echo "value = '{$row['name']}' disabled='disabled'>";
     else 
       echo ">";
     ?>
     </td>
    </tr>
    <?php
      if ($_REQUEST['action'] == 'Add') {
    ?>
    <tr>
     <td>Password</td>
     <td><input type="password" name="password1"></td></tr>

    <tr>
     <td>Retype Password</td>
     <td><input type="password" name="password2"></td> 
    </tr>
    <?php
    }
    ?>
    <tr>
     <td>Firstname</td>
     <td>
      <input type="text" name="firstname" 
       value='<?php echo $row['firstname']; ?>'>
     <?php
     if ($_REQUEST['action'] == 'Edit') {
       echo "<a href='user.php?action=Change Password&id={$_REQUEST['id']}'>
         Change Password</td>";
     }
     ?>
    </tr>
    <tr>
     <td>Lastname</td>
     <td>
      <input type="text" name="lastname" 
        value='<?php echo $row['lastname']; ?>'>
     </td>
    </tr>
    <tr class='class1'><td>All Permissions</td><td>My Permissions</td></tr>  
    <!--<tr style='display:inline;'>-->
    <tr>
     <!--<td  colspan='2' style='width:50em;'>-->
     <td colspan='2'>
      <!--<table style='table-layout:fixed;'>-->
      <table> 
       <tr>
        <td>
        <?php
         if (($_REQUEST['action'] == 'Add')||($_REQUEST['action'] == 'Edit')) {
           //Get all permissions
           $sql="select * from permissions ";
		 
           if($_REQUEST['action'] == 'Edit') {
             $sql .= " where id not in (select pid from user_permissions 
               where uid={$_REQUEST['id']})";
           }
           $result = mysql_query($sql, $con) or die(mysql_error());
           echo "<select name='pid' size='10' id='pid'>";
           while ($row = mysql_fetch_array($result)) {
             echo "<option value='{$row['id']}'>{$row['name']}</option>";
           }
           echo "
           </select>
	</td>
	<td>
         <table style='border: solid black 0.0em;'>
	  <tr>
	   <td>
	    <a name='adds' id='adds' onClick='transfer();'>
	     <img src='images/next.gif'></a>
	   </td>
	  </tr>
	  <tr>
	   <td>
            <a name='dels' id='dels' onClick='transfer2();'>
	     <img src='images/prev.gif'></a>
	   </td>
	  </tr>
	 </table>
	</td>
	";
        }
	?>
	<td>
         <select size='10' id='u_permissions' name='u_permissions'>
	 <?php 
         $perm = array();
	 if($_REQUEST['action'] == 'Edit'){
	   $sql ="select p.id, p.name as 'permission_name' from 
             permissions p join user_permissions up on 
	     (up.pid = p.id) where up.uid={$_REQUEST['id']}";
	   $result = mysql_query($sql, $con) or die(mysql_error());
	   while ($row = mysql_fetch_array($result)) {
	     echo "<option value='{$row['id']}'>{$row['permission_name']}
              </option>";
             $perm[] = $row['id'];
	   } 
	 }
	 ?>
         </select>
          <input type='hidden' name='u_permissions_members'
           value='<?php echo implode("|", $perm);?>'>
        </td>
       </tr>
      </table>
     </td>
    </tr>
    <?php  
    if($_REQUEST['action'] == 'Edit') { 
      echo "<input name='id' type='hidden' value='{$_REQUEST['id']}'>";
    }
    echo "<tr><td><input name='action' class='btn' type='submit' value='"; 
    echo $_REQUEST['action'] == 'Edit' ? 'Update' : 'Add';
    echo " User'></td>";
      if ($_REQUEST['action'] == 'Edit') {
        echo "<td><input name='action' class='btn' type='submit' 
          value='Delete'></td>";
      }
    ?>
    </tr>
   </table>
  </form>
  <?php
  main_footer();
  exit;
} 
  ?>
   <div>
     <?php
      if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'Print')) {
        echo "<p></p>";
      } else {
        if (in_array('administrator', $temp))
	  echo "<p><a href='user.php?action=Add'>Add</a></p>";
      }
      ?> 
  <table class="table table-striped table-bordered table-hover">
   <thead>
    <tr>
     <th>Username</th>
     <th>Firstname</th>
     <th>Lastname</th>
     <th>Permission</th>
    </tr>
   </thead>
   <tbody>
   <?php
   //Admin privilege means you can access all users
   if (in_array('administrator', $temp))
     $sql="select * from user";
   else
     $sql="select * from user where id={$_SESSION['uid']}";
	 
   $result = mysql_query($sql, $con) or die(mysql_error());
   while ($row = mysql_fetch_array($result)) {
     echo "
      <tr class='class3'>";
     if (in_array('administrator', $temp)) {
       echo "<td><a href='user.php?id={$row['id']}&action=Edit'>
           {$row['name']}</td>";
     }
     else {
       echo "<td><a href='user.php?id={$row['id']}&action=Change Password'>
           {$row['name']}</td>";
     }
     echo "
           <td>{$row['firstname']}</td>
           <td>{$row['lastname']}</td>
      <td>";
	
     $sql="select up.id, p.name, up.rw, up.d from user_permissions up 
           join permissions p on (up.pid = p.id)
	   where up.uid={$row['id']}";
     $result2 = mysql_query($sql, $con) or die(mysql_error());
     while($row2 = mysql_fetch_array($result2)) {
       echo "{$row2['name']}--";
       echo ($row2['rw'] == '1') ? "rw" : "";
       echo ($row2['d'] == '1') ? "d" : "";
       echo ",";
     }
     echo "</td>
         </tr>";
   }
   echo "</tbody></table>";
main_footer();
?>
