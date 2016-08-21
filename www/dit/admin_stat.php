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
                $magent_id = $agent_id;
                $utype = $stmt->f("type");
                $magent = $stmt->f("mainAgent");
        }
   echo page_head(true,true,$uname);
      echo '<script type="text/javascript">
         var break_link=true;
         </script>';


        echo '<script type="text/javascript">
        $(document).ready(function(){
//                $(\'[class^="sub"]\').hide();

                //$(".rewrap").load("admin_statresult.php", { \'queue\':\'ALL Queues\',\'date\':\'Today\'  }, function() {
                //});


                $(".dform").submit(function(e){
                        e.preventDefault();
                        //console.log("click");
                  var queText = $("#queue").text().trim();
                  if(queText=="Select Queue"){
                        alert("Please select a Queue");
                  }else{
                        $(".timewrap").hide();
                        $(\'#loadingImg\').show();
                        $(".rewrap").hide();
                        var shText = $(".ddate").val().trim();
                        $(".rewrap").load("admin_statresult.php", { \'queue\':queText,\'date\': shText }, function() {
                                $(\'#loadingImg\').hide();
                                $(".rewrap").show();
                        });
                  }
                });
        });
        </script>';


function toMin ($hms) {
      if(floor($hms / 60) >= 10){
        return "<font color=red>".floor($hms / 60).":".sprintf("%02d",($hms % 60))."</font>";
      }else{
        return floor($hms / 60).":".sprintf("%02d",($hms % 60));
      }
}


$timestamp = 0;




   echo     '        <div class="pull-left">'.$n;
        echo '  <ul class="nav nav-pills">
          <li class="shortcut defaultsh">
            <a href="#">Today</a>
          </li>
          <li class="shortcut">
            <a href="#">Yesterday</a>
          </li>
          <li class="shortcut">
            <a href="#">Week</a>
          </li>
          <li class="shortcut">
            <a href="#">Month</a>
          </li>
          <li class="shortcutsp">
            <a href="#">Specific</a>
          </li>
        </ul>';
   echo        '        </div>'.$n;

        echo '<div class="clearfix"></div>';

        echo '<script type="text/javascript">
        </script>';

        echo '<script type="text/javascript">
                $(".shortcut").click(function(){
                  $(".timewrap").hide();
                        $(\'#loadingImg\').show();
                        $(".rewrap").hide();
                        $(".shortcut").removeClass("active");
                        $(".shortcutsp").removeClass("active");
                        $(this).addClass("active");
                        var shText = $(this).text().trim();
                        //console.log(queText+shText);
			var queText="detail";
                        $(".rewrap").load("admin_statresult.php", { \'queue\':queText,\'date\':shText  }, function() {
                                $(\'#loadingImg\').hide();
                                $(".rewrap").show();
                        });
                });

                $(".shortcutsp").click(function(){
		  var queText="detail";
                  if(queText=="Select Queue"){
                        alert("Please select a Queue");
                  }else{
                        $(".timewrap").show();
                        $(".rewrap").hide();
                        $(".shortcut").removeClass("active");
                        $(this).addClass("active");
                  }
                });



                $(".spbtn").click(function(){
                        //console.log("click");
		  var queText="detail";
                  if(queText=="Select Queue"){
                        alert("Please select a Queue");
                  }else{
                        $(".timewrap").hide();
                        $(\'#loadingImg\').show();
                        $(".rewrap").hide();
                        var shText = $(ddate).text().trim();
                        $(".rewrap").load("admin_statresult.php", { \'queue\':queText,\'date\': shText }, function() {
                                $(\'#loadingImg\').hide();
                                $(".rewrap").show();
                        });
                  }
                });
        </script>';

//Time Range
        echo '<div class="timewrap span12" style="display:none;margin-left: 0px;">
        <div class="col-md-4 col-md-offset-4">
        <form class="form-horizontal dform" action="" method="post" name="div_form">
        <fieldset>
           <div class="control-group">
           <label class="control-label" for="ddate">Date Range:</label>
             <div class="controls">
                <input type="text" id="ddate" name="ddate" class="ddate form-control" value="'.date("Y-m-d").'" />
             </div>
           </div>
           </br>
           <div class="control-group">
                <button id="spbtn" value="submit" class="btn btn-primary spbtn pull-right">Generate Report</button>
           </div>
        </fieldset>
        </form>
        </div>
                </div>';



print '<link rel="stylesheet" media="screen" type="text/css" href="/css/datepicker.css" />
<script type="text/javascript" src="/js/datepicker.js"></script>';
print '<script type="text/javascript">
     jQuery.curCSS = jQuery.css;
</script>';

print   '<script>
                $(\'#ddate\').DatePicker({
                format:\'Y-m-d\',
                date: \''.date("Y-m-d").'\',
                current: \''.date("Y-m-d").'\',
                starts: 1,
                calendars: 2,
                mode: \'range\',
                position: \'right\',
                onBeforeShow: function(){
                        //$(\'#ddate\').DatePickerSetDate($(\'#ddate\').val(), true);
                        //$(\'#date\').DatePickerSetDate($(\'#date\').val(), true);
                },
                onChange: function(formated, dates){
                        $(\'#ddate\').val( formated.join(\' ,\'));
                }
                });
        </script>';


//Loading Image
        echo '<div id="loadingImg" class="span12" style="display:none;margin-left: 0px;"><img style="display: block;margin-left: auto;margin-right: auto;" src="images/loading.gif"></div>';

//Main load div
        echo '<div class="rewrap span12" style="margin-left: 0px;"></div>';
        echo '<div class="clearfix"></div>';



   echo page_foot($ajax);
} else {
	   echo 'You are not authorized to access this page, please login with the main account. <a href="login.php">Back</a> <br/>';
}


?>

