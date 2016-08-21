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
        echo        '          <a class="btn btn-primary" href="user.php">Manage Sub-accounts</a>'.$n;
        echo        '          <a class="btn btn-primary" href="register.php">Add Sub-accounts</a>'.$n;
        echo        '        </div>'.$n;
   }else if($utype == "subuser"){
	   echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
	   exit;
   }


        echo '<div class="input-prepend">
                <span class="add-on">Search:</span>
                <input class="span3 filter" id="prependedInput" type="text" placeholder="eg. email">
              </div>';

        echo '<script>
         $(\'input.filter\').keyup(function() {
            var rex = new RegExp($(this).val(), \'i\');
            $(\'.searchable tr\').hide();
                $(\'.searchable tr\').filter(function() {
                    return rex.test($(this).text());
                }).show();
            });
        </script>';


   $agent_sql = "mainAgent";
   //$sub_select = "and agentID = 0";
   $sub_select = "";
   if($utype == "subagent"){
	$agent_sql = "subAgent";
	$agent_id = $user_id;
	$sub_select="";
   }
        $stmt2 = new DB_Sql;
        if ($stmt2->query("SELECT * from agents where ".$agent_sql." = '$agent_id' ".$sub_select."")) {
	
           if($stmt2->num_rows() > 0) { // If the user exists
		
             echo ' <table class="table table-hover">
                      <caption><h3>Sub-account List</h3></br><h4  style="color:gray">click one to edit details</h4></br></caption>
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Type</th>';
	if($utype == "main"){
              echo          '<th>One-time Referral Fee</th>
                        <th>Referral Monthly Fee</th>
                        <th>Add-on Fee</th>';
	}
              echo      '<th>Operation</th>
                      </tr>
                    </thead>
                    <tbody class="searchable">';
                while($stmt2->next_record()){
			$sub_id = $stmt2->f("id");
			$sub_uname = $stmt2->f("username");
			$sub_email = $stmt2->f("email");
			$sub_utype = $stmt2->f("type");
				$sub_aid = $stmt2->f("subAgent");
			
			if(($sub_utype == "subuser" && $sub_aid == 0) || $sub_utype == "subagent"){
					
			$sub_magent = $stmt2->f("mainAgent");
			$sub_onetimeRF = $stmt2->f("onetimeRFee");
			$sub_monthfee = $stmt2->f("monthlyfee");
			$sub_addonfee = $stmt2->f("addonfee");
                        $sub_monthlyday = $stmt2->f("monthlyday");
                        $sub_onetimeday = $stmt2->f("onetimeday");

			$sub_phone = $stmt2->f("phone");

                echo '<tr onmouseover="this.style.cursor=\'pointer\'" onclick="if (break_link) window.location =\'user_edit.php?id='.$sub_id.'\'">
                        <td>'.$sub_id.'</td>
                        <td>'.$sub_uname.'</td>
                        <td>'.$sub_email.'</td>
                        <td>'.$sub_phone.'</td>
                        <td>'.$sub_utype.'</td>';
        if($utype == "main" && $sub_utype == "subagent"){
                echo    '<td>$'.$sub_onetimeRF.' / '.$sub_onetimeday.' days</td>
                        <td>$'.$sub_monthfee.' / '.$sub_monthlyday.' days</td>
                        <td>'.$sub_addonfee.'%</td>';
        }else if($sub_utype == "subuser"){
                echo    '<td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>';
        }

	
	
        echo            '<td onmouseover="break_link=false;" onmouseout="break_link=true;"><form style="margin: 0 0 1px;" action="user_delete.php" method="post" onsubmit="return confirm(\'Are you sure to delete this sub-user?\');"><input type="hidden" id="uid" name="uid" value="'.$sub_id.'"><input type="submit" name="submit" value="Delete User" class="btn-xs btn-danger"/></form></td>
                        </tr>';

			
				}
		
			


             }
             echo '</tbody></table>';
           }else{
                // empty list?
		echo '<h4>You don\'t have any sub-accounts.</h4>';
           }
        }else{
                // failed to grab customer info
        }

	$stmt2->free();

   echo page_foot($ajax);
} else {
	   echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
}


?>

