<?php

$db_name = "braune-db";
$dbhost = "oniddb.cws.oregonstate.edu";
$dbuser = "braune-db";
$dbpass = "GxGW1nC0BStHXQcB";
$rowname = "filedata";

$id = $_GET['id'];
// do some validation here to ensure id is safe

$link = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($db_name);
$sql = "SELECT filedata FROM uploads WHERE fid='".$id."'"; 
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
mysql_close($link);

header("Content-type: image/jpeg");
echo $row[$rowname];

?>