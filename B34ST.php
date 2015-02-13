<?php

function DisableAntiFlood() {
return false; //Default: false
}

function DisableBlacklist() {
return false; //Default: false
}

function blacklist_path() {
// Blacklist Location
return $blacklist_url = "http://b34st-Security.tk/database/ip-blacklist.txt"; //Default: http://b34st-Security.tk/database/ip-blacklist.txt
}

function forbidden_path() {
//Forbidden page location
return $forbidden_url = 'http://b34st-Security.tk/gateway/forbidden.html'; //Default: http://b34st-Security.tk/gateway/ip-blacklist.txt
}

function blacklist() {
$blacklist_url = blacklist_path();
$forbidden_url = forbidden_path();

$file = fopen($blacklist_url,"r");
while(! feof($file))
  {
$raddr = $_SERVER['REMOTE_ADDR'];
  if ($raddr == fgets($file)) {
   exit('<script>location.replace("' . $forbidden_url . '");</script>');
}
  }
fclose($file);
}

function antiflood () {
//Anti-flood Settings
$forbidden_url = forbidden_path();
$session_directory = 'session'; //Default: session
$max_connections = 180; //Default: 180
$connection_interval = 60; //Default: 60
$remote_ip = $_SERVER['REMOTE_ADDR'];
if (!file_exists($session_directory . '/')) {
mkdir($session_directory . '/');
//Session Directory Created
}
if (!file_exists($session_directory . '/' . $remote_ip)) {
mkdir(session_directory . '/' . $remote_ip);
//User Session Directory Created
}
//Last time
if (file_exists($session_directory . '/' . $remote_ip . '/lock.tmp')) {
//read last time file
$LockFile = fopen($session_directory . '/' . $remote_ip . '/lock.tmp', "r");
$LastTime = fgets($LockFile);
//resets visit count if time exceeded
if ($LastTime + $connection_interval <= time()) {
unlink($session_directory . '/' . $remote_ip . '/lock.tmp');
unlink($session_directory . '/' . $remote_ip . '/visits.tmp');
}
fclose($LockFile);
}
else {
//write last time file
$LockFile = fopen($session_directory . '/' . $remote_ip . '/lock.tmp', "w");
fwrite($LockFile, time());
$LastTime = time();
fclose($LockFile);
}
//Visit amount
if (file_exists($session_directory . '/' . $remote_ip . '/visits.tmp')) {
//read count
$VisitFile = fopen($session_directory . '/' . $remote_ip . '/visits.tmp', "r");
$Amount = fgets($VisitFile);
fclose($VisitFile);
//add to count
$VisitFile = fopen($session_directory . '/' . $remote_ip . '/visits.tmp', "w");
$Amount++;
fwrite($VisitFile,$Amount);
fclose($VisitFile);
if ($Amount >= $max_connections) {
exit('<script>location.replace("' . $forbidden_url . '");</script>');
}
}
else {
//write count file
$VisitFile = fopen($session_directory . '/' . $remote_ip . '/visits.tmp', "w");
fwrite($VisitFile, 1);
$Amount = 1;
fclose($VisitFile);
}
}
if (DisableBlacklist() == false) {
blacklist();
}
if (DisableAntiFlood() == false) {
antiflood();
}
?>
