<?php
//Chris 2014-01-16 function to calculate diff in month
function mon_diff($date1, $date2) {     //format: 2014-01   -    2013-12
  $date1r = explode("-",$date1);
  $date2r = explode("-",$date2);

  if($date1r[0] == $date2r[0]){
        return formatNum($date1r[1]-$date2r[1]);
  }else{
        return formatNum(($date1r[0]-$date2r[0])*12 + ($date1r[1]-$date2r[1]));
  }

}

//print mon_diff("2014-12","2014-01-21")."\n";


function mon_diff2($date1, $date2) {     //format: 2014-01   -    2013-12
  $date1r = explode("-",$date1);
  $date2r = explode("-",$date2);

  if($date1r[0] == $date2r[0]){
        return formatNum($date1r[1]-$date2r[1]);
  }else{
        return formatNum(($date1r[0]-$date2r[0])*12 + ($date1r[1]-$date2r[1]) - 1);
  }

}



?>
