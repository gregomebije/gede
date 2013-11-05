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


$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array('org info', $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    'org_info', true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}
main_menu($_SESSION['uid'],
   $_SESSION['firstname'] . " " . $_SESSION['lastname'],
   'org_info', true, $con);

?>
<div class="container" style="margin-top: -15px;">
  <div class="page-header text-center" style='margin-bottom:5px;
      margin-top:-10px;'>
   <h3 style='margin-bottom:-15px;'> Organization Information
    </h3>

  </div>

<?php
if (isset($_REQUEST['action']) && 
  ($_REQUEST['action'] == 'Update')) {
  
  if (empty($_REQUEST['n'])) {
    echo msg_box("Please enter Name of Organization", "org_info.php", "Back");
   exit;
  } else {
    $result = mysql_query("select * from org_info where id=1", $con);
	if(mysql_num_rows($result) > 0) {
      if (!empty($_FILES['logo']['name'])) {
        if ($_FILES['logo']['error'] > 0) {
          echo '<tr><td>Problem: ';
          switch($_FILES['logo']['error']) {
            case 1: echo 'File exceeded upload max_filesize'; break;
            case 2: echo 'File exceeded max_file_size'; break;
            case 3: echo 'File only partially uploaded'; break;
            case 4: echo 'No file uploaded'; break;
          }
          echo '</td></tr>';
        } elseif ($_FILES['logo']['type']
            != ('image/jpeg' || 'image/gif' || 'image/png')) {
          echo '<tr><td>Prolem: file is not an image</td></tr>';
          exit;
        } else {
          //Delete previous file

          $upfile = "upload/". $_FILES['logo']['name'];
          if(is_uploaded_file($_FILES['logo']['tmp_name'])) {
            if(!move_uploaded_file($_FILES['logo']['tmp_name'], $upfile)) {
              echo '<tr><td>
                Problem: Could not move file to destination directory
                </td></tr>';
              exit;
            }
          } else {
            echo '<tr><td>Problem: Possible fiel upload attachk. Filename: ';
            echo $_FILES['logo']['name'] . '</td></tr>';
            exit;
          }
        }
        $sql="UPDATE org_info set logo = '{$_FILES['logo']['name']}'
          where id=1";
        mysql_query($sql);
	  }
      $sql="UPDATE org_info set name='{$_REQUEST['n']}', 
       address = '{$_REQUEST['a']}', phone = '{$_REQUEST['p']}',
       email = '{$_REQUEST['em']}', web = '{$_REQUEST['w']}'  where id=1";
      mysql_query($sql, $con);
    } else {
     if (!empty($_FILES['passport']['name'])) {
      if ($_FILES['logo']['error'] > 0) {
        echo '<tr><td>Problem: ';
        switch($_FILES['logo']['error']) {
          case 1: echo 'File exceeded upload max_filesize'; break;
          case 2: echo 'File exceeded max_file_size'; break;
          case 3: echo 'File only partially uploaded'; break;
          case 4: echo 'No file uploaded'; break;
        }
        echo '</td></tr>';
      } elseif ($_FILES['logo']['type']
            != ('image/jpeg' || 'image/gif' || 'image/png')) {
        echo '<tr><td>Prolem: file is not an image</td></tr>';
        exit;
      } else {
        $upfile = "upload/". $_FILES['logo']['name'];
        if(is_uploaded_file($_FILES['logo']['tmp_name'])) {
          if(!move_uploaded_file($_FILES['logo']['tmp_name'], $upfile)) {
            echo '<tr><td>
                Problem: Could not move file to destination directory
                </td></tr>';
            exit;
          }
        } else {
          echo '<tr><td>Problem: Possible fiel upload attachk. Filename: ';
          echo $_FILES['logo']['name'] . '</td></tr>';
           exit;
        }
      }
	 }
     $sql="INSERT INTO org_info (name, address, email, phone, 
           web, logo) VALUES('{$_REQUEST['n']}', '{$_REQUEST['a']}', 
           '{$_REQUEST['em']}', '{$_REQUEST['p']}', '{$_REQUEST['w']}', 
           '{$_FILES['logo']['name']}')";
     mysql_query($sql, $con);
	}
  }
}

$result = mysql_query("SELECT * FROM org_info", $con);
$row = mysql_fetch_array($result);
?>
  <table>
   <tr class='class1'>
       <td colspan="4">
        <form enctype="multipart/form-data" 
          action="org_info.php" method="post">
       </td>
      </tr>
      <!--
      <tr>
       <td>Logo Image</td>
       <td>
       <img src='upload/<?php echo $row['logo'];?>' 
        width='100' height='100'></td>
      </tr>
      -->
      <tr>
       <td>Name</td>
       <td><input type="text" name="n" size="50" value="<?=$row['name']?>"></td>
      </tr>
      <tr>
       <td>Address</td>
       <td><textarea rows="5" cols="50" name="a"><?=$row['address']?></textarea></td>
      </tr>
      <tr>
       <td>Phone</td>
        <td><input type="text" name="p" size="50" value="<?=$row['phone']?>">
        </td>
      </tr>
      <tr>
       <td>Email</td>
       <td><input type="text" name="em" size="50" value="<?=$row['email']?>">
       </td>
      </tr>
      <tr>
       <td>Website</td>
       <td><input type="text" name="w" size="50" value="<?=$row['web']?>"></td>
      </tr>
      <!--
      <tr>
       <td>Logo</td>
       <td><input type="file" name="logo"></td>
      </tr>
      -->
      <tr>
       <td><input type="submit" name="action" value="Update"></td>
      </tr>
     </form>
    </table>
<?php
  main_footer();
?>
