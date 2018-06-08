<?php
$dr = isset($_SERVER['CONTEXT_DOCUMENT_ROOT']) ? $_SERVER['CONTEXT_DOCUMENT_ROOT'] : $_SERVER['DOCUMENT_ROOT'];
include_once($dr."/lib/config.php");

function reg_register($data){
  //registro l'utente
  global $_CONFIG, $isLocal;
  $email_control_user = query_db("SELECT email FROM ".$_CONFIG['table_users']." WHERE email = '$_POST[email]'");
  if(mysql_num_rows($email_control_user)){
    echo "L'Email è già utilizzata";
  } else {
    if(isset($_POST['email']) && isset($_POST['password'])){
      $id = reg_get_unique_id();
      $crypt_pass = crypt(MD5($_POST["password"]),'$5$hYCcrK$');
      $now = date("Y-m-d H:i:s");
      $default_avatar = $_CONFIG["site_path"]."images/avatar-default.jpg";
      query_db("INSERT INTO ".$_CONFIG['table_users']." (email, password, temp, uid, updated_at, created_at, avatar)
      VALUES
      ('$_POST[email]','$crypt_pass','1', '$id', '$now', '$now', '$default_avatar')");
      if($user_id = mysql_insert_id()){
        if($isLocal){
          echo "OK";
        } else {
          echo "OK";
          // echo reg_send_confirmation_mail($data['email'], $_CONFIG['email_sender'], $id, "user");
        }
      }
      else echo REG_FAILED;
    }
    else echo REG_FAILED;
  }
}

function reg_clean_expired(){
  global $_CONFIG;
  $query = query_db("DELETE FROM ".$_CONFIG['table_users']." WHERE (regdate + ".($_CONFIG['regexpire'] * 60 * 60).") <= ".time()." and temp='1'");
}

function reg_get_unique_id(){
  //restituisce un ID univoco per gestire la registrazione
  list($usec, $sec) = explode(' ', microtime());
  mt_srand((float) $sec + ((float) $usec * 100000));
  return md5(uniqid(mt_rand(), true));
}

function reg_check_data(&$data, $type){
  global $_CONFIG;

  $errors = array();

  foreach($data as $field_name => $value){
    $func = null;
    if($type=="user")
      if(array_key_exists($field_name,$_CONFIG['check_table_user'])) $func = $_CONFIG['check_table_user'][$field_name];
    elseif($type=="update_user")
      if(array_key_exists($field_name,$_CONFIG['check_update_table_user'])) $func = $_CONFIG['check_update_table_user'][$field_name];
    if(!is_null($func)){
      $ret = $func($value);
      if($ret !== true)
        $errors[] = array($field_name, $ret);
    }
  }

  return count($errors) > 0 ? $errors : true;
}

function reg_confirm($uid){
  global $_CONFIG;
  $exist = false;
  $already_exists = false;

  $user = array();
  $user_exists = get_user($uid, true, true);
  if($user_exists){
    $already_exists = true;
  } else {
    query_db("UPDATE ".$_CONFIG['table_users']." SET temp='0' WHERE uid='".$uid."'");
    $user = get_user($uid, true);
    if($user){
      $exist = true;
    }
  }

  if($already_exists){
    return REG_ALREADY_EXISTS;
  } else {
    if(mysql_affected_rows() != 0 && $exist){
      // reg_send_mail_ok($user["email"]);
      return REG_SUCCESS;
    } else {
      return REG_FAILED;
    }
  }
}

function email_type($email){
  global $_CONFIG;
  $user = get_query_db("SELECT id FROM ".$_CONFIG["table_users"]." WHERE email = '".$email."' LIMIT 1");
  if(count($user)==1)
    return $_CONFIG["table_users"];
  else
    return false;
}

function password_rescue($email){
  global $_CONFIG;
  $len = 16;
  $base = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
  $max = strlen($base)-1;
  $new_pass = '';
  mt_srand((double)microtime()*1000000);
  while(strlen($new_pass)<$len+1)
  $new_pass.=$base{mt_rand(0,$max)};
  $crypt_pass_new = crypt(MD5($new_pass),'$5$hYCcrK$');
  $table = email_type($email);
  if($table){
    query_db("UPDATE ".$table." SET password = '".$crypt_pass_new."' WHERE email = '".$email."'");
    if(!mysql_error()){
      // change_password_send_mail($email, $_CONFIG['email_sender2'], $new_pass);
      return true;
    }
    else
      return false;
  } else return false;
}
