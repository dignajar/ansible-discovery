<?php

// Domain
define('DOMAIN', 'your-domain.com');

// Where store the JSON files
define('JSON_DIR', '/www/hosts/');

// Set timezone to UTC
date_default_timezone_set('UTC');

// Available operating system
$availableOS = array(
  'ubuntu',
  'centos',
  'redhat',
  'other'
);

// Check method POST and variables
if( !isset($_POST['hostname']) || !isset($_POST['os']) ) {
  exit(json_encode(array('status'=>1, 'message'=>'Failed when try to detect the hostname and os variables.')));
}

// HOSTNAME
// -----------------------------------------------------------------------------
$hostname = htmlspecialchars($_POST['hostname'], ENT_COMPAT|ENT_HTML5, 'UTF-8');
$hostname = trim($hostname);

$eHostname = explode(DOMAIN, $hostname);

if( empty($eHostname[0]) || !isset($eHostname[1]) ) {
  exit(json_encode(array('status'=>1, 'message'=>'Failed when try to detect the Hostname.')));
}

// OS
// -----------------------------------------------------------------------------
$os = htmlspecialchars($_POST['os'], ENT_COMPAT|ENT_HTML5, 'UTF-8');
$os = trim($os);

if(empty($os)) {
    exit(json_encode(array('status'=>1, 'message'=>'Failed when try to detect the OS.')));
}

// Current time
// -----------------------------------------------------------------------------
$date = new DateTime();
$currentDate = $date->format('Y-m-d H:i:s');

// Update JSON file
// -----------------------------------------------------------------------------

// $data = array('hostname'=>'', 'os'=>'', 'time'=>'');
$filename = JSON_DIR.$eHostname[0].'.'.DOMAIN.'.json';

// Recovery data from the JSON file
if(file_exists($filename)) {
  $data = json_decode(file_get_contents($filename), true);
} else {
  // Hostname
  $data['hostname'] = $explodeHostname[0].'.'.DOMAIN;
}

// OS
$data['os'] = $os;

// Update date
$data['time'] = $currentDate;

// Save JSON file. Filename: hostname.domain.json
if( file_put_contents($filename, json_encode($data), LOCK_EX) ) {
  exit(json_encode(array('status'=>0, 'message'=>'Successfully.')));
}

exit(json_encode(array('status'=>1, 'message'=>'Failed to save the JSON file.')));
