<?php
/**
* Workaround for PHP < 5.3.0
* Chris 2013-11-14
*/
// Chris 2014-01-07 data_diff workaround for php<5.1.6
function formatNum($num){
    return sprintf("%+d",$num);
}

function date_difference($date1, $date2) {
  return formatNum(round((strtotime($date2)-strtotime($date1))/60/60/24));
}

function date_difference2($startdate, $now, $onetimeday, $dayslater) {
  $flag = strtotime ( '+'.$onetimeday.' day' , strtotime ( $startdate ) ) ;
  $flagdate = date( 'Y-m-j' , $flag );

  $laterflag = strtotime ( '+'.$dayslater.' day' , strtotime ( $now ) ) ;
  $laterflagdate = date( 'Y-m-j' , $laterflag );

  //print $startdate." + ".$onetimeday." = ".$flagdate."\n";
  //print $now." + ".$dayslater." = ".$laterflagdate."\n";

  //print $flagdate." - ".$laterflagdate." = ".date_difference($laterflagdate,$flagdate)."\n\n";
  return date_difference($flagdate,$laterflagdate);
  //return round((strtotime($date2)-strtotime($date1))/60/60/24);
}


?>
