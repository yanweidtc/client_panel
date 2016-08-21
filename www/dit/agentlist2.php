<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
require_once "class.rc4crypt.php";

sec_session_start();
if(login_check() == true) {
   global $n;
      $user_id = $_SESSION['user_id'];
	$stmt = new DB_Sql;
	if ($stmt->query("SELECT id, type, username, agentID, monthlyfee, onetimeRFee, activeaccounts, addonfee, addonpackages, pendinginvoice, update_time, mainAgent,subAgent,newsignup,billable from agents where id = '$user_id'")) {
		$stmt->next_record();
		$monthfee = $stmt->f("monthlyfee");
		$activeaccount = $stmt->f("activeaccounts");
		$addonfee = $stmt->f("addonfee");
		$addonpackages = $stmt->f("addonpackages");
		$pdis = $stmt->f("pendinginvoice");
		$update_time = $stmt->f("update_time");
		$onetimeRF = $stmt->f("onetimeRFee");
		$agent_id = $stmt->f("agentID");
		$magent_id = $stmt->f("agentID");
		$uname = $stmt->f("username");
		$utype = $stmt->f("type");
		$magent = $stmt->f("mainAgent");
		$newsignup = $stmt->f("newsignup");
		$ubillable = $stmt->f("billable");

		$subagent= $stmt->f("subAgent");

	$today = date('M');
	$month = date('F');
	$fullday = date("Y-m-d");

	if($utype == "subagent"){
                $agent_id = $magent."-".$user_id;
                $magent_id = $magent;
        }

   echo page_head(true,true,$uname);

        echo '<div id="alertdiv"></div>';
        echo '<div id="refreshbtndiv" class="pull-right"><button onclick="myFunction()" class="btn btn-primary">Reload info</button></div>';
	echo '<div class="cxlistdiv"></div>';
	echo '<div class="cxchdiv2" style="display:none;"></div>';
	echo '<div class="funcbtnloader" style="display:none;"></div>';

	echo '<iframe id="ifrloader" style="display:none;" src="https://interface.acanac.com/zazeenlogin.php?user=masteradmin&password=CAgxaDVYmzg9GWdLa43FehCKXvmZwtQd&directxml=1&item=<function>accountinfo</function><accountid>1070</accountid><agent>623411</agent>"></iframe>';

//	echo '<div id="frmtexter"></div>';


/*
	echo '<script type="text/javascript">
		function myFunction() {
		    location.reload();
		}

		function getXML() {
		    var iframe = document.getElementById(\'ifrloader\');
		    var frameBody = iframe.contentWindow.document.body;

		    // remove unneeded toggle links +/-
		    var links = frameBody.getElementsByTagName(\'a\');
		    for (var i = links.length - 1; i >= 0; i--) {
			if(links[i].className === \'b\') {
			    links[i].parentNode.removeChild(links[i]);
			}
		    }

		    // display parsed XML source
		    console.log(frameBody.innerText);

		    // parse cleaned highlight code source to new XML document
		    var xml = $.parseXML(area.value);
		    alert(xml);
		}


		$(document).ready(function(){

			$("#frmtexter").text("Go");
			$(\'iframe#ifrloader\').load(function() {
				console.log($(\'#ifrloader\').innerText);
				$("#frmtexter").html($(\'#ifrloader\').innerText);
		        });
//			$("#frmtexter").html($(\'#ifrloader\').contents().find("html").html());
		});
        </script>';
*/				


	echo '<script type="text/javascript">
		function myFunction() {
		    location.reload();
		}

		$(document).ready(function(){
			// A initial load
			$(".cxchdiv2").load("agent_request.php", { \'req\':1, \'agentid\':\''.$agent_id.'\',\'data\': \'\' }, function() {
			});


			$(".cxlistdiv").load("agentlistdiv.php", { \'sel\': \'go\'}, function(response) {
				if (response.indexOf("Customer List") >= 0){
						//Bind function btn
						$(\'.cxlistdiv\').on(\'click\', \'.funcbtn\', function() {
							var classname = $(this).attr(\'id\');
							var dcheckid = classname.substring(3);
							var act = classname.substring(0,3);

							//some loader
							var obtndiv= "obtn"+dcheckaid;
                                                        var limgdiv= "limg"+dcheckaid;

                                                        var actstr = "";
                                                        if(act=="sus"){
                                                                actstr="suspendaccount";
                                                        }else if(act=="res"){
                                                                actstr="unsuspendaccount";
                                                        }else if(act=="tem"){
                                                                actstr="terminateaccount";
                                                        }else if(act=="utm"){
                                                                actstr="unterminateaccount";
                                                        }else{
                                                                //Report Invalid action
                                                        }

					
							var url = "https://interface.acanac.com/zazeenlogin.php?user=masteradmin&password=CAgxaDVYmzg9GWdLa43FehCKXvmZwtQd&directxml=1&item=<function>"+actstr+"</function><accountid>"+dcheckaid+"</accountid><agent>'.$magent_id.'</agent>";
                                                        var el = document.getElementById(\'ifrloader\');
                                                        el.src = url;

                                                        $("."+obtndiv).hide();
                                                        $("."+limgdiv).show();
                                                        $(\'iframe#ifrloader\').load(function() {
                                                                $("."+limgdiv).hide();
                                                                location.reload();
                                                        });
						});

				}
			});

			var refreshId = setInterval(function()
			{
				$(".cxlistdiv").load("agentlistdiv.php", { \'sel\': \'go\'}, function(response) {
					if (response.indexOf("Customer List") >= 0){
	//						console.log("success");
						//success return, slow down


						//Bind function btn
						$(\'.cxlistdiv\').on(\'click\', \'.funcbtn\', function() {
							var classname = $(this).attr(\'id\');
							var dcheckaid = classname.substring(3);
							var act = classname.substring(0,3);
							var obtndiv= "obtn"+dcheckaid;
							var limgdiv= "limg"+dcheckaid;

							var actstr = "";
							if(act=="sus"){
								actstr="suspendaccount";
							}else if(act=="res"){
								actstr="unsuspendaccount";
							}else if(act=="tem"){
								actstr="terminateaccount";
							}else if(act=="utm"){
								actstr="unterminateaccount";
							}else{	
								//Report Invalid action
							}
					
							var url = "https://interface.acanac.com/zazeenlogin.php?user=masteradmin&password=CAgxaDVYmzg9GWdLa43FehCKXvmZwtQd&directxml=1&item=<function>"+actstr+"</function><accountid>"+dcheckaid+"</accountid><agent>'.$magent_id.'</agent>";
							var el = document.getElementById(\'ifrloader\');
							el.src = url;
							
							$("."+obtndiv).hide();	
							$("."+limgdiv).show();	
							$(\'iframe#ifrloader\').load(function() {
								$("."+limgdiv).hide();	
								location.reload();
							});

						});

						clearInterval(refreshId);
						/*refreshId = setInterval(function()
						{
							$(".cxlistdiv").load("agentlistdiv.php", { \'sel\': \'go\'}, function(response) {
							});
						}, 1200000);*/

					}
				});
			}, 5000);


			
		});
	</script>';

   }
       echo page_foot($ajax);

} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}


?>

