<?php
  include_once('./js/header.php');
  $ajax = false;

include 'db_connect.php';
include 'functions.php';
include_once('addon_plans.php');
sec_session_start($mysqli);
if(login_check($mysqli,false) == true) {
   global $n; 

	if(isset($_GET['id'])){	
	     $aid = filter_var($_GET['id'], FILTER_VALIDATE_INT);
	     if($aid){
	     }else{
		die('Caution: Invalid Id!');
	     }
	}

      $user_id = $_SESSION['user_id'];
      if ($stmt = $mysqli->prepare("SELECT cid,email,truename, company, address, country, phone, ccname,ccnumber,ccexpiry,update_time FROM members WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();

        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($ccid,$cus_email,$cus_name,$cus_company,$cus_address, $cus_country, $cus_phone, $cus_ccname, $cus_ccnumber, $cus_ccexpiry, $cus_updatetime); // get variables from result.
           $stmt->fetch();

        }}


     if ($stmt = $mysqli->prepare("SELECT status,name,raw_pkgname,trackingnumber,mid,update_time,start_date,nextdue,addon,acid,stblist FROM members_pkg WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $aid); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();
	//echo '<pre>'.$stmt->num_rows.'</pre>';
        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($status,$username,$pkgname,$track,$mid,$update_time,$startdate,$nextdue,$addonstr,$acid,$stblist); // get variables from result.
           $stmt->fetch();


	   // Check if it's within this account, otherwise we get a weak hack
	   if($mid != $user_id){
		log_bad("Tried to access other pkgid",$mysqli,$cus_name);
	   }


   echo page_head(true,false,$cus_name);

	     $addonstr2=$addonstr;
	     $addon_a = explode(",",$addonstr2);

   echo     '        <div class="pull-right">'.$n;
   echo        '          <a class="btn btn-success" href="#">Refresh</a>'.$n;
   echo        '          <a class="btn btn-success" href="main_cli_list.php">Back to List</a>'.$n;
   echo        '        </div>'.$n.
            '        <div class="clearfix"></div>'.$n;
   echo '<script>
		var break_link=true;

		$(function() {
		    $("textarea[maxlength]").bind(\'input propertychange\', function() {
			var maxLength = $(this).attr(\'maxlength\');
			//Detect how many newlines are in the textarea, then be sure to count them twice as part of the length of the input.
			var newlines = ($(this).val().match(/\n/g) || []).length
			if ($(this).val().length + newlines > maxLength) {
			    $(this).val($(this).val().substring(0, maxLength - newlines));
			}
		    })
		});

		var showplanstoggle = {';
	$ctraddon = 0;
	foreach($addon_a as $addon_e){
                $addonplans=explode("+",$addon_e);
		if($addonplans[0]){
			if($ctraddon < (count($addon_a)-2)){
			//echo "\n".'showplanstoggle[\''.str_replace(" ","-",$addonplan[0]).'\']=1;';
				echo ' \''.str_replace(" ","-",$addonplans[0]).'\': 1 ,';
			}else{
				echo ' \''.str_replace(" ","-",$addonplans[0]).'\': 1';
			}			
		}
		$ctraddon++;
	}
		echo ' };
			
			var ppkg="Basic-Ontario";
			//console.log(showplanstoggle[ppkg]);';
		
    echo 	"\n".'		function showplans(pkg){
				if(!showplanstoggle[pkg]){
					$(\'.\'+pkg).hide();
					showplanstoggle[pkg]=!showplanstoggle[pkg];
				}else{
					$(\'.\'+pkg).show();
					showplanstoggle[pkg]=!showplanstoggle[pkg];	
				}
			}


                $(document).ready(function(){
			initSessionMonitor();
                        $(\'[class^="addref"]\').each(function(){
                                 var field = $(this).attr(\'id\');
                                 $(this).load("refreshadd.php?field="+field);
                        })
                        $(\'[class^="addmod"]\').each(function(){
                                 var field = $(this).attr(\'id\');
                                 $(this).load("refreshaddbtn.php?field="+field);
                        })
                        $(\'[class^="dstb"]\').each(function(){
                                 var field = $(this).attr(\'id\');
                                 $(this).load("refreshstb.php?field="+field);
                        })
                });


		setInterval(function refinfo(){
                        $(\'[class^="addref"]\').each(function(){
                                 var field = $(this).attr(\'id\');
                                 $(this).load("refreshadd.php?field="+field);
                        })
                        $(\'[class^="addmod"]\').each(function(){
                                 var field = $(this).attr(\'id\');
                                 $(this).load("refreshaddbtn.php?field="+field);
                        })
                        $(\'[class^="dstb"]\').each(function(){
                                 var field = $(this).attr(\'id\');
                                 $(this).load("refreshstb.php?field="+field);
                        })
                        //Tricky call back, to ensure 1st function load on page load
                        return refinfo;
                }(),10000); //refresh every 5 seconds, and tricky brackets here

	</script>';

	        $label = "info";
                if($status == "Active"){
                        $label = "success";
                }

                if($status == "Suspended"){
                        $label = "warning";
                }



	   // Output main info-table

             echo '</br><div id="detailtable"><table class="table table-bordered">
                      <caption><h3>Package Details</h3><h4 style="color:gray">Last update on: '.$update_time.'</h4></br></caption>';
                echo '<tr>
                        <td>Package Name:</td>
                        <td>'.$pkgname.'</td>
                      </tr>
                      <tr>
                        <td>Status:</td>
                        <td><span class="label label-'.$label.'">'.$status.'</span></td>
                      </tr>
                      <tr>
                        <td>Start Date:</td>
                        <td>'.$startdate.'</td>
                      </tr>
                      <tr>
                        <td>Next Due:</td>
                        <td>'.$nextdue.'</td>
                      </tr>
                  </table>
                </div><br>';



            echo'        <div class="clearfix"></div>'.$n;





                        /*<th style="width: 20%">Name</th>
                        <th style="width: 20%">Price</th>
                        <th style="width: 20%">Status</th>
                        <th style="width: 20%">Start Date</th>
                        <th style="width: 20%;text-align: right;">Modify</th>*/
             echo ' <table class="table table-hover" style="width:100%;table-layout:fixed;">
                      <caption><h3>Package Add-ons</h3><h4 style="color:green">Click for channels detail</h4><br></caption>
                    <thead>
                      <tr>
                        <th style="width: 20%">Name</th>
                        <th style="width: 20%">Price</th>
                        <th style="width: 20%">Status</th>
                        <th style="width: 20%">Start Date</th>
                        <th style="width: 20%;text-align: right;">Modify</th>
                      </tr>
                    </thead>
                    <tbody class="searchable">';
	     require_once "encryption.php";
	     $eccid = $converter->encode($ccid);
	     $eacid = $converter->encode($acid);
	     $eaid = $converter->encode($aid);

	     $addon_array = explode(",",$addonstr);
	     foreach($addon_array as $addon_entry){
		$addon=explode("+",$addon_entry);
			
    	        $edaddon = $converter->encode('d'.$addon[2]);
    	        $eaaddon = $converter->encode('a'.$addon[2]);

		if($addon[3]=="r"){
			$astatus = "Active";
			$alabel = "label-success";
			$abtn = '<form style="margin: 0 0 1px;" action="delete_cli_pkg.php" method="post" onsubmit="return confirm(\'This action will REMOVE this service, are you sure to continue?\n\nPlease note that the change will take affect at the end of billing cycle.\');"><input type="hidden" id="cid" name="cid" value="'.$eccid.'"><input type="hidden" id="pid" name="pid" value="'.$edaddon.'"><input type="hidden" id="aid" name="aid" value="'.$eacid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Remove" class="btn-mini btn-danger"/></form>';
			$btn_addon= $converter->encode('d'.$addon[2].','.$aid.','.$ccid.','.$acid);
			//$abtn='';
		}else{
			$astatus = "Non-active";
			$alabel = "";
			$abtn = '<form style="margin: 0 0 1px;" action="delete_cli_pkg.php" method="post" onsubmit="return confirm(\'Thank you for choosing Zazeen IPTV! Your change will be updated soon.\');"><input type="hidden" id="cid" name="cid" value="'.$eccid.'"><input type="hidden" id="pid" name="pid" value="'.$eaaddon.'"><input type="hidden" id="aid" name="aid" value="'.$eacid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Activate" class="btn-mini btn-success"/></form>';
			$btn_addon= $converter->encode('a'.$addon[2].','.$aid.','.$ccid.','.$acid);
		}
		
		if($addon[0]=="Basic Quebec" || $addon[0]=="Basic Ontario" ){
			$astatus = "Active";
			$alabel = "label-success";
			$abtn = '<td><span class="label label-info">Main Package</span></td>';

		}

	
		if($addon[4]!=""){
			$trdate = $addon[4];
		}else{
			$trdate = 'N/A';
		}

		if($addon[0]!="" && strpos(" ".$addon[0],"Basic") == false ){
			
    	        	$st_addon = $converter->encode('s'.$addon[2].','.$aid);
    	        	$da_addon = $converter->encode('t'.$addon[2].','.$aid);


			echo '<tr onmouseover="this.style.cursor=\'pointer\'" onclick="if (break_link) showplans(\''.str_replace(" ","-",$addon[0]).'\');">
				<td>'.$addon[0].'</td>
				<td>$'.$addon[1].'</td>
				<td><div class="addref" id="'.$st_addon.'"><span class="label '.$alabel.'">'.$astatus.'</span></div></td>
				<td><div class="addref" id="'.$da_addon.'">'.$trdate.'</div></td>
				<td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="addmod pull-right" id="'.$btn_addon.'">'.$abtn.'</div></td>
				</tr>';

				//<td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="addmod pull-right" id="'.$btn_addon.'">'.$abtn.'</div></td>
			echo $addonplan[$addon[0]];
		}
             }
		echo '</tbody></table>';

	   if($stblist != ""){
		echo ' <table class="table table-striped" style="width:100%;table-layout:fixed;">
                      <caption><h3>STB Boxes List</h3><br></caption>
                    <thead>
                      <tr>
                        <th style="width: 20%">ID</th>
                        <th style="width: 20%">MAC</th>
                        <th style="width: 20%">IP</th>
                        <th style="width: 20%">Last Seen</th>
                        <th style="width: 20%;text-align: right;">Modify</th>
                      </tr>
                    </thead>
                    <tbody>';
		   $stbbtn = '<a herf="#">Delete[not functional yet]</a>';
		   $stblist_r = explode("+",$stblist);
		   foreach($stblist_r as $stb_e){
			$stb = explode(",",$stb_e);

			   $stb3 = $stb[3];
			   if($stb[3]==""){
				$stb3 = "N/A";
			   }

			   $estbid = $converter->encode($stb[0]);
			   $stbtn = '<form style="margin: 0 0 1px;" action="delete_cli_stb.php" method="post" onsubmit="return confirm(\'This action will REMOVE this STB box, are you sure to continue?\');"><input type="hidden" id="sid" name="sid" value="'.$estbid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Remove" class="btn-mini btn-danger"/></form>';
			echo '<tr class="dstb" id="'.$estbid.'">
                                <td>'.$stb[0].'</td>
                                <td>'.$stb[1].'</td>
                                <td><span class="label label-success">'.$stb[2].'</span></td>
                                <td>'.$stb3.'</td>
                                <td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right">'.$stbtn.'</div></td>
                              </tr>';				
		   }
		   echo '</tbody></table>';
	   }



	}else{
	   // returning no result or mutiple result
	   log_bad("Try to access other pkg_id",$mysqli,$cus_name); 
	}
     }else{
	// database error
	log_bad("Pkg database failure",$mysqli,$cus_name); 
     }



   echo page_foot($ajax);
} else {
	   log_bad("Tried to directly access pkg page",$mysqli); 

}


?>
