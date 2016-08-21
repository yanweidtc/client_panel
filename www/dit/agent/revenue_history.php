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
	if ($stmt->query("SELECT id, type, username, agentID, monthlyfee, onetimeRFee, activeaccounts, addonfee, addonpackages, pendinginvoice, update_time, mainAgent,subAgent,newsignup from agents where id = '$user_id'")) {
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
	if(isset($_GET["sid"])){
		$sid = $_GET["sid"];
		$agent_id = $agent_id."-".$sid;

		$read = new DB_Sql;
		$read->query("select username from agents where id='$sid'");
		$read->next_record();
		$uname = $read->f("username");
		$read->free();
	}
	if($utype == "subagent"){
		$agent_id = $magent."-".$user_id;
	}
   	echo        '        <div class="pull-right">'.$n;
        echo        '          <a class="btn btn-primary" href="main.php">Back</a>'.$n;
	echo        '        </div>'.$n.
           	    '        <div class="clearfix"></div>'.$n;
   
	// calculate subagent total revenue:
	     $st_db = new DB_Sql;
	     $st_db->query("SELECT * from agenthistory where agentid = '$agent_id' order by time");


		        // List details of this agent
		     echo ' <table class="table table-hover">
                      <caption><h3>'.$uname.'\'s Revenue History</h3></br></caption>
                    <thead>
                      <tr>
                        <th>Month</th>
                        <th>Revenue</th>
                      </tr>
                    </thead>
                    <tbody>';
		$counter = 0;
	     while($st_db->next_record()){
		        $st_revenue = $st_db->f("revenue");
			$st_time = $st_db->f("time");

			$tr_info = '';
		if($counter>11){
			$tr_info = ' class="morerow" style="display:none"';
		}
                echo '<tr'.$tr_info.'>
                        <td>'.$st_time.'</td>
                        <td>'.$st_revenue.'</td>
                        </tr>';
	
		$counter++;
	     }

             echo '</tbody></table>';

	     echo '<div id="btn_wrap" align="center"><button class="btn btn-large btn-block" id="showbtn" type="button">Show More</button></div></br></br>';
		
		}//query

	}else{
   		echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
	
	}
   echo page_foot($ajax);
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}


?>

