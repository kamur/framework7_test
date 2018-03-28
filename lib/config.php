<?php
  error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
  ini_set('display_errors', '1');
  setlocale(LC_ALL, 'it_IT', 'ita');
  date_default_timezone_set('Europe/Rome');
  header('Content-Type: text/html; charset=utf-8');

  global $isLocal;
  $isLocal = $_SERVER['SERVER_ADDR']=="127.0.0.1" ? true : false;

  $_CONFIG['host'] = "localhost";
  $_CONFIG['user'] = "test_fw7";
  $_CONFIG['pass'] = "9q%j7/NgXLUgnqnP";
  $_CONFIG['dbname'] = "test_fw7";
  $_CONFIG['charset'] = "UTF-8";
  $_CONFIG['site_path'] = "http://{$_SERVER['HTTP_HOST']}/";
  $_CONFIG['path_images'] = "/images/";

  $_CONFIG['email_sender'] = 'mail@gmail.com';

  $_CONFIG['table_options'] = "options";
  $_CONFIG['table_option_types'] = "option_types";
  $_CONFIG['table_sessions'] = "sessions";
  $_CONFIG['table_users'] = "users";

  $_CONFIG['expire'] = 1200; //20 minuti in secondi
  $_CONFIG['regexpire'] = 24; //24 ore

  $_CONFIG['skip_check_if_logged'] = array('/actions/login.php', '/actions/register.php');

  $_CONFIG['check_table_user'] = array(
    "email"=>"check_global",
    "password"=>"check_global"
  );

  $_CONFIG['check_update_table_user'] = array(
    "email"=>"check_global"
  );

  function check_global($value){
    global $_CONFIG;

    $value = trim($value);
    if($value == "")
      return "Il campo non può essere lasciato vuoto";

    return true;
  }


  //--------------
  define('AUTH_LOGGED', 99);
  define('AUTH_NOT_LOGGED', 100);

  define('AUTH_USE_COOKIE', 101);
  define('AUTH_USE_SESSION', 102);
  define('AUTH_USE_LINK', 103);
  define('AUTH_INVALID_PARAMS', 104);
  define('AUTH_LOGEDD_IN', 105);
  define('AUTH_FAILED', 106);

  define('REG_ERRORS', 107);
  define('REG_SUCCESS', 108);
  define('REG_FAILED', 109);
  define('REG_ALREADY_EXISTS', 120);

  define('AUTH_UPDATE_FAILED', 118);
  define('ONP_UPDATE_FAILED', 119);

  // Filename of log to use when none is given to write_log
  $file_log = $isLocal ? $_SERVER["DOCUMENT_ROOT"]."/logs/development.log" : $_SERVER["DOCUMENT_ROOT"]."/logs/production.log";
  define("DEFAULT_LOG", $file_log);

  /**
  * Parameters:
  *  $message:   Message to be logged
  *  $logfile:   Path of log file to write to.  Optional.  Default is DEFAULT_LOG.
  *
  * Returns array:
  *  $result[status]:   True on success, false on failure
  *  $result[message]:  Error message
  */
  function write_log($message, $logfile=''){
    global $_CONFIG, $isLocal, $user, $original_user;
    $user_id = null;
    $onp_id = null;
    $print_ref = "";
    if(isset($user) && isset($user["id"])){
      $print_ref = "\r\n    User: {$user["id"]} - {$user["email"]}";
    }
    $message = "        ".trim($message);
    if($logfile == '') {
      if(defined("DEFAULT_LOG") == TRUE)
        $logfile = DEFAULT_LOG;
      else {
        if($isLocal)
          error_log("No log file defined!");
        else
          error_log('No log file defined!', 1, "gennaro.ciotola@donordonee.eu", "From: ".$_CONFIG['email_sender2']);
        return array("status" => false, "message" => 'No log file defined!');
      }
    }
    if(($time = $_SERVER['REQUEST_TIME']) == '')
      $time = time();
    if(preg_match("/production.log$/", $logfile)){
      $logfile = $_SERVER["DOCUMENT_ROOT"]."/logs/production-".date("d-m-Y", $time).".log";
    } else if(preg_match("/development.log$/", $logfile)){
      $logfile = $_SERVER["DOCUMENT_ROOT"]."/logs/development-".date("d-m-Y", $time).".log";
    }
    if(($remote_addr = $_SERVER['REMOTE_ADDR']) == '')
      $remote_addr = "REMOTE_ADDR_UNKNOWN";
    if(($request_uri = $_SERVER['REQUEST_URI']) == '')
      $request_uri = "REQUEST_URI_UNKNOWN";
    $params = "[params: {";
    if(!is_array($_REQUEST)) $_REQUEST = array();
    $count = 1;
    $tot = count($_REQUEST);
    foreach($_REQUEST as $key=>$val){
      $params .= "{$key}: {$val}";
      if($count < $tot) $params .= ", ";
      $count ++;
    }
    $params .= "}]";
    $date = date("D d M H:i:s Y", $time);
    if($fd = fopen($logfile, "a")){
      $result = fwrite($fd, "[{$date}] [client: {$remote_addr}], [location: {$request_uri}] {$params}{$print_ref}\r\n$message\r\n\r\n");
      fclose($fd);
      if($result > 0)
        return array("status" => true);
      else
        return array("status" => false, "message" => 'Unable to write to '.$logfile.'!');
    } else {
      return array("status" => false, "message" => 'Unable to open log '.$logfile.'!');
    }
  }

  include_once($_SERVER['DOCUMENT_ROOT']."/lib/functions.php");
