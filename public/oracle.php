<?php
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ALL);


/* funciona 
$conn = oci_connect('EUREKA', 'EUREKA', 'localhost:51521/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, 'SELECT * FROM il3_usuario');
oci_execute($stid);

$row = oci_fetch_assoc($stid);
var_dump($row);
*/

$conn = odbc_connect('EUREKA','EUREKA','EUREKA');
if (!$conn)  {
	exit("Connection Failed: " . $conn);
}
$sql = "SELECT * FROM il3_usuario";
$rs  = odbc_exec($conn,$sql);
if (!$rs) {
   exit("Error in SQL");
}
$reg = odbc_fetch_object($rs);
var_dump($reg);
odbc_close($conn);
