<?php

include_once($_SERVER['DOCUMENT_ROOT']."/lib/user.php");

$_AUTH = array("TRANSICTION METHOD" => AUTH_USE_COOKIE);

function auth_set_option($opt_name, $opt_value){
  global $_AUTH;

  $_AUTH[$opt_name] = $opt_value;
}

function auth_get_option($opt_name){
  global $_AUTH;

  return is_null($_AUTH[$opt_name]) ? NULL : $_AUTH[$opt_name];
}

function auth_clean_expired(){
  global $_CONFIG;

  $result = auth_get_uid() ? get_query_db("SELECT created_at FROM ".$_CONFIG['table_sessions']." WHERE uid='".auth_get_uid()."'") : false;
  if($result){
    $data = $result[0];
    if($data['created_at'] && (($data['created_at'] + $_CONFIG['expire']) <= time())){
      switch(auth_get_option("TRANSICTION METHOD")){
        case AUTH_USE_COOKIE:
          setcookie("uid", "", time()-36000, "/");
        break;
        case AUTH_USE_LINK:
          global $_GET;
          $_GET['uid'] = NULL;
        break;
      }
      header("Location: ".$_CONFIG['site_path']."login");
    } else {
      $isConnect = AUTH_LOGGED;
      setcookie("uid", auth_get_uid(), time()+3600*365,"/");
      query_db("UPDATE ".$_CONFIG['table_sessions']." SET created_at = '".time()."' WHERE uid = '".auth_get_uid()."'");
    }
  }
  query_db("DELETE FROM ".$_CONFIG['table_sessions']." WHERE expired + ".$_CONFIG['expire']." <= ".time());
  if(!$result && !in_array($_SERVER['SCRIPT_NAME'], $_CONFIG['skip_check_if_logged'])){
    header("Location: ".$_CONFIG['site_path']."login");
  }
}

function auth_get_uid(){

  $uid = NULL;

  switch(auth_get_option("TRANSICTION METHOD")){
    case AUTH_USE_COOKIE:
      global $_COOKIE;
      if(isset($_COOKIE['uid'])) $uid = $_COOKIE['uid'];
    break;
    case AUTH_USE_LINK:
      global $_GET;
      if(isset($_GET['uid'])) $uid = $_GET['uid'];
    break;
  }

  return $uid ? $uid : NULL;
}

function auth_get_status(){
  global $_CONFIG;

  auth_clean_expired();
  $uid = auth_get_uid();
  if(is_null($uid))
    return array(AUTH_NOT_LOGGED, NULL);

  $result = get_query_db("SELECT U.* FROM {$_CONFIG['table_sessions']} S, {$_CONFIG['table_users']} U WHERE S.user_id = U.id AND S.uid = '{$uid}' AND S.expired='0'");

  if(count($result) != 1)
    return array(AUTH_NOT_LOGGED, NULL);
  else {
    $u = get_user($result[0]["id"]);
    return array(AUTH_LOGGED, array_merge($u, array('uid' => $uid)));
  }
}

function auth_login($email, $passw){
  global $_CONFIG;
  $email = mysql_real_escape_string($email);
  $passw = mysql_real_escape_string($passw);

  $crypt_pass = crypt(MD5($passw),'$5$hYCcrK$');
  $result = get_query_db("SELECT * FROM ".$_CONFIG['table_users']." WHERE email='{$email}' AND password='".$crypt_pass."' AND temp = '0'");

  if(count($result) != 1){
    return array(AUTH_INVALID_PARAMS, NULL);
  } else {
    $data = $result[0];
    // Incremento il numero di login giornalieri
    $now = date("Y-m-d H:i:s");
    $today = date("Y-m-d");
    $count_user_login_option = get_query_db("SELECT * FROM {$_CONFIG['table_option_types']} WHERE name = 'count_user_login' LIMIT 1");
    $count_user_login_option_id = $count_user_login_option[0]["id"];
    $today_option = get_query_db("SELECT * FROM {$_CONFIG['table_options']} WHERE DATE(created_at) = '{$today}' AND option_type_id = {$count_user_login_option_id} LIMIT 1");
    if($today_option && isset($today_option[0])){
      $count = intval($today_option[0]["option_value"])+1;
      query_db("UPDATE {$_CONFIG['table_options']} SET option_value = '{$count}', updated_at = '{$now}' WHERE DATE(created_at) = '{$today}' AND option_type_id = {$count_user_login_option_id}");
    } else {
      query_db("INSERT INTO {$_CONFIG['table_options']} (option_type_id, option_value, updated_at, created_at) VALUES ('{$count_user_login_option_id}', '1', '{$now}', '{$now}')");
    }
    return array(AUTH_LOGEDD_IN, $data);
  }
}

function auth_generate_uid(){
  list($usec, $sec) = explode(' ', microtime());
  mt_srand((float) $sec + ((float) $usec * 100000));
  return md5(uniqid(mt_rand(), true));
}

function auth_register_session($udata){
  global $_CONFIG;

  $uid = auth_generate_uid();

  query_db("INSERT INTO ".$_CONFIG['table_sessions']." (uid, user_id, created_at) VALUES ('".$uid."', '".$udata['id']."', ".time().")");
  if(mysql_insert_id()){
    return array(AUTH_LOGEDD_IN, $uid);
  } else {
    return array(AUTH_FAILED, NULL);
  }
}

function auth_logout(){
  global $_CONFIG;

  $uid = auth_get_uid();

  if(is_null($uid)){
    return false;
  } else {
    get_query_db("DELETE FROM ".$_CONFIG['table_sessions']." WHERE uid = '".$uid."'");
    switch(auth_get_option("TRANSICTION METHOD")){
      case AUTH_USE_COOKIE:
        setcookie('uid','',time()-36000,"/");
        break;
      case AUTH_USE_LINK:
        global $_GET;
        $_GET['uid'] = NULL;
        break;
    }
    return true;
  }
}
