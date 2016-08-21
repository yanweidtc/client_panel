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
	if($utype == "subagent"){
		$agent_id = $magent."-".$user_id;
	}

	if(isset($_GET['agid']) && $_GET['agid']!="" && strlen($_GET['agid'])<20 && preg_match('/^[0-9-]*$/', $_GET['agid'])){
		$agent_id=$_GET['agid'];
		$sidarr = explode("-",$agent_id);
	
		if(isset($sidarr[1])){
			$sid = $sidarr[1];
			$read = new DB_Sql;
			$read->query("select username from agents where id='$sid'");
			$read->next_record();
			$uname = $read->f("username");
			$read->free();
		}else{
			$sid = $sidarr[0];
			$read = new DB_Sql;
			$read->query("select username from agents where agentID='$sid'");
			$read->next_record();
			$uname = $read->f("username");
			$read->free();

		}
	}

   	echo        '        <div class="pull-right">'.$n;
        echo        '          <a class="btn btn-primary" href="main.php">Back</a>'.$n;
	echo        '        </div>'.$n.
           	    '        <div class="clearfix"></div>'.$n;
   

	if(isset($_GET['eid']) && $_GET['eid']!="" && strlen($_GET['eid'])<20 && preg_match('/^[0-9]*$/', $_GET['eid'])){

			$reid=$_GET['eid'];
			$read2 = new DB_Sql;
			$read2->query("select name from agentcx where id='$reid'");
			$read2->next_record();
			$cxname = $read2->f("name");
			$read2->free();
	}

	// calculate subagent total revenue:
	     $st_db = new DB_Sql;
	if(isset($_GET['eid']) && $_GET['eid']!="" && strlen($_GET['eid'])<20 && preg_match('/^[0-9]*$/', $_GET['eid'])){
		$seid=$_GET['eid'];
	        $st_db->query("SELECT * from agentlog where eid = '$seid' order by updatetime");


		$cusflag=true;
		        // List details of this agent
		     echo ' <table class="table table-hover">
                      <caption><h3>Customer '.$cxname.'\'s History</h3></br></caption>
                    <thead>
                      <tr>
                        <th>Time</th>
                        <th>Log Event</th>
                      </tr>
                    </thead>
                    <tbody>';
	}else{    
	     $st_db->query("SELECT * from agentlog where agentid = '$agent_id' order by updatetime");

		$cusflag=false;
		        // List details of this agent
		     echo ' <table class="table table-hover">
                      <caption><h3>Agent '.$uname.'\'s History</h3></br></caption>
                    <thead>
                      <tr>
                        <th>Time</th>
                        <th>Log Event</th>
                      </tr>
                    </thead>
                    <tbody>';

	}

		$counter = 0;
	     while($st_db->next_record()){
		        $st_revenue = $st_db->f("event");
			$st_time = $st_db->f("updatetime");
			$st_action = $st_db->f("action");

			$tr_info = '';
			$tr_color = '';
		if($st_action=="noansw" || $st_action=="voicem"){
			$tr_color="warning";
		}else if($st_action=="sorder"){
			$tr_color="success";
		}else if($st_action=="nointr" || $st_action=="hangup"){
			$tr_color="danger";
		}else if($st_action=="callbl" || $st_action=="techsu"){
			$tr_color="info";
		}

		if($counter>11){
			$tr_info = ' morerow" style="display:none"';
		}else{
			$tr_info = '"';
		}
                echo '<tr class="'.$tr_color.$tr_info.'>
                        <td>'.$st_time.'</td>
                        <td>'.$st_revenue.'</td>
                        </tr>';
	
		$counter++;
	     }

             echo '</tbody></table>';

	     echo '<div id="btn_wrap" align="center"><button class="btn btn-primary btn-lg btn-block" id="showbtn" type="button">Show More</button></div></br></br>';
		
		}//query

	}else{
   		echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
	
	}
   echo page_foot($ajax);
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}


?>

