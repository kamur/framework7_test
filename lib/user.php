<?php

function get_user($id, $is_uid=false, $return_if_confirmed=false){
  global $_CONFIG;
  $where = $is_uid ? "uid" : "id";
  $result = get_query_db("SELECT * FROM {$_CONFIG['table_users']} WHERE {$where}='{$id}'");
  if(isset($result[0])){
    $u = $result[0];
    $u["full_name"] = "{$u["nome"]} {$u["cognome"]}";
    if($u["avatar"] == "" || $u["avatar"] == $_CONFIG["site_path"]."images/avatar-default.jpg"){
      $avatar = get_query_db("SELECT file_name FROM {$_CONFIG["table_multimedia"]} WHERE user_id = {$u["id"]} AND avatar = 1 AND deleted = 0 LIMIT 1");
      if($avatar){
        $u["avatar"] = $_CONFIG["site_path"]."multimedia/images/avatar/user_".$u["id"]."/".$avatar[0]["file_name"];
      } else {
        $u["avatar"] = $_CONFIG["site_path"]."images/avatar-default.jpg";
      }
      query_db("
        UPDATE ".$_CONFIG['table_users']."
        SET avatar = '".$u["avatar"]."'
        WHERE id = ".$u["id"]."
      ");
    }
    if($return_if_confirmed){
      return $u["temp"]==0;
    } else {
      return $u;
    }
  }
  return false;
}

function get_user_by_email($email){
  global $_CONFIG;
  $result = get_query_db("SELECT * FROM {$_CONFIG['table_users']} WHERE email='{$email}'");
  if($result){
    return $result[0];
  } else {
    return false;
  }
}

function update_user(){
  global $_CONFIG;
  $currentUser = get_user($_POST["id"]);

  if($_POST['password']) $password = crypt(MD5($_POST['password']),'$5$hYCcrK$');
  else $password = $currentUser['password'];

  $update_fields = array();
  if($password != $currentUser['password']) array_push($update_fields, 'password = "'.$password.'"');
  if($currentUser['temp'] != 0) array_push($update_fields, 'temp = 0');
  if(count($update_fields) > 0){
    array_push($update_fields, 'updated_at = "'.date("Y-m-d H:i:s").'"');
    $query = 'UPDATE '.$_CONFIG["table_users"].' '.implode(', ', $update_fields).' WHERE id="'.$_POST["id"].'"';
    query_db($query);
    if(!mysql_error())
      echo "OK";
    else
      echo mysql_error();
  } else {
    echo 'OK';
  }
}
