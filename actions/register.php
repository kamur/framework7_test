<?php
  $dr = isset($_SERVER['CONTEXT_DOCUMENT_ROOT']) ? $_SERVER['CONTEXT_DOCUMENT_ROOT'] : $_SERVER['DOCUMENT_ROOT'];
  include_once($dr."/lib/config.php");
  include_once($dr."/lib/reg.lib.php");
  if(reg_check_data($_POST, "user")){
    reg_register($_POST);
  }