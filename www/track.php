<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
        <title>Track History</title>
</head>

<body  background='background.bmp'>

<?php
        $trid = $_GET['trid'];
if(substr(trim($trid),0,4) == "2001"){
        print '<legend> Your Canada Post Tracking Number for the STB is : '.$_GET["trid"].'</legend>';
}else if(substr(trim($trid),0,3) == "798"){
        print '<legend> Your Fedex Tracking Number for the STB is : '.$_GET["trid"].'</legend>';
}else{
        print '<legend> Invalid Tracking Number!</legend>';
        exit;
}
        $trides = urlencode($trid);
        $content = `curl -m 40 -ks https://www.zazeen.com/tracktools/track.php?trid='$trides'`;
        print $content;

?>

</body>
</html>
