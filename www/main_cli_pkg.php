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

      require_once "encryption.php";
      $dcryaid = $converter->encode($aid);

      $user_id = $_SESSION['user_id'];

      if ($stmt0 = $mysqli->prepare("SELECT cid,email,truename, company, address, country, phone, ccname,ccnumber,ccexpiry,update_time,agent,type,agcontact FROM members WHERE id = ? LIMIT 1")) {
        $stmt0->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt0->execute(); // Execute the prepared query.
        $stmt0->store_result();

        if($stmt0->num_rows == 1) { // If the user exists
           $stmt0->bind_result($ccid,$cus_email,$cus_name,$cus_company,$cus_address, $cus_country, $cus_phone, $cus_ccname, $cus_ccnumber, $cus_ccexpiry, $cus_updatetime,$cus_agent,$cus_type,$cus_agcontact); // get variables from result.
           $stmt0->fetch();

        }}

	//if($cus_agent=="billagent" || $cus_agent=="shipagent" || $cus_type=="billcustomer" || $cus_type=="shipcustomer"){
	if($cus_agent=="billagent" || $cus_agent=="shipagent"){
		$billaghide='display:none;';
	}


      if ($stbstmt = $mysqli->prepare("SELECT wnc,entone,plc,moca FROM stb_info WHERE id = 1 LIMIT 1")) {
        $stbstmt->execute(); // Execute the prepared query.
        $stbstmt->store_result();

        if($stbstmt->num_rows == 1) { // If the user exists
           $stbstmt->bind_result($wncprice,$entoneprice,$plcprice,$mocaprice); // get variables from result.
           $stbstmt->fetch();

        }}



     if ($stmt = $mysqli->prepare("SELECT status,name,raw_pkgname,trackingnumber,mid,update_time,start_date,nextdue,addon,acid,stblist,maxstb FROM members_pkg WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $aid); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();
	//echo '<pre>'.$stmt->num_rows.'</pre>';
        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($status,$username,$pkgname,$track,$mid,$update_time,$startdate,$nextdue,$addonstr,$acid,$stblist,$maxstb); // get variables from result.
           $stmt->fetch();


	   // Check if it's within this account, otherwise we get a weak hack
	   if($mid != $user_id){
		log_bad("Tried to access other pkgid",$mysqli,$cus_name);
	   }


   echo page_head(true,false,$cus_name);

echo '<script type="text/javascript" src="sha512.js"></script>
        <script type="text/javascript" src="forms.js"></script>';

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
                        /*$(\'[class^="dstb"]\').each(function(){
                                 var field = $(this).attr(\'id\');
                                 $(this).load("refreshstb.php?field="+field);
                        })*/
			
			$(".maxstbnum").load("refresh_maxstb.php", { \'pkgid\': \''.$aid.'\'}, function(response) {
			});

			$(".stbtableloading").show();
			$(".stbtable").load("refresh_stbtable.php", { \'pkgid\': \''.$aid.'\'}, function(response) {';

					
echo '							if (response.indexOf("data-ref=\"Y\"") >= 0){
								//location.reload();
							}
				$(".stbtableloading").hide();
			});

			$(\'input[type="radio"]\').change(function(){
			    if($(this).attr("id")=="option1"){
				$("#buyflag").attr(\'value\', \'Y\');
				$(".selectstb").show();
			    }
			    if($(this).attr("id")=="option2"){
				$("#buyflag").attr(\'value\', \'N\');
				$(".selectstb").hide();
			    }
			    CalculateTotal(); 
			});

			$("#addnewstbbtn").click(function(event) {
				  if( parseInt($(".maxstbnum").text()) >=4 ){
				     ';
				     if($cus_type=="shipcustomer" && $cus_agent==""){
echo '				     	alert("Sorry, to add more than 4 STB boxes per household, please contact your service provider at:\n\n '.$cus_agcontact.'");';
				     }else{
echo '				     	alert("Sorry, to add more than 4 STB boxes per household, please contact your service provider at:\n\n Zazeen Inc. \nToll Free: +1 877 814 0280 \n Toronto: +1 416 628 0945 \n FAX: +1 905 275 2713 \nWEBSITE: www.zazeen.com");';
				     }
echo '				  }else{' ;
				if($cus_type=="shipcustomer" && $cus_agent==""){
echo '				     	alert("To add a STB box, please contact your service provider at:\n\n '.$cus_agcontact.'");';
				}else{					
					echo '	//Can add
						$("#myModal").modal("show");';
				}
echo '				  }
			});

			//Bind function btn
			$(\'.stbtable\').on(\'click\', \'.dslotbtn\', function() {
				var classname = $(this).attr(\'id\');
				var dslotid = classname.substring(5);
				var loadtr = "slot"+dslotid;


				var cof = confirm(\'This action will REMOVE this STB box slot, are you sure to continue?\');
				if(cof == true){
					$(".dslotloader").load("delete_cli_stbslot.php", { \'pkg\': \''.$aid.'\', \'slot\': dslotid}, function(response) {
						$(".stbtable").load("refresh_stbtable.php", { \'pkgid\': \''.$aid.'\'}, function(res) {
							if (res.indexOf("data-ref=\"Y\"") >= 0){
								//location.reload();
								$(".maxstbnum").load("refresh_maxstb.php", { \'pkgid\': \''.$aid.'\'}, function(response) {
								});
							}
						});
						/*$("."+loadtr).load("refresh_slot.php", { \'pkg\': \''.$aid.'\', \'slot\': dslotid}, function(res) {
							console.log(res);
							if (res == ""){
								location.reload();
							}
						});*/
					});	
				}else{
					
				}
			});

			//Bind delete STB function btn
			$(\'.stbtable\').on(\'click\', \'.dstbbtn\', function() {
				var classname = $(this).attr(\'id\');
				var dstbid = classname.substring(3);
				//var loadtr = "slot"+dslotid;


				var cof = confirm(\'This action will REMOVE this STB box, are you sure to continue?\');
				if(cof == true){
					$(".dslotloader").load("delete_cli_stb.php", { \'uid\': \''.$dcryaid.'\', \'sid\': dstbid}, function(response) {
						$(".stbtable").load("refresh_stbtable.php", { \'pkgid\': \''.$aid.'\'}, function(res) {
							if (res.indexOf("data-ref=\"Y\"") >= 0){
								//location.reload();
								$(".maxstbnum").load("refresh_maxstb.php", { \'pkgid\': \''.$aid.'\'}, function(response) {
								});
							}
						});
					});	
				}else{
					
				}
			});

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
                        /*$(\'[class^="dstb"]\').each(function(){
                                 var field = $(this).attr(\'id\');
                                 $(this).load("refreshstb.php?field="+field);
                        })*/

			$(".maxstbnum").load("refresh_maxstb.php", { \'pkgid\': \''.$aid.'\'}, function(response) {
			});

			$(".stbtable").load("refresh_stbtable.php", { \'pkgid\': \''.$aid.'\'}, function(response) {';
		if($_GET['stb']=="Y"){
				echo '	window.location = window.location.href.split("&")[0];';
		}

echo'							if (response.indexOf("data-ref=\"Y\"") >= 0){
								//location.reload();
							}
			});
                        //Tricky call back, to ensure 1st function load on page load
                        return refinfo;
                }(),10000); //refresh every 5 seconds, and tricky brackets here


		function getPathFromUrl(url) {
		  return url.split("?")[0];
		}


		function refstbinfo(){
                    	//$(".maxstbnumloading").show();
                    	//$(".stbtableloading").show();

			$(".stbreqloader").load("ref_cli_stb.php", { \'pkg\': \''.$aid.'\'}, function(response) {
				$(".maxstbnum").load("refresh_maxstb.php", { \'pkgid\': \''.$aid.'\'}, function(response) {
				});
		
				$(".stbtable").load("refresh_stbtable.php", { \'pkgid\': \''.$aid.'\'}, function(response) {
				});
                        });
		}';
	
		

echo '	</script>';

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
                      </tr>';
if($cus_agent=="shipagent"){
	echo '                      <tr>';
}else{
	echo '                      <tr style="display:none;">';
}

echo '                        <td>Max STB count:</td>
                        <td><div class="maxstbnum" style="float:left;">'.$maxstb.'</div><div class="maxstbnumloading" style="display:none;float:left;"><img id="maxstb-img" src="ajax-loader.gif"></img></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button style="display:none;" type="button" class="btn btn-success btn-mini refstbbtn" onclick="refstbinfo();">Refresh STB info</button></td>
                      </tr>
                  </table>
                </div><br>';



            echo'        <div class="clearfix"></div>'.$n;


            echo'        <div class="stbreqloader" style="display:none;"></div>'.$n;



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
			$alabel = "label-default";
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


	if($_GET['stb']=="Y"){
		echo "<div class=\"alert alert-info alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><strong>Success:</strong> order will be processed within 3 business days, details will be sent to your email address.</div>";
	}


	     //$addstbtn = '<form style="margin: 0 0 1px;" action="add_cli_stb.php" method="post" onsubmit="return confirm(\'This action will REMOVE this STB box, are you sure to continue?\');"><input type="hidden" id="sid" name="sid" value="'.$estbid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Add New STB" class="btn btn-success"/></form>';
//<button class="btn btn-success" id="addnewstbbtn" data-toggle="modal" data-target="#myModal">
$addstbtn = '<!-- Button trigger modal -->
<button class="btn btn-success" id="addnewstbbtn">
  Add New STB
</button>';

	   echo '<br><div class="pull-right">'.$addstbtn.'
			<button type="button" class="btn btn-success refstbbtn" onclick="refstbinfo();">Refresh STB info</button>
			</div>';

echo '<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New STB</h4>
      </div>
      <form name=form1 id=form1 method=post action=\'add_cli_stb.php\'>
      <div class="modal-body">';



$stblist_r_prev = explode("+",$stblist);
$precount = sizeof($stblist_r_prev);
$precount = $maxstb;
?>

<table class='' style="width:85%;margin-left:15%;">
<tr>
        <td colspan=4>
        &nbsp;
	<input type="hidden" name="buyflag" id="buyflag" value="Y" />
	<input type="hidden" name="pkg" id="pkg" value="<?php echo $aid;?>" />
	<input type="hidden" name="maxstb" id="maxstb" value="<?php echo $maxstb;?>" />
        </td>
</tr>
<tr style="<?php print $billaghide;?>">
        <td colspan=4>
		<div class="btn-group" data-toggle="buttons">
		  <label class="btn btn-success active">
		    <input type="radio" name="options" id="option1" checked> Order one from Zazeen.
		  </label>
		  <label class="btn btn-success">
		    <input type="radio" name="options" id="option2"> Use my own STB.
		  </label>
		</div>
        </td>
</tr>
<tr>
        <td colspan=4>
        &nbsp;
        </td>
</tr>
<tr class="selectstb">
        <td colspan=2>
                <span name=itemname id=itemname>STB type</span>:
        </td>
        <td colspan=2>
                <select name=stbtype id=stbtype onchange='CalculateTotal()'>
                        <?php	if($cus_agent=="billagent" || $cus_agent=="shipagent"){
				}else{
					print "<option value='1' name=wncitem id=wncitem>&nbsp;&nbsp;WNC&nbsp;&nbsp;</option>\n";
				}
			?>
                        <option value='2' name=entoneitem id=entoneitem>&nbsp;&nbsp;entone&nbsp;&nbsp;</option>
<!--                        <option value='3' name=plc id=plc disabled>&nbsp;&nbsp;P L C&nbsp;&nbsp;</option>
                        <option value='4' name=moca id=moca disabled>&nbsp;&nbsp;MOCA&nbsp;&nbsp;</option>-->
                </select>
        </td>
</tr>
<tr>
        <td colspan=2>
                Count:
        </td>
        <td colspan=2>
                <select name=count id=count onchange='CalculateTotal()'>
                        <option value='1' id=o1 <?if($precount>3){ print 'disabled';}?>>&nbsp;&nbsp;1&nbsp;&nbsp;<?if($precount>3){ print '*';}?></option>
                        <option value='2' id=o2 <?if($precount>2){ print 'disabled';}?>>&nbsp;&nbsp;2&nbsp;&nbsp;<?if($precount>2){ print '*';}?></option>
                        <option value='3' id=o3 <?if($precount>1){ print 'disabled';}?>>&nbsp;&nbsp;3&nbsp;&nbsp;<?if($precount>1){ print '*';}?></option>
                        <option value='4' id=o4 <?if($precount>0){ print 'disabled';}?>>&nbsp;&nbsp;4&nbsp;&nbsp;<?if($precount>0){ print '*';}?></option>
                </select>
        </td>
</tr>
<tr>
        <td colspan=4>
        &nbsp;
        </td>
</tr>
<tr id=a1 style='<?php print $billaghide;?>color:gray;font-size:12px'>
        <td colspan=2>
        &nbsp;
        </td>
        <td>
                <label style='margin-left:10px'>Price:</label>
        </td>
        <td>
                <label id=lprice name=lprice></label>
        </td>
</tr>
<tr id=a2 style='<?php print $billaghide;?>color:gray;font-size:12px'>
        <td colspan=2>
        &nbsp;
        </td>
        <td>
                <label style='margin-left:10px'>shipping:</label>
        </td>
        <td>
                <label id=lshipping name=lshipping></label>
        </td>
</tr>
<tr id=a3 style='<?php print $billaghide;?>color:gray;font-size:12px'>
        <td colspan=2>
        &nbsp;
        </td>
        <td>
                <label style='margin-left:10px'>Tax:</label>
        </td>
        <td>
                <label id=ltax name=ltax></label>
        </td>
</tr>
<tr style="<?php print $billaghide;?>">
        <td colspan=2>
        &nbsp;
        </td>
        <td colspan=2>
                <hr width=80% align=right>
        </td>
</tr>
<tr id=a4 style='<?php print $billaghide;?>color:gray;font-size:13px'>
        <td colspan=2>
        &nbsp;
        </td>
        <td>
                <label style='margin-left:10px'>Onetime Total:</label>
        </td>
        <td>
                <label id=ltotal name=ltotal></label>
        </td>
</tr>
<tr id=a4 class="mmtotaldiv" style='<?php print $billaghide;?>color:gray;font-size:13px'>
        <td colspan=2>
        &nbsp;
        </td>
        <td>
                <label style='margin-left:10px'>Monthly:</label>
        </td>
        <td>
                <label id=mmtotal name=mmtotal>$5.00 / Month</label>
        </td>
</tr>
<tr style="<?php print $billaghide;?>">
        <td colspan=4>
        &nbsp;<p>* For more than 4 STB boxes per account, please contact our agent to increase the STB count.</p>
        </td>
</tr>
</table>

<?php		
echo'      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" onclick=\'addstbhash(this.form, this.form.count, this.form.maxstb);\'>Submit Order</button>
      </div>
      </form>
    </div>
  </div>
</div>';

?>

<script>
function roundit(num, dec)
{
        var result = String(Math.round(num*Math.pow(10,dec))/Math.pow(10,dec));
        if(result.indexOf('.')<0)
                {result+= '.';}
        while(result.length- result.indexOf('.')<=dec)
                {result+= '0';}
        return result;
}
function GetTax(Province,Country)
{
        var sProvince=Province.trim();
        sProvince=sProvince.replace(/[^a-zA-Z ]/,'');
        sProvince=sProvince.replace(/ +/," ");
        var i="ON";
        var gst=0.0;
        var pst=0.0;
        if (sProvince.match(/^Yu|\bYK\b/i))             {gst=5.0;pst=0.0;i="YK"};
        if (sProvince.match(/\bNT\b|^north/i))          {gst=5.0;pst=0.0;i="NT"};
        if (sProvince.match(/^Nu/i))                    {gst=5.0;pst=0.0;i="NU"};
        if (sProvince.match(/^al|\bAB\b/i))             {gst=5.0;pst=0.0;i="AB"};
        if (sProvince.match(/^mani|\bMB\b/i))           {gst=5.0;pst=0.0;i="MB"};
        if (sProvince.match(/^sas|\bSK\b/i))            {gst=5.0;pst=0.0;i="SK"};
        if (sProvince.match(/\bPE\b|^Prin/i))           {gst=5.0;pst=0.0;i="PE"};
        if (sProvince.match(/^q/i))                     {gst=5.0;pst=0.0;i="QC"};
        if (sProvince.match(/\bBC\b|^briti/i))          {gst=5.0;pst=7.0;i="BC"};
        if (sProvince.match(/^on/i))                    {gst=5.0;pst=8.0;i="ON"};
        if (sProvince.match(/\bNB\b|^New B|^newb/i))    {gst=5.0;pst=8.0;i="NB"};
        if (sProvince.match(/\bNL\b|^New f|^newf/i))    {gst=5.0;pst=8.0;i="NL"};
        if (sProvince.match(/\bNS\b|^Nov/i))            {gst=5.0;pst=10.0;i="NS"};
        var tax=gst+pst;
        return (tax);
}
function CalculateTotal()
{
        var price;
        var shipping;
        var subtotal;
        var total;
	var montht;

	var mprice=0;
        if (document.getElementById('stbtype').value=='1'){
                price=<?print $wncprice?>;
		mprice=6.95;
	}
        if (document.getElementById('stbtype').value=='2'){
                price=<?print $entoneprice?>;
		mprice=7.95;
	}

        if (document.getElementById('stbtype').value=='3')
                price=<?print $plcprice?>;
        if (document.getElementById('stbtype').value=='4')
                price=<?print $mocaprice?>;
        shipping=9.95;
        price=price*document.getElementById('count').value;
	montht=mprice*document.getElementById('count').value;

	if($('#buyflag').attr("value") == "N"){
		//show only monthly fee
		$('#lprice').text("$0.00");
		$('#lshipping').text("$0.00");
		$('#ltax').text("$0.00");
		$('#ltotal').text("$0.00");

		if (typeof document.getElementById('mmtotal').innerText == 'undefined')
			document.getElementById('mmtotal').textContent="$"+roundit(montht,2)+" / Month";
		else
			document.getElementById('mmtotal').innerText="$"+roundit(montht,2)+" / Month";
	}else{

		if (typeof document.getElementById('lprice').innerText == 'undefined')
			document.getElementById('lprice').textContent="$"+roundit(price,2);
		else
			document.getElementById('lprice').innerText="$"+roundit(price,2);
		if (typeof document.getElementById('lshipping').innerText == 'undefined')
			document.getElementById('lshipping').textContent="$"+roundit(shipping,2);
		else
			document.getElementById('lshipping').innerText="$"+roundit(shipping,2);
		subtotal=price+shipping;
		tax=subtotal*GetTax("<?print $cus_address?>","<?print $cus_country?>")/100.0;
		if (typeof document.getElementById('ltax').innerText == 'undefined')
			document.getElementById('ltax').textContent="$"+roundit(tax,2);
		else
			document.getElementById('ltax').innerText="$"+roundit(tax,2);
		total=subtotal+tax;
		if (typeof document.getElementById('ltotal').innerText == 'undefined')
			document.getElementById('ltotal').textContent="$"+roundit(total,2);
		else
			document.getElementById('ltotal').innerText="$"+roundit(total,2);

		if (typeof document.getElementById('mmtotal').innerText == 'undefined')
			document.getElementById('mmtotal').textContent="$"+roundit(montht,2)+" / Month";
		else
			document.getElementById('mmtotal').innerText="$"+roundit(montht,2)+" / Month";
		$(".mmtotaldiv").hide();
        }

}
CalculateTotal();
</script>



<?php

	   echo '<div class="stbtableloading" style="display:none;"><img id="stbtable-img" src="ajax-loader.gif"></img></div>';
	   echo '<div class="dslotloader" style="display:none;"></div>';
	   echo '<div class="stbtable">';
	   if($stblist != "" && 2==1){
		   $stbbtn = '<a herf="#">Delete[not functional yet]</a>';
		   $stblist_r = explode("+",$stblist);
		   $curstbcount = sizeof($stblist_r);
		echo ' <table class="table table-striped" style="width:100%;table-layout:fixed;">
                      <caption><h3>STB Boxes List ( '.$curstbcount.' / '.$maxstb.' )</h3><br></caption>
                    <thead>
                      <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 20%">MAC</th>
                        <th style="width: 20%">IP</th>
                        <th style="width: 15%">MODEL</th>
                        <th style="width: 15%">Last Seen</th>
                        <th style="width: 20%;text-align: right;">Modify</th>
                      </tr>
                    </thead>
                    <tbody>';

		   foreach($stblist_r as $stb_e){
			$stb = explode(",",$stb_e);

			   $stb3 = $stb[3];
			   if($stb[3]==""){
				$stb3 = "N/A";
			   }

			   $stb4="";
			   if(isset($stb[4])){
				$beforedash = explode("_",$stb[4]);
				$stb4=$beforedash[0];
			   }

			   $estbid = $converter->encode($stb[0]);
			   $stbtn = '<form style="margin: 0 0 1px;" action="delete_cli_stb.php" method="post" onsubmit="return confirm(\'This action will REMOVE this STB box, are you sure to continue?\');"><input type="hidden" id="sid" name="sid" value="'.$estbid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Remove" class="btn-mini btn-danger"/></form>';
			echo '<tr class="dstb" id="'.$estbid.'">
                                <td>'.$stb[0].'</td>
                                <td>'.$stb[1].'</td>
                                <td><span class="label label-success">'.$stb[2].'</span></td>
                                <td>'.$stb4.'</td>
                                <td>'.$stb3.'</td>
                                <td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right">'.$stbtn.'</div></td>
                              </tr>';				
		   }

//		   $stbtn2 = '<form style="margin: 0 0 1px;" action="delete_cli_stbslot.php" method="post" onsubmit="return confirm(\'This action will REMOVE this STB box slot, are you sure to continue?\');"><input type="hidden" id="pkg" name="pkg" value="'.$aid.'"><input type="submit" name="submit" value="Remove" class="btn-mini btn-danger"/></form>';
		   $dslot = 0;
//		   if($_GET["dslot"] == "Y"){$dslot=1;}
		   for($l=0;$l<($maxstb-$curstbcount);$l++){
		   	$stbtn2='<button type="button" class="btn-mini btn-danger dslotbtn" id="dslot'.$l.'">Remove</button>';

			echo '<tr class="slot'.$l.'" id="slot'.$l.'">
                                <td>Empty Slot</td>
                                <td>Waiting on registration</td>
                                <td><span class="label label-success">Waiting on registration</span></td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right">'.$stbtn2.'</div></td>
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

	echo '</div>';

   echo page_foot($ajax);
} else {
	   log_bad("Tried to directly access pkg page",$mysqli); 

}


?>
