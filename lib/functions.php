<?php

$con = mysql_connect($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass']);
mysql_select_db($_CONFIG['dbname'], $con);
mysql_set_charset("utf8", $con);

function query_db($query=null, $print_log="log"){
  if($print_log=="log") write_log($query);
  return @mysql_query($query);
}

function get_query_db($query=null, $print_log="log"){
  $res = query_db($query, $print_log);
  $myrows = array();
  while($row=@mysql_fetch_array($res,MYSQL_ASSOC)) $myrows[]=$row;
  if(mysql_error()) echo mysql_error();
  return $myrows;
}

if(!function_exists('is_xhr')){
  function is_xhr(){
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return true;
    return false;
  }
}

function array_multisort_gen(&$array, $search_key, $type="time", $sort="DESC"){
  $ord = array();
  foreach($array as $key => $value){
    $ord[] = $type == "time" ? strtotime($value[$search_key]) : $value[$search_key];
  }
  array_multisort($ord, ($sort == "DESC" ? SORT_DESC : SORT_ASC), $array);
  return $array;
}

function array_search_multidimensional($arr, $col, $find, $return_search_object=false){
  $return = false;
  foreach($arr as $index => $a){
    if($a[$col] == $find){
      if($return_search_object)
        $return = $arr[$index];
      else
        $return = $index;
      break;
    }
  }
  return $return;
}

function createDateRangeArray($strDateFrom, $strDateTo, $dateFormat='Y-m-d'){
  $aryRange = array();
  $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
  $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
  if($iDateTo>=$iDateFrom){
    array_push($aryRange, date($dateFormat, $iDateFrom));
    while($iDateFrom<$iDateTo){
      $iDateFrom += 86400;
      array_push($aryRange, date($dateFormat, $iDateFrom));
    }
  }
  return $aryRange;
}