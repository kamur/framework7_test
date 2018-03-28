<?php
include_once($_SERVER['DOCUMENT_ROOT']."/lib/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/lib/auth.lib.php");

list($status, $user) = auth_get_status();

if(isset($_POST['email']) && isset($_POST['password'])){
  $email = strtolower(trim($_POST['email']));
  $password = trim($_POST['password']);

  if($status == AUTH_NOT_LOGGED){
    if($email == "" or $password == ""){
      $status = AUTH_INVALID_PARAMS;
    } else {
      list($status, $user) = auth_login($email, $password);
      if(!is_null($user)){
        list($status, $uid) = auth_register_session($user);
      }
    }
  }

  switch($status){
    case AUTH_LOGGED:
      echo "index"; //già connesso
    break;
    case AUTH_INVALID_PARAMS:
      echo "error"; //dati non corretti
    break;
    case AUTH_LOGEDD_IN:
      switch(auth_get_option("TRANSICTION METHOD")){
        case AUTH_USE_LINK:
          echo "index?uid=".$uid;
        break;
        case AUTH_USE_COOKIE:
          setcookie('uid', $uid, time()+3600*365,"/");
          echo "index§".$uid;
        break;
        case AUTH_USE_SESSION:
          $_SESSION['uid'] = $uid;
          echo "index";
        break;
      }
    break;
    case AUTH_FAILED:
      echo "error2"; //errore di connessione
    break;
  }

} else {
  echo "error";
}