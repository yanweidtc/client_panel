<?php
  include_once('./js/header.php');
  $ajax = false;

include 'db_connect.php';
include 'functions.php';
sec_session_start($mysqli);
if(login_check($mysqli,false) == true) {
   global $n; 

      $user_id = $_SESSION['user_id'];
      if ($stmt = $mysqli->prepare("SELECT cid,email,truename, company, address, country, phone,phone2, ccname,ccnumber,ccexpiry,update_time,invoices FROM members WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();

        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($ccid,$cus_email,$cus_name,$cus_company,$cus_address, $cus_country, $cus_phone,$cus_phone2, $cus_ccname, $cus_ccnumber, $cus_ccexpiry, $cus_updatetime,$cus_invoices); // get variables from result.
           $stmt->fetch();

        }}


   echo page_head(true,false,$cus_name);
   echo '<script type="text/javascript">
	 	var break_link=true;

		$(document).ready(function(){
			initSessionMonitor();
			$(\'[class^="ref"]\').each(function(){
                                 var field = $(this).attr(\'id\');
                                 $(this).load("refreshinfo.php?field="+field);
                        })
                        $( "div[id=\'upd-cc\']" ).each(function(){
                            $(this).load("refresh.php");  //update div
                        })
		});


/*                setInterval(function refinfo(){			
			$(\'[class^="ref"]\').each(function(){
				 var field = $(this).attr(\'id\');
				 $(this).load("refreshinfo.php?field="+field);
			})
                        $( "div[id=\'upd-cc\']" ).each(function(){
                            $(this).load("refresh.php");  //update div
                        })
                        $( "div[id=\'upd-email\']" ).each(function(){
                            $(this).load("refreshemail.php");  //update div
                        })
                        $( "div[id=\'upd-phone\']" ).each(function(){
                            $(this).load("refreshphone.php");  //update div
                        })
			//Tricky call back, to ensure 1st function load on page load
			return refinfo;
		}(),5000); //refresh every 5 seconds, and tricky brackets here
*/

                setInterval(function refinfo(){			
			$(\'[class^="ref"]\').each(function(){
				 var field = $(this).attr(\'id\');
				 $(this).load("refreshinfo.php?field="+field);
			})
                        $( "div[id=\'upd-cc\']" ).each(function(){
                            $(this).load("refresh.php");  //update div
                        })
			//Tricky call back, to ensure 1st function load on page load
			return refinfo;
		}(),5000); //refresh every 5 seconds, and tricky brackets here

	 </script>';

   echo     '        <div class="pull-right">'.$n;
echo        '        </div>'.$n.
            '        <div class="clearfix"></div>'.$n;




// Output main info-table
//table-bordered
             echo '<br><div id="detailtable"><table class="table table-bordered">
                      <caption><h3>Profile</h3><h4 style="color:gray">Last update on: '.$cus_updatetime.'</h4><br></caption>';
                echo '	     <tr>
				<td>Name:</td>
				<td>'.$cus_name.'</td>
			      </tr>
			      <tr>
				<td>Phone:</td>
				<td class="ref" id="phone">'.$cus_phone.'</td>
			      </tr>';
		//if($cus_phone2){
			echo '<tr>
				<td>Phone 2:</td>
				<td class="ref" id="phone2">'.$cus_phone.'</td>
			      </tr>';
		//}
		/*	 echo '<tr>
				<td style="border-right:0px;border-left:0px;"></td><td style="border-left:0px;"><div class="pull-right" id="upd-phone"></div></td>
				</tr>';*/

			 echo '<tr>
				<td>Email Address:</td>
				<td class="ref" id="email">'.$cus_email.'</td>
			      </tr>';
			   /*   <tr>
				<td style="border-right:0px;border-left:0px;"></td><td style="border-right:0px;border-left:0px;"><div class="pull-right" id="upd-email"></div></td>
			      </tr>
			';*/

		 echo '
		       <tr>
                        <td>Credit Card Name:</td>
			<td><div class="ref" id="ccname">'.$cus_ccname.'</div>';
			
		echo  '</td>
                      </tr>
		      <tr>
                        <td>Credit Card Number:</td>
                        <td class="ref" id="ccnumber">'.$cus_ccnumber.'</td>
                      </tr>
		      <tr>
                        <td>Credit Card Expiry:</td>
                        <td class="ref" id="ccexpiry">'.$cus_ccexpiry.'</td>
                      </tr>';
		 echo '<tr>
                        <td style="border-right:0px;border-left:0px;"></td><td style="border-right:0px;border-left:0px;"><div class="pull-right" id="upd-cc"></div></td>
		       </tr>
		       
		       
		       ';
echo 	'	</table>
		</div>';



            echo'        <div class="clearfix"></div>'.$n;





/////////////////////////////////////////////////////////Packages




	$user_id = $_SESSION['user_id'];
        // List all packages this customer has
        if ($stmt2 = $mysqli->prepare("SELECT m.email, p.name, p.raw_pkgname, p.status, p.start_date,p.id,p.nextdue FROM members m JOIN members_pkg p ON m.id = p.mid WHERE m.id = ?")) {
	  $stmt2->bind_param('i', $user_id); 
          $stmt2->execute(); // Execute the prepared query.
          $stmt2->store_result();
/*	echo '<div class="input-prepend pull-right">
		<span class="add-on">Search:</span>
		<input class="span3 filter" id="prependedInput" type="text" placeholder="eg. Cable">
	      </div>';*/

	echo '<script>
	 $(\'input.filter\').keyup(function() {
	    var rex = new RegExp($(this).val(), \'i\');
	    $(\'.searchable tr\').hide();
		$(\'.searchable tr\').filter(function() {
		    return rex.test($(this).text());
		}).show();
	    });
	</script>';

           if($stmt2->num_rows > 0) { // If the user exists
             $stmt2->bind_result($email,$username,$pkgname,$status,$update_time,$aid,$nextdue); // get variables from result.
             echo ' <table class="table table-hover">
	      <caption><h3>Packages</h3><h4 style="color:green">click on entry to view add-on list</h4><br></caption>
		    <thead>
		      <tr>
		        <th>Username</th>
		        <th>Package</th>
		        <th>Status</th>
		        <th>Nextdue</th>
		      </tr>
		    </thead>
		    <tbody class="searchable">';
	     while($stmt2->fetch()){
		$label = "info";
                if($status == "ACTIVE" || $status == "Completed" || $status == "Active"){
                        $label = "success";
                }

                if($status == "Suspended"){
                        $label = "warning";
                }

		$comment_label = '<span class="label label-success">Answered</span>';
		// Search Comments
		if ($stmt = $mysqli->prepare("SELECT says_who FROM members_comments WHERE aid = ? ORDER BY time DESC")) {
		  $stmt->bind_param('i', $aid);
		  $stmt->execute(); // Execute the prepared query.
		  $stmt->store_result();

		   if($stmt->num_rows > 0) { // If comments exists
		     $stmt->bind_result($who); // get variables from result.
		     $stmt->fetch();
		     if($who == $username){
			$comment_label = '<span class="label label-warning">Waitng Reply</span>';
		     }
		  }
		}
		echo '<tr onmouseover="this.style.cursor=\'pointer\'" onclick="if (break_link) window.location =\'main_cli_pkg.php?id='.$aid.'\'">
			<td>'.$username.'</td>
			<td>'.$pkgname.'</td>
			<td><div id="checkStatus"><span class="label label-'.$label.'">'.$status.'</span><input type = \'hidden\' class = \'check_id\' value = '.$aid.'/><input type = \'hidden\' class = \'default_status\' value = '.$status.'/></div></td>
			<td>'.$nextdue.'</td>
			</tr>';
             }
	     echo '</tbody></table>';
           }else{
                // empty list?
		echo '<h2> Empty Package list </h2>';
           }
        }else{
                // failed to grab customer info
        }
   








////////////////////////////////////////////////////////////////////////////invoices

             echo ' <table class="table">
		      <caption><h3>Invoices</h3><br></caption>
		    <thead>
		      <tr>
		        <th>Invoice#</th>
		        <th>Description</th>
		        <th>Amount</th>
		        <th>Date</th>
		        <th>Charge</th>
		      </tr>
		    </thead>
		    <tbody class="searchable">';
		$invoices_data = explode("#",$cus_invoices);
	     foreach($invoices_data as $invoices_entry){
		$i =  explode("+",$invoices_entry);
		if($i[0]!="" || $i[0]!=0){
			echo '<tr>
				<td>'.$i[0].'</td>
				<td>'.$i[1].'</td>
				<td>$'.$i[2].'</td>
				<td>'.$i[3].'</td>
				<td>'.$i[4].'</td>
				</tr>';
		}
             }
	     echo '</tbody></table>';



   

   echo page_foot($ajax);
} else {
	 log_bad("Tried to directly access main page",$mysqli);

}


?>
