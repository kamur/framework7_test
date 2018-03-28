<?php
  include_once($_SERVER['DOCUMENT_ROOT']."/lib/config.php");
  include_once($_SERVER['DOCUMENT_ROOT']."/lib/reg.lib.php");
  if(reg_check_data($_POST, "user")){
    reg_register($_POST);
  }