<?php header('Content-Type: application/json');

// Where are stored the JSON files
define('JSON_DIR', '/www/hosts/');

// Check method POST and variables
if( !isset($_POST['hostname']) || !isset($_POST['os']) ) {
  exit(json_encode(array('status'=>1, 'message'=>'Failed when try to detect the hostname and os variables.')));
}

// HOSTNAME
// -----------------------------------------------------------------------------
$hostname = htmlspecialchars($_POST['hostname'], ENT_COMPAT|ENT_HTML5, 'UTF-8');
$hostname = trim($hostname);
$hostname = mb_strtolower($hostname, 'UTF-8');

if(empty($hostname)) {
    exit(json_encode(array('status'=>1, 'message'=>'Failed when try to detect the hostname.')));
}

// OS
// -----------------------------------------------------------------------------
$os = htmlspecialchars($_POST['os'], ENT_COMPAT|ENT_HTML5, 'UTF-8');
$os = trim($os);
$os = mb_strtolower($os, 'UTF-8');

if(empty($os)) {
    exit(json_encode(array('status'=>1, 'message'=>'Failed when try to detect the OS.')));
}

// Current time in UTC
// -----------------------------------------------------------------------------
date_default_timezone_set('UTC');
$date = new DateTime();
$currentDate = $date->format('Y-m-d H:i:s');

// Update JSON file
// -----------------------------------------------------------------------------

// $data = array('hostname'=>'', 'os'=>'', 'time'=>'');
$filename = JSON_DIR.$hostname.'.json';

// Recovery data from the JSON file
if(file_exists($filename)) {
  $data = json_decode(file_get_contents($filename), true);
} else {
  // Hostname
  $data['hostname'] = $hostname;
}

// OS
$data['os'] = $os;

// Update date
$data['time'] = $currentDate;

// Save JSON file. Filename: hostname.json
if( file_put_contents($filename, json_encode($data), LOCK_EX) ) {
  exit(json_encode(array('status'=>0, 'message'=>'Hostname updated: '.$hostname)));
}

exit(json_encode(array('status'=>1, 'message'=>'Failed to save the JSON file.')));
