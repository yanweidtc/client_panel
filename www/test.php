<?php
// Zazeen blilling server
        $ip=getenv ("REMOTE_ADDR");
        //66.49.254.56.
        if ($ip != "66.49.254.56")
        {
          print "<b><i>Forbidden</i></b>";
          exit;
        }



include 'db_connect.php';
//Chris 2014-01-03
if (isset($_POST["reply"]))
{
        print "here\n";
//          print "ID=".$_POST["id"]."\n";
          print "REPLY=".$_POST["reply"]."\n";

	if ($insert_pkg_stmt = $mysqli->prepare("INSERT INTO admins (username,email,password,salt,type) VALUES (1,?,1,1,1)")) {
	   $insert_pkg_stmt->bind_param('s', $_POST["reply"]);
	   $insert_pkg_stmt->execute();
	}

	

}else{
}
?>
