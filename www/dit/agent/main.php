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
	if ($stmt->query("SELECT * from agents where id = '$user_id'")) {
		$stmt->next_record();
		$monthfee = $stmt->f("monthlyfee");
		$activeaccount = $stmt->f("activeaccounts");
		$addonfee = $stmt->f("addonfee");
		$addonpackages = $stmt->f("addonpackages");
		$pdis = $stmt->f("pendinginvoice");
		$update_time = $stmt->f("update_time");
		$onetimeRF = $stmt->f("onetimeRFee");
		$newsignup = $stmt->f("newsignup");
		$cancelled = $stmt->f("cancelled");
		$agent_id = $stmt->f("agentID");
		$uname = $stmt->f("username");
		$utype = $stmt->f("type");
		$magent = $stmt->f("mainAgent");
		$onetimeaccount = $stmt->f("onetimeaccount");
		$monthlyaccount = $stmt->f("monthlyaccount");
		$monthlypdi = $stmt->f("monthlypdi");
		$monthlyday = $stmt->f("monthlyday");
		$onetimeday = $stmt->f("onetimeday");
                $usubtoggle = $stmt->f("subtoggle");
                $ubillable = $stmt->f("billable");
                $unsuspend = $stmt->f("nsuspend");
                $unterminate = $stmt->f("nterminate");

		$subagent= $stmt->f("subAgent");

	$today = date('M');


   echo page_head(true,true,$uname); 
      echo '<script type="text/javascript">
         var break_link=true;
         </script>';

   echo '<script type="text/javascript">
        $(document).ready(function(){
                $( "#showbtn" ).click(function() {
                        boothUpdate();
                });
        });

                function boothUpdate() {
                    if( $( "#showbtn" ).text()==="Show More"){
                        $(".morerow").show();
			$( "#showbtn" ).text("Show Less");
                    }
                    else {
                        $(".morerow").hide();
			$( "#showbtn" ).text("Show More");
                    }
                }

        </script>';


   if($utype == "main" || $utype == "subagent"){
	if($utype == "subagent"){
		$agent_id = $magent."-".$user_id;
	}
   	echo        '        <div class="pull-right">'.$n;
   if($user_id==540){
        echo        '          <a class="btn btn-primary" href="viewsales.php">View Sales List</a>'.$n;
   }
        echo        '          <a class="btn btn-primary" href="revenue_history.php">View Revenue History</a>'.$n;
        echo        '          <a class="btn btn-primary" href="agentlist.php">Customer Management</a>'.$n;
   if($usubtoggle=="Y"){
        echo        '          <a class="btn btn-primary" href="user.php">Manage Sub-accounts</a>'.$n;
	echo        '          <a class="btn btn-primary" href="register.php">Add Sub-accounts</a>'.$n;
   }
	echo        '        </div>'.$n.
           	    '        <div class="clearfix"></div>'.$n;
   
	$sub_total = 0;

	// calculate subagent total revenue:
	     $st_db = new DB_Sql;
	     $st_db->query("SELECT * from agents where mainAgent = '$agent_id' and type = 'subagent'");
	     while($st_db->next_record()){
		        $st_monthfee = $st_db->f("monthlyfee");
			$st_activeaccount = $st_db->f("activeaccounts");
			$st_addonfee = $st_db->f("addonfee");
			$st_addonpackages = $st_db->f("addonpackages");
			$st_pdis = $st_db->f("pendinginvoice");
			$st_update_time = $st_db->f("update_time");
			$st_onetimeRF = $st_db->f("onetimeRFee");
			$st_agent_id = $st_db->f("agentID");
			$st_uname = $st_db->f("username");
			$st_utype = $st_db->f("type");
			$st_magent = $st_db->f("mainAgent");
			$st_uid = $st_db->f("id");
			$st_newsignup = $st_db->f("newsignup");
			$st_onetimeaccount = $st_db->f("onetimeaccount");
			$st_cancelled = $st_db->f("cancelled");
			$st_monthlyaccount = $st_db->f("monthlyaccount");
			$st_monthlypdi = $st_db->f("monthlypdi");

			$st_email= $st_db->f("email");
			if($st_activeaccount == ""){
				$st_activeaccount=0;
			}
			if($st_pdis == ""){
				$st_pdis=0;
			}
			if($st_newsignup == ""){
				$st_newsignup=0;
			}
			if($st_cancelled == ""){
				$st_cancelled=0;
			}
			$sub_t = (($st_monthfee*($st_monthlyaccount-$st_monthlypdi))+(($st_onetimeRF * ($st_onetimeacconut))));
			$sub_total = $sub_total+$sub_t;
	     }


	     $total = (($monthfee*($monthlyaccount-$monthlypdi))+($onetimeRF*($onetimeaccount)));
        // List details of this agent
             echo ' </br><table class="table table-hover">
                      <caption><h3>Your Revenue</h3></br><h5 style="color:gray">click in for more details</h5><h4 style="color:gray">Last update on: '.$update_time.'</h4></br></caption>
                    <thead>
                      <tr>
                        <th>One-time Referral Fee</th>
                        <th>Referral Monthly Fee</th>
                        <th>Add-on Referral Fee</th>
                        <th>Active Accounts</th>
                        <th>Suspend Accounts</th>
                        <th>Terminated Accounts</th>';
		if($ubillable!="Y")
                       echo ' <th>Pending Invoices</th>';

                       echo ' <th>Cashable Revenue ( '.$today.' )</th>
                      </tr>
                    </thead>
                    <tbody>';

                echo '<tr  onmouseover="this.style.cursor=\'pointer\'" onclick="if (break_link) window.location =\'user_detail.php?id='.$user_id.'\'">
                        <td>$'.$onetimeRF.' / '.$onetimeday.' days</td>
                        <td>$'.$monthfee.' / '.$monthlyday.' days</td>
                        <td>'.$addonfee.'%</td>
                        <td>'.$activeaccount.'</td>
                        <td>'.$unsuspend.'</td>
                        <td>'.$unterminate.'</td>';
		if($ubillable!="Y")
                echo'        <td>'.$pdis.'</td>	';
echo'                        <td>$'.$total.' ( Net:$'.($total-$sub_total).' ) </td>	
                        </tr>';
             
             echo '</tbody></table>';


	// subagent table for main 
	if($utype == "main" && $usubtoggle=="Y"){
		echo '  </br>      
			</br>
			<div class="clearfix"></div>'.$n;

//print "<font color=red>Here</font>";
	     $sub_db = new DB_Sql;
	     $sub_db->query("SELECT * from agents where mainAgent = '$agent_id' and type = 'subagent' order by update_time desc");
	
	     if($sub_db->num_rows() > 0){

		        // List details of this agent
		     echo ' <table class="table table-hover">
                      <caption><h3>Sub Agent Revenue</h3></br></caption>
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Agent ID</th>
                        <th>Active Accounts</th>
                        <th>New Accounts</th>
                        <th>Deleted Accounts</th>
                        <th>Cashable Revenue ( '.$today.' )</th>
                      </tr>
                    </thead>
                    <tbody>';
		
	     $counter=0;
	     while($sub_db->next_record()){
		$sub_monthfee = $sub_db->f("monthlyfee");
                $sub_activeaccount = $sub_db->f("activeaccounts");
                $sub_addonfee = $sub_db->f("addonfee");
                $sub_addonpackages = $sub_db->f("addonpackages");
                $sub_pdis = $sub_db->f("pendinginvoice");
                $sub_update_time = $sub_db->f("update_time");
                $sub_onetimeRF = $sub_db->f("onetimeRFee");
                $sub_agent_id = $sub_db->f("agentID");
                $sub_uname = $sub_db->f("username");
                $sub_utype = $sub_db->f("type");
                $sub_magent = $sub_db->f("mainAgent");
                $sub_uid = $sub_db->f("id");
                $sub_newsignup = $sub_db->f("newsignup");
                $sub_onetimeaccount = $sub_db->f("onetimeaccount");
                $sub_cancelled = $sub_db->f("cancelled");
                $sub_monthlyaccount = $sub_db->f("monthlyaccount");
                $sub_monthlypdi = $sub_db->f("monthlypdi");

                $sub_email= $sub_db->f("email");
		if($sub_activeaccount == ""){
			$sub_activeaccount=0;
		}	
		if($sub_pdis == ""){
			$sub_pdis=0;
		}	
		if($sub_newsignup == ""){
			$sub_newsignup=0;
		}	
		if($sub_cancelled == ""){
			$sub_cancelled=0;
		}	


			$tr_info = '';
		if($counter>4){
			$tr_info = ' class="morerow" style="display:none"';
		}
                echo '<tr'.$tr_info.'  onmouseover="this.style.cursor=\'pointer\'" onclick="if (break_link) window.location =\'user_detail.php?id='.$sub_uid.'\'">
                        <td>'.$sub_uname.'</td>
                        <td style="width:70px;">'.$sub_magent.'-'.$sub_uid.'</td>
                        <td>'.$sub_activeaccount.'</td>
                        <td>'.$sub_newsignup.'</td>
                        <td>'.$sub_cancelled.'</td>      
                        <td>$'.(($sub_monthfee*($sub_monthlyaccount-$sub_monthlypdi))+(($sub_onetimeRF * ($sub_onetimeaccount)))).'</td>     
                        </tr>';
	
		$counter++;

	     }


             echo '</tbody></table>';

	     echo '<div id="btn_wrap" align="center"><button class="btn btn-large btn-block" id="showbtn" type="button">Show More</button></div></br></br>';


	     }else{	//empty list
		echo '<h4>You don\'t have any sub-accounts.</h4>';	
	     }

	}




	}else{
		$agent_id = $magent."-".$subagent."-".$user_id;
	
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
			<!--<h4>Referral URL:</h4>
			<a href="https://www.zazeen.com/index.php?agentcode='.$agent_id.'">Zazeen_referral</a>-->';
		if($ubillable == "Y"){
			echo '<br>
                        <br>
                        <h4>Signup a New Customer :</h4>
                        <a class="button" href="order.php">Zazeen_IPTV</a>';
		}else{
			echo '<br>
                        <br>
                        <h4>Signup a New Customer:</h4>
                        <a class="button" href="orderpage.php">Zazeen_IPTV</a>';
		}		


	echo	'   </div>
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

