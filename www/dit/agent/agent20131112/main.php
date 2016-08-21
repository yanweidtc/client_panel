<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {
   global $n;

        $user_id = $_SESSION['user_id'];
	$stmt = new DB_Sql;
	if ($stmt->query("SELECT id, type, username, agentID, monthlyfee, onetimeRFee, activeaccounts, addonfee, addonpackages, pendinginvoice, update_time, mainAgent from agents where id = '$user_id'")) {
		$stmt->next_record();
		$monthfee = $stmt->f("monthlyfee");
		$activeaccount = $stmt->f("activeaccounts");
		$addonfee = $stmt->f("addonfee");
		$addonpackages = $stmt->f("addonpackages");
		$pdis = $stmt->f("pendinginvoice");
		$update_time = $stmt->f("update_time");
		$onetimeRF = $stmt->f("onetimeRFee");
		$agent_id = $stmt->f("agentID");
		$uname = $stmt->f("username");
		$utype = $stmt->f("type");
		$magent = $stmt->f("mainAgent");

   echo page_head(true,true,$uname); 
      echo '<script type="text/javascript">
         var break_link=true;
         </script>';


   if($utype == "main"){
   	echo        '        <div class="pull-right">'.$n;
        echo        '          <a class="btn btn-primary" href="user.php">Manage Sub-accounts</a>'.$n;
	echo        '          <a class="btn btn-primary" href="register.php">Add Sub-accounts</a>'.$n;
	echo        '        </div>'.$n.
           	    '        <div class="clearfix"></div>'.$n;
   


        // List details of this agent
             echo ' <table class="table table-hover">
                      <caption><h3>Your Revenue Details</h3></br><h4 style="color:gray">Last update on: '.$update_time.'</h4></br></caption>
                    <thead>
                      <tr>
                        <th>One-time Referral Fee</th>
                        <th>Referral Monthly Fee</th>
                        <th>Add-on Referral Fee</th>
                        <th>Active Accounts</th>
			<th>Add-on Revenue</th>
                        <th>Pending Invoices</th>
                        <th>Total Revenue</th>
                      </tr>
                    </thead>
                    <tbody>';

                echo '<tr>
                        <td>$'.$onetimeRF.'</td>
                        <td>$'.$monthfee.'</td>
                        <td>'.$addonfee.'%</td>
                        <td>'.$activeaccount.'</td>
                        <td>$'.(($addonfee/100)*$addonpackages).'</td>	
                        <td>'.$pdis.'</td>	
                        <td>$'.(($monthfee*($activeaccount-$pdis))+(($addonfee/100)*$addonpackages)).'</td>	
                        </tr>';
             
             echo '</tbody></table>';


	}else{
		$agent_id = $magent."-".$user_id;
	
	}
	     if(isset($_GET["sent"])){
		$sent=$_GET["sent"];
	     }
		
	     echo '</br>
		<div class="row">
		   <div class="span8">
		    <form action="ticket.php" method="post" name="main_comment_form">
		    <fieldset>
		    <h4>You can submit a ticket for us here: </h4></br>';
	    if($sent == "Y"){
		echo '<div class="alert alert-success" style="width: 409px; height: 17px; padding-top: 4px; padding-bottom: 8px;">
		    <button type="button" class="close" data-dismiss="alert">&times;</button>
		    <strong>Success!</strong> Ticket submitted.
		    </div>';
	    }else if($sent == "N"){
		echo '<div class="alert alert-danger" style="width: 409px; height: 17px; padding-top: 4px; padding-bottom: 8px;">
		    <button type="button" class="close" data-dismiss="alert">&times;</button>
		    <strong>Error!</strong> Failed submitting ticket.
		    </div>';

	    }
	     echo   '<label class="control-label">Subject:</label>
		    <input class="span6" type="text" id="subject" name="subject" placeholder="subject..">
		    <label class="control-label">Comments:</label>
		    <textarea class="span6" id="comment" name="comment" rows="8" placeholder="Type comment…"></textarea>
		    <span class="help-block">Reply of this ticket will be sent directly to your registered email.</span>
		    <button type="submit" class="btn btn-primary">Submit</button>
		    </fieldset>
		    </form>
		 </div>
		';

	     echo '<div class="span4">
			<h4>Referral URL:</h4>
			<a href="http://www.zazeen.com/index.php?agentcode='.$agent_id.'">http://www.zazeen.com/index.php?agentcode='.$agent_id.'</a>
			</br>
			</br>
			</br>
			<h4>Signup a New Customer:</h4>
			<a class="button" href="https://www.zazeen.com/cgi-bin/orders.cgi/Order_iptv.html?id='.$agent_id.'" target="_blank">Signup</a>
		   </div>
		</div>';

           }else{
                // empty list?
                echo '<h2> Unable to get the details. </h2>';
           }
	   $stmt->free();

   echo page_foot($ajax);
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}


?>

