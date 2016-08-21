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
   }else if($utype == "sub"){
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

        $stmt2 = new DB_Sql;
        if ($stmt2->query("SELECT id, type, username,email, update_time, mainAgent from agents where mainAgent = '$agent_id'")) {
	
           if($stmt2->num_rows() > 0) { // If the user exists
		
             echo ' <table class="table table-hover">
                      <caption><h3>Sub-user List</h3></br><h4  style="color:gray">click one to edit details</h4></br></caption>
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Operation</th>
                      </tr>
                    </thead>
                    <tbody class="searchable">';
                while($stmt2->next_record()){
			$sub_id = $stmt2->f("id");
			$sub_uname = $stmt2->f("username");
			$sub_email = $stmt2->f("email");
			$sub_utype = $stmt2->f("type");
			$sub_magent = $stmt2->f("mainAgent");

                echo '<tr onmouseover="this.style.cursor=\'pointer\'" onclick="if (break_link) window.location =\'user_edit.php?id='.$sub_id.'\'">
                        <td>'.$sub_id.'</td>
                        <td>'.$sub_uname.'</td>
                        <td>'.$sub_email.'</td>
                        <td onmouseover="break_link=false;" onmouseout="break_link=true;"><form style="margin: 0 0 1px;" action="user_delete.php" method="post" onsubmit="return confirm(\'Are you sure to delete this sub-user?\');"><input type="hidden" id="uid" name="uid" value="'.$sub_id.'"><input type="submit" name="submit" value="Delete User" class="btn-xs btn-danger"/></form></td>
                        </tr>';
             }
             echo '</tbody></table>';
           }else{
                // empty list?
           }
        }else{
                // failed to grab customer info
        }



   echo page_foot($ajax);
} else {
	   echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
}


?>

