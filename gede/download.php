<?php
header('Content-type: application/pdf');

if (isset($_REQUEST['file'])) {
  header("Content-Disposition: attachment; filename=\"{$_REQUEST['file']}\"");
  readfile("{$_REQUEST['file']}");
}
?>
