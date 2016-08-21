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
		$addonfee = $stmt->f("addonfee");
		$onetimeRF = $stmt->f("onetimeRFee");
                $uname = $stmt->f("username");
                $agent_id = $stmt->f("agentID");
                $utype = $stmt->f("type");
                $magent = $stmt->f("mainAgent");
        }
   echo page_head(true,true,$uname);
      echo '<script type="text/javascript">
         var break_link=true;
         </script>';


   if($utype == "main"){
        echo        '        <div class="pull-right">'.$n;
        //echo        '          <a class="btn btn-primary" href="user.php">Manage Sub-accounts</a>'.$n;
        //echo        '          <a class="btn btn-primary" href="register.php">Add Sub-accounts</a>'.$n;
        echo        '        </div>'.$n;
   }else if($utype == "subuser"){
	   echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
	   exit;
   }


        echo '<div class="input-prepend">
                <span class="add-on">Search:</span>
                <input class="span3 filter" id="prependedInput" type="text" placeholder="eg. agentid">
              </div>';

        echo '<script>
         $(\'input.filter\').keyup(function() {
            var rex = new RegExp($(this).val(), \'i\');
            $(\'.searchable tr\').hide();
                $(\'.searchable tr\').filter(function() {
                    return rex.test($(this).text());
                }).show();
            });
	$(document).ready(function(){
                $( "#showprebtn" ).click(function() {
                        boothUpdate();
                });
        });

                function boothUpdate() {
                    if( $( "#showprebtn" ).text()==="Show More"){
                        $(".moreprev").show();
                        $( "#showprebtn" ).text("Show Less");
                    }
                    else {
                        $(".moreprev").hide();
                        $( "#showprebtn" ).text("Show More");
                    }
                }

	  
        </script>';

	$now=date("Y-m", strtotime("-1 month") ) ;
	$nnow=date("Y-m");
        $stmt2 = new DB_Sql;
        if ($stmt2->query("SELECT * from agent_sales where mainAgent = '$agent_id' and month='$now'")) {
	
           if($stmt2->num_rows() > 0) { // If the user exists
		
             echo ' <table class="table table-hover">
                      <caption><h3>'.$now.' Sales History</h3><br></caption>
                    <thead>
                      <tr>
                        <th>Agent ID</th>
                        <th>Name</th>
                        <th>#Sales</th>';
              echo      '
                      </tr>
                    </thead>
                    <tbody class="searchable">';
                while($stmt2->next_record()){
			$agid = $stmt2->f("agentid");
			$agname = $stmt2->f("name");
			$agsalenum = $stmt2->f("salenum");
			echo '<tr>
				<td>'.$agid.'</td>
				<td>'.$agname.'</td>
				<td>'.$agsalenum.'</td>
			      </tr>';
		}
			
             echo '</tbody></table>';
           }else{
                // empty list?
		echo '<h4>'.$now.' => Empty.</h4>';
           }
        }else{
                // failed to grab customer info
        }

	$stmt2->free();



	echo '<div id="btn_wrap" align="center"><button class="btn btn-large btn-block" id="showprebtn" type="button">Show More</button></div></br></br>';
	print '<div class="moreprev" style="display:none;">';
	$predata=array();
        $stmt3 = new DB_Sql;
        if ($stmt3->query("SELECT * from agent_sales where mainAgent = '$agent_id' and month<>'$now' and month<>'$nnow'")) {
	
           if($stmt3->num_rows() > 0) { // If the user exists
		
                while($stmt3->next_record()){
			$predata[$stmt3->f("time")][$stmt3->f("agentid")]['agname'] = $stmt3->f("name");
			$predata[$stmt3->f("time")][$stmt3->f("agentid")]['agsalenum'] = $stmt3->f("salenum");
		}
			
           }else{
                // empty list?
		echo '<h4>Empty.</h4>';
           }
        }else{
                // failed to grab customer info
        }

	$stmt3->free();


	foreach($predata as $agmon => $agd){
             echo ' <table class="table table-hover">
                      <caption><h3>'.$agmon.' Sales History</h3><br></caption>
                    <thead>
                      <tr>
                        <th>Agent ID</th>
                        <th>Name</th>
                        <th>#Sales</th>';
              echo      '
                      </tr>
                    </thead>
                    <tbody class="searchable">';
		foreach($agd as $sagid => $agsd){
			echo '<tr>
				<td>'.$sagid.'</td>
				<td>'.$agsd["agname"].'</td>
				<td>'.$agsd["agsalenum"].'</td>
			      </tr>';
		}
             echo '</tbody></table>';
	}

	print '</div>';
   echo page_foot($ajax);
} else {
	   echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
}


?>

