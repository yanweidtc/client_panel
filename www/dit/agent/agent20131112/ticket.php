<?php
// Chris 2013-08-02
include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {
    if(isset($_POST["comment"]) && isset($_POST["subject"])){

	$user_id = $_SESSION['user_id'];
        $stmt = new DB_Sql;
        if ($stmt->query("SELECT username, email from agents where id = '$user_id'")) {
                $stmt->next_record();
                $name = $stmt->f("username");
		$email = $stmt->f("email");
	}else{
		echo "Invalid info!";
        	exit;
        }

	$contents=$_POST["comment"];
	$subject=$_POST["subject"];
		$contents = str_replace("\n", "\r\n", $contents);
		$contents = str_replace("\n.", "\n..", $contents);
		$date = date('Y-m-d H:i:s');

		$to = "agents@zazeen.com";
		$subject = $subject."  by AGENT: ".$name." -- ".$date;
		$message = $contents;
		$from = $email;
		$headers = "From:" . $from;
		if(mail($to,$subject,$message,$headers)){}

		header('Location: ./main.php?sent=Y');
    }else{
	echo "Invalid info!";
        header('Location: ./main.php?sent=N');
	exit;	
    }

}else{
	echo  'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}

?>

