<?php
require_once 'Dao/DataBaseConnection.php';
$DataBaseConnection = new DataBaseConnection();
$db_connection = $DataBaseConnection->getConnection();

$tz = 'Europe/Athens';
$timestamp = time();
$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
$dt->setTimestamp($timestamp); //adjust the object to correct timestamp
$time= $dt->format('d.m.Y, H:i:s');


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
}
//whether ip is from proxy
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
//whether ip is from remote address
else {
    $ip_address = $_SERVER['REMOTE_ADDR'];
}

$query = "insert ignore request_ips (ip, time_stamp) values ('".$ip_address."','".$time."')";
if (!($result = @$db_connection->query($query))) {
  //
}

header("Location:voteParty.php");
?>


