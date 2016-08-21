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
        $stmt->query("SELECT id, type, username, agentID from agents where id = '$user_id'");
        $stmt->next_record();
		$agent_id = $stmt->f("agentID");
                $uname = $stmt->f("username");
                $utype = $stmt->f("type");

   if($utype != "main"){
	echo 'You are not authorized to access this page. <br/>'; 
   }else{

if(isset($_POST['username'],$_POST['email'], $_POST['p'])) {
   $username = $_POST['username'];
   $email = $_POST['email'];
   $password = $_POST['p']; // The hashed password.

        // Create a random salt
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        // Create salted password (Careful not to over season)
        $password = hash('sha512', $password.$random_salt);

        // Insert to database. 
	$insert_stmt = new DB_Sql;
        if ($insert_stmt->query("INSERT INTO agents (username, email, agentID, password, salt, type, mainAgent) VALUES ('".$username."', '".$email."', '-1', '".$password."', '".$random_salt."', 'sub', '".$agent_id."')")) {
           $insert_stmt->free();
        }
        header('Location: ./main.php');

} else {
   echo page_head(true,true,$uname);

   $rstar = '&nbsp;<font color="red">*</font>';


   echo '<script type="text/javascript" src="sha512.js"></script>
         <script type="text/javascript" src="forms.js"></script>';

   echo '<script type="text/javascript" src="./js/jsDatePick.jquery.min.1.3.js"></script>';
   echo '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjcMdLBu7FRwxgSQOI1qdGE-ktBg0pvMg&sensor=false"></script>';
   //echo '<script type="text/javascript" src="https://zazeen.com/postal_code.php?js=1"></script>';
   echo '<script type="text/javascript" src="./js/postal.js"></script>';

   echo '<script>

		function processreturn(){
			var servername="";
			var arr = document.cookie.split(\';\');
			for(var i=0;i < arr.length ; i++) {
				var c = arr[i];
				while (c.charAt(0)==\' \') c = c.substring(1,c.length);
				if (c.indexOf(\'ServerName=\') == 0) servername=c.substring(11,c.length);
			}
			servername=servername.replace(/\+/g,\' \');
			servername=servername.replace(/%3A/gi,\':\');
			servername=unescape(servername);
			document.getElementById(\'extra\').value=servername;
		}

		function process(){
				if (document.getElementById(\'extra\').value=="")
				{
					document.getElementById(\'k14\').innerHTML="<img src=/collect.php onload=processreturn()>";
				}
		}
	</script>';


echo '		
		<span name=k14 id=k14 style=\'display:none\'></span> <span name=k15 id=k15 style=\'display:none\'><img src="../tips.gif" onload=\'process()\'></span>
<div align="center">

          <form method="post" id="oform" action="post2.php">
            <input type="hidden" value="beb1c93e2bda4f227b5375497e8f1999" name="sid">
            <input style="display:none" value="" id="extra" name="extra">
            <input type="hidden" value="iptv" name="p_formdb">
            <link href="/jsDatePick_ltr.min.css" media="all" type="text/css" rel="stylesheet"><select size="1" name="CREDIT_TYP" style="display:none">
                <option value="---select-one---">---select-one--- </option>
                <option selected="" value="Visa">Visa </option>
                <option value="Mastercard">Mastercard </option>
                <option value="Amex">Amex</option>
                </select><input type="text" id="CSV" name="CSV" style="display:none"><table width="778" cellspacing="6" cellpadding="6" border="0" bgcolor="#efefef" style="display:table;border: solid 1px #777777;" id="pg_789c622134" class="phpForms_main">
              <tbody><tr>
                <td valign="top" align="center" colspan="2"><span class="phpForms_pgtitle"><strong>Zazeen IP TV Beta Order Form</strong></span></td>
              </tr>
              <tr>
                <td valign="top" align="center" colspan="2">&nbsp;</td>
              </tr>
              <tr> 
                
                <!-- Page title -->
                
                <td valign="top" align="center" colspan="2"><table width="765" cellspacing="5" cellpadding="5" border="0">
                  <tbody><tr>
                    <td><div align="left">For every month that we stay in Beta you will receive a $10.00 credit.   Upon completion of  beta your accumulated credits will be used to   discount your 
monthly IPTV service by $10 until they are exhausted.   Zazeen anticipates to stay in Beta for at least 3 months. These credits   are not real-world currency and have no monetary value. You will not 
be charged unitl the day your STB is shipped. We anticpate to ship out the first STB\'s within the next few weeks. Visit our forums for updates and timelines.</div></td>
                  </tr>
                </tbody></table></td>
                </tr>
              
              <!-- /Page title -->
              
              <tr> 
                
                <!-- Page top text -->
                
                <td align="center" colspan="2"></td>
                </tr>
              
              <!-- /Page top text -->
              
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left">&nbsp;</td>
                <td bgcolor="#ffffff" align="left">                
              </td></tr><tr>
                <td width="364" valign="top" bgcolor="#ffffff" align="left"><p><span style=""> &nbsp;Province:</span> <font color="red">*</font><br>
                </p></td>
                <td width="370" bgcolor="#ffffff" align="left"><select onchange="CalculateTotal();" onfocusout="CalculateTotal();" id="STATE_PROV" name="STATE_PROV">
                  <option selected="" value="---select-one---">---select-one---</option>
                  <option selected="" value="ON">Ontario</option>
                  <option value="QC">Quebec</option>
                  <!--                  <option value="AB" >Alberta</option>
                  <option value="BC" >British Columbia</option>
                  <option value="MB" >Manitoba</option>
                  <option value="NB" >New Brunswick</option>
                  <option value="NL" >Newfoundland and Labrador</option>
                  <option value="NS" >Nova Scotia</option>
                  <option value="NU" >Nunavut</option>
                  <option value="PE" >Prince Edward Island</option>
                  <option value="SK" >Saskatchewan</option>
                  <option value="YT" >Yukon</option>
                  <option value="NT" >Northwest Territories</option>
-->
                  </select>
                  </td></tr><tr>
                    
                  </tr><tr style="display:none">
                    <td valign="top" bgcolor="#ffffff" align="left"><p> </p><p> &nbsp; Activation Phone Number or<br>
                       &nbsp;Naked DSL/Dry Loop*: <font color="red">*</font><font color="red"><br>
                    </font></p></td>
                    <td bgcolor="#ffffff" align="left"><input type="text" value="1111111111" maxlength="33" size="33" name="PHONE_NUMB"></td>
                  </tr>
              <tr style="">
                <td valign="top" bgcolor="#ffffff" align="left">&nbsp;Package Term: <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><select onchange="CalculateTotal();" onfocusout="CalculateTotal();" size="1" name="TERM" style="background-color:silver">
                  <!--                  <option value="---select one---">---select one---</option>-->
                  <option value="1 Month Term">1 Month Term</option>
                  <!--                  <option value="3 Month Term">3 Month Term</option>
                  <option value="6 Month Term">6 Month Term</option>
                  <option value="12 Month Term">12 Month Term</option>
-->
                  </select></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;IPTV package type: <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><select onchange="CalculateTotal();" onfocusout="CalculateTotal();" size="1" id="PACKAGE_TYPE" name="PACKAGE_TYPE" style="background-color:silver">
                  <!--                  <option selected="selected" value="---select-one---">---select-one---</option>-->
                  <option value="Basic">Basic $(49.95)</option>
                  <!--                  <option value="Advanced">Advanced</option>-->
                  </select>
                </td></tr>
              <tr>
                <td valign="top" align="left"><div id="provv" name="provv" style="display: block;"> &nbsp;Addon channels: <font color="red"></font></div></td>
<td align="left">
<div style="display: none;" id="onaddons" name="onaddons">
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1001" name="pkg1001">Bell Addon: $9.95 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1002" name="pkg1002">Blue Ant Addon: $0.99 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1003" name="pkg1003">ZoomerMedia Addon: $0.75 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1004" name="pkg1004">Astral English Addon: $4.99 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1005" name="pkg1005">Astral French Addon: $2.99 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1011" name="pkg1011">TVA Addon: $2.99 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1007" name="pkg1007">Super Ecran Addon: $12.99 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1008" name="pkg1008">Standalone Addon: ichannel (Issue Channel) $0.99<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1009" name="pkg1009">The Movie Network Package $12.99<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1010" name="pkg1010">Standalone Addon: Sportsnet World $29.95<br>
<br>
</div>
<div style="display: block;" id="qcaddons" name="qcaddons">
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1014" name="pkg1014">Bell Addon: $9.95 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1015" name="pkg1015">Blue Ant Addon: $0.99 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1016" name="pkg1016">ZoomerMedia Addon: $0.75 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1017" name="pkg1017">Astral English Addon: $4.99 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1019" name="pkg1019">Super Ecran Addon: $12.99 p/month<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1020" name="pkg1020">Standalone Addon: ichannel (Issue Channel) $0.99<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1021" name="pkg1021">The Movie Network Package $12.99<br>
<input type="checkbox" onchange="CalculateTotal();" value="1" id="pkg1022" name="pkg1022">Standalone Addon: Sportsnet World $29.95<br>
<br>
</div>
</td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><font size="-1">  &nbsp;Current Internet Service Provider:</font><font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><select onchange="sendwarning();" name="USERNAME"><option value="--select one--">--select one--</option><option value="Acanac">Acanac</option><option value="Distibutel">Distributel</option><option value="Xinflix">Xinflix</option><option value="Others">Other</option></select><font size="-2" color="grey"><span id="isp" name="isp"><br>You can order required Internet Service through:<br><a target="_blank" href="http://www.distributel.ca">Distributel</a><br><a target="_blank" href="http://www.acanac.com">Acanac Inc.</a><br><a target="_blank" href="http://www.xinflix.com">Xinflix Media Inc.</a><br></span><br>
</font></td>

                </tr>
              <script>

//CalculateTotal();

</script>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Number of required Set Top Box units:<font color="red">*</font><br>
                   &nbsp;
                  <br>                    <br></td>
                <td bgcolor="#ffffff" align="left"><select onchange="CalculateTotal();" onfocusout="CalculateTotal();" size="1" id="STB_COUNT" name="STB_COUNT">
                  <option value="--select one--">--select one--</option>
                  <option selected="" value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
</select>
                  <table width="382" cellspacing="5" cellpadding="5" border="0">
                    <tbody><tr>
                      <td width="362">Please note that a monthly fee for each aditional box has not yet been established. Prices due to
additional bandwidth usage are still to be determined during the beta test phase.<br>
<br></td>
                    </tr>
                  </tbody></table></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Preferred STB unit<font size="-2" color="grey"> (IPTV <br>
                   &nbsp;streaming  &nbsp;device)</font>: <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><select onchange="CalculateTotal();" onfocusout="CalculateTotal();" size="1" name="SHIP_DSL_M">
                  <option value="WNC $75.00 +  9.95 Shipping fee">WNC $75.00 + 9.95 Shipping fee</option>
                  <option value="Entone $100.00 +  9.95 Shipping fee(Not Available)">Entone $75(Regular:$100.00) + 9.95 Shipping fee</option>
                  <option disabled="" value="Amino $150.00 +  9.95 Shipping fee(Not Available)">Amino $150.00 + 9.95 Shipping fee(Not available)</option>
                </select></td>
                </tr>
              <tr style="display:none">
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Local Phone Provider: <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="Bell" maxlength="25" size="25" name="LOCAL_PHON"></td>
                </tr>

<script src="/js/jsDatePick.jquery.min.1.3.js" type="text/javascript"></script>
<script type="text/javascript">
        window.onload = function(){
                new JsDatePick({
                        useMode:2,
                        target:"ACTIVATIONDATE",
                        dateFormat:"%Y-%m-%d"
                        /*selectedDate:{                                This is an example of what the full configuration offers.
                                day:5,                                          For full documentation about these settings please see the full version of the code.
                                month:9,
                                year:2006
                        },
                        yearsRange:[1978,2020],
                        limitToToday:false,
                        cellColorScheme:"beige",
                        dateFormat:"%m-%d-%Y",
                        imgPath:"img/",
                        weekStartDay:1*/
                });
        };
</script>
	<tr>
                <td align="left" bgcolor="#ffffff" valign="top"> &nbsp;Preferred activation date(YYYY/mm/dd): <br>
                  <br></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" id="ACTIVATIONDATE" name = "ACTIVATIONDATE" size = "10" maxlength = "66" value = "'.date("Y-m-d").'" /></td>
                </tr>
              <tr>
              <tr>
                <td bgcolor="#ffffff" colspan="2"><br>
                  <center>
                    <b>Customer Information</b>
                    </center>
                  <br></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Company: <br>
                  <br></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="66" size="44" name="COMPANY"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;First Name: <font color="red">*<br>
                  <br>
                </font></span></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="66" size="44" name="FIRST_NAME"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;Last Name:</span> <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="66" size="44" name="LAST_NAME"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Work Phone:<br>
                  <br></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="66" size="44" name="WORK_PHONE"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;Home Phone #:</span> <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="66" size="44" name="HOME_PHONE"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;Street Number:</span> <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="25" size="25" name="STREET_NUM"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Street Name: <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="66" size="44" name="STREET_ADD"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Street Type: <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><select size="1" name="STREET_TYP">
                  <option value="---select one---" selected="selected">---select one--- </option>
                  <option value="AV">AV </option>
                  <option value="BL">BL </option>
                  <option value="CH">CH </option>
                  <option value="CIR">CIR </option>
                  <option value="Cote">Cote </option>
                  <option value="CR">CR </option>
                  <option value="CTR">CTR </option>
                  <option value="DR">DR </option>
                  <option value="GATE">GATE </option>
                  <option value="GDNS">GDNS </option>
                  <option value="GRV">GRV </option>
                  <option value="HWY">HWY </option>
                  <option value="Line">Line </option>
                  <option value="LN">LN </option>
                  <option value="MTEE">MTEE </option>
                  <option value="PKWY">PKWY </option>
                  <option value="PL">PL </option>
                  <option value="PRIV">PRIV </option>
                  <option value="RD">RD </option>
                  <option value="RDWY">RDWY </option>
                  <option value="ROW">ROW </option>
                  <option value="RTE">RTE </option>
                  <option value="RUE">RUE </option>
                  <option value="SD">SD </option>
                  <option value="SDRD">SDRD </option>
                  <option value="SQ">SQ </option>
                  <option value="ST">ST </option>
                  <option value="TERR">TERR </option>
                  <option value="TRL">TRL </option>
                  <option value="WAY">WAY</option>
                  <option value="OTHER">OTHER</option>
                  </select></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Buzzer Code #:<br>
                  <br></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="25" size="25" name="BUZZER_COD"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Apartment Number:<br>
                  <br></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="30" size="30" name="APT_OR_UNI"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;City:</span> <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="66" size="44" name="CITY"></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;Postal Code:</span> <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" onchange="resetbind()" maxlength="66" size="44" name="POSTAL_COD" id="POSTAL_COD">&nbsp;&nbsp;&nbsp;<button onclick="checkZip()" id="check_zip" class="btn btn-mini btn-primary" style="margin-top: -10px;" type="button">Check Availability</button><div id="check_done"></div><div class="pull-right" id="zip" style="display:none;"></div></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;Country:</span> <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left">
                  
                  <select style="background-color:silver" id="COUNTRY" name="COUNTRY">
                    <option selected="selected" value="CANADA">CANADA</option>
                    </select>
                  </td></tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;E-mail Address:</span> <font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><input type="text" value="" maxlength="66" size="44" name="E_MAIL_ADD"></td>
                </tr>
              
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;Agent ID: </span><font color="red">*<br>
                  <br>
                </font></td>
                <td bgcolor="#ffffff" align="left"><input id="AGENTID" name="AGENTID" value='.$user_id.' readonly></td>
                </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" align="left"><span style=""> &nbsp;Additional Comments</span><br>
                  <br>
                  <br>
                  <br>
                  <br>
<br></td>
                <td bgcolor="#ffffff" align="left"><textarea cols="44" rows="7" name="COMMENTS"></textarea></td>
                </tr>
		<tr>
                	<td valign="top" bgcolor="#ffffff" align="left">
				<span id="pricebreakdown" name="pricebreakdown" title="5">Price breakdown:</span>
			</td>
			<td valign="top" bgcolor="#ffffff" align="left">
				<div id="subtotal" name="subtotal"><script>//CalculateTotal()</script><table width="350px" cellspacing="0" cellpadding="0" bordercolor="#000000" border="1" style="FONT-SIZE: x-small;border-bottom-style:none;border-left-style:none" bordercolorlight="#000000" class="priceclass"><tbody><tr style="HEIGHT: 25px"><td style="BORDER-LEFT: solid thin">Item</td><td>Term</td><td>Monthly fee</td><td>Total for term $CAD</td></tr><tr style="HEIGHT:25px"><td style="BORDER-LEFT: solid thin">Basic</td><td>1 Month Term</td><td>49.95</td><td>49.95</td></tr>
<tr style="HEIGHT:25px"><td style="BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin">Set-top box fee</td><td style="BORDER-BOTTOM: solid thin">One time</td><td>One time</td><td>75.00</td></tr>
<tr style="HEIGHT:25px"><td style="BORDER-LEFT: solid thin">Activation Fee</td><td style="">One time</td><td>One time</td><td>0.00</td></tr>
<tr style="HEIGHT:25px"><td style="BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin">Shipping fee</td><td style="BORDER-BOTTOM: solid thin">One time</td><td>One time</td><td>9.95</td></tr>
<tr style="HEIGHT:25px"><td style="BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin">Addon</td><td style="BORDER-BOTTOM: solid thin">1 Month Term</td><td>0.00</td><td>0.00</td></tr>
<tr style="HEIGHT: 25px"><td style="BORDER-BOTTOM-STYLE: none;border-left-style:none" rowspan="2" colspan="2"> </td><td style="BORDER-LEFT: solid thin">Tax</td><td>6.25</td></tr><tr style="HEIGHT: 25px"><td style="BORDER-BOTTOM: solid thin;BORDER-LEFT: solid thin"><span>Total $CAD</span></td><td style="BORDER-BOTTOM: solid thin">131.15</td></tr>
</tbody></table></div>
			</td>
                </tr>
              <tr>
                <td align="center" class="phpForms_main" colspan="2"><label>
                  <textarea id="User Policeis" readonly="readonly" rows="10" style="width:100%;margin-left: -5px;" name="User Policeis"> 
                  
The rendering of an invoice(s) by Zazeen Inc. shall be construed as an offer to extend this agreement and the payment of such invoice(s) by customer shall be construed as an acceptance. If such invoice(s) are not paid within fifteen (15) days of presentment, in the legal money of Canada, Zazeen Inc. may terminate this agreement and discontinue services.

 Limitation of Liability and Indemnification

Neither Zazeen Inc. nor its officers, directors or employees may be held liable for (i) any claim, damage, or loss (including but not limited to profit loss), or (ii) any damage as a result of service outage, data loss. The customer hereby waives any and all such claims or causes of action, arising from or relating to any service outage and unless it is proven that the act or omission proximately causing the claim, damage, or loss constitutes gross negligence, recklessness, or intentional misconduct on the part of Zazeen Inc.. Subject to the provisions of this agreement, Zazeen Inc. does not provide any other warranties of any kind either express or implied, including without limitation the warranties of merchantability and fitness for a particular purpose. 

The customer agrees to defend, indemnify, and hold harmless Zazeen Inc., its officers, directors, employees, affiliates, agents, legal representatives and any other service provider who offers services to the customer or Zazeen Inc. in relation with the present agreement or the service provided, from any and all claims, losses, damages, fines, penalties, costs and expenses (including, without limitation, legal fees and expenses) by, or on behalf of, the customer, any third party or user of the customers\' service relating to the absence, failure or outage of the service.

1. Confidentiality and information security policy.Online shopping with Zazeen Inc is secure. Personal and credit card information entered on the site is transmitted electronically in a format that cannot be intercepted, altered or decoded by a third party as it is encrypted to ensure its confidentiality. Zazeen Inc. complies with SSL encryption standards, under which transaction information will always be transmitted securely. 

2.0 Shipping - Zazeen only will only ship items within canada. STBs shipped within 5 - 7 business days unless otherwise advised by a customer service representative.

Auto Downgrades
 Any client that is on a term larger then 1 month and has outstanding invoices will be automatically downgraded to the month to month term. This means that if you normally pay annually and your credit card fails we will automatically start to bill you month to month on the monthly rate. If you wish to cancel please e-mail billing@Zazeen.com and request cancelation.

The decisions made by the Canadian Radio and Television Commission (the "CRTC") and other regulatory bodies with jurisdiction over Zazeen and its services affect the contracts for services you have contracted for with us and Zazeen\'s costs of providing them . Therefore all cited prices for all services Zazeen offers to you either on an initial term or on a renewal of your contract will be increased immediately in the event that the CRTC or other regulatory bodies may issue orders that may apply to the services you contract for or the price for those services. The increases in costs to Zazeen for providing those services will be passed on to you, the consumer. Zazeen Inc. therefore reserves the right to increase the rates you pay and increase the amount on your bills to reflect these increased costs 

Even if Zazeen were to be found to be negligent or at fault, Zazeen Inc. shall not be liable for more than a refund of the monies paid by Customer to Zazeen. Zazeen makes no representation as to the merchantability or fitness for any purpose of the Phone or DSl service and ancillary derive to be provided to customer. 

Customer agrees to comply with all applicable governmental laws in their use of their service and ancillary services provided by Zazeen Inc., and, in the event of any non-compliance, agrees to hold harmless Zazeen and its personnel and contractors from the consequence of such non-compliance.

If any action in law or equity is instituted by either party here to with respect to the subject matter of this agreement, the prevailing party shall be entitled to recover, in addition to any other relief granted, reasonable attorney\'s fees, legal costs, and expenses reasonably incurred. This is the entire agreement. It may not be changed orally. Any waiver, alteration, or modification of any of the provisions of this agreement will not be valid unless in writing signed by both parties. 

Billing. All terms are due up front. If you sign up for the lowest rate then you are likely on the 1 year term. You will be billed the entire term up front and then billed on a yearly basis. All credit cards are billed automatically on their renewal dates. If you do not want to renew your account please cancel the account on or before the renewal date. Cancellations must be done by e-mail and sent to accounting@Zazeen.com or billing@Zazeen.com. Please make sure you obtain the cancellation ID or ticket number for your request to confirm cancellation of service. 

Promo Codes. Promo codes are only applicable for the first term. After the initial term is over the accounts will auto renew at the regular rate. If you wish to cancel please do so by contacting billing@Zazeen.com. Please note that any customer that has already used the promo code once will not be able to sign up using the promo code again. Any client who attempts to cancel service and initiate again to obtain the promo price will be refused.

Furthermore, promo prices are only applicable once per household. Any attempt to place orders with a different name at an address previously supplied with service will also be denied. 

Disclosure. We may disclose the personal information of the client such as the client\'s identity and the clients address and phone numbers and related information without the knowledge or consent of the client when 

a) we are required to comply with a subpoena or warrant issued or an order made by a court, person or body with jurisdiction to compel the production of information, or 

b) to comply with rules of court relating to the production of records; or c) made to a government institution or part of a government institution that has made a request for the information, identified its lawful authority to obtain the information and indicated that 

(i) it suspects that the information relates to national security, the defence of Canada or the conduct of international affairs, 

(ii) the disclosure is requested for the purpose of enforcing any law of Canada, a province or a foreign jurisdiction, carrying out an investigation relating to the enforcement of any such law or gathering intelligence for the purpose of enforcing any such law, or 

(iii) the disclosure is requested for the purpose of administering any law of Canada or a province; 

30 Day Money Back. If you are not satisfied with our service for any reason within the first 30 days, Zazeen will provide a full refund. . After the initial 30 day period a customer must complete the remainder of the term. Customer(s) may choose to cancel the account prior to the end of term however, no refund will be issued for that period. Zazeen Inc. understands that due to the nature of our technology and affiliations with other partners, issues can occur which are out of our control. In such cases Zazeen Billing department may choose at our sole discretion to grant refunds on a case by case basis. Furthermore the 30 day unconditional money back guarentee does not apply for renewals. Customers must terminate the agreement before or on the renewal date. Should a client forget to terminate before the renewal date, clients will be required to pay each additional month of service ( at the monthly rate ) and the remainder will be refunded. We also provide a full refund for the STV if cancellation is made within the 30 day no obligation period. After the initial 30 day period Zazeen Inc. will not accept the return of your hardware. Shipping and Handling fees are non-refundable.

Should a client purchase an STB from Zazeen, but decide that they wish to return it must notify us in writing with 10 days of the original purchase date to qualify for a refund. After 10 days, the modem fee is non-refundable. Equipment that is purchased directly from Zazeen has full 1 year warranty, but will not cover physical damage to equipment not consistent with normal wear and tear. As long as your internet services are active with Zazeen and the modem was purchased directly from us, we will troubleshoot any equipment troubles and will provide a replacement modem should one be required, free of additional cost. The defective modem must be returned to Zazeen Inc within 30 days of receiving the replacement equipment. 

Disclaimer

Zazeen Inc.cannot be held liable for system downtime, crashes, or data loss. We cannot be held liable for any predicted estimate of profits in which a client would have gained if their service was functioning. Thus, certain equipment, routing, software, and programming used by Zazeen Inc. are not directly owned and written by Zazeen Inc.. Moreover, Zazeen Inc.holds no responsibility for the use of our clients accounts. If any terms or conditions are failed to be followed, the account in question will be automatically deactivated. We reserve the right to remove any account without advanced notice for any reason without restitution as Zazeen Inc.sees fit.

Suspension of Service. The Company reserves the right to suspend the Service, in whole or in part, including any features, at any time in the Company\'s sole and absolute discretion. If the Company determines that the suspension of the Service is without fault of the Customer, then the Customer may request a credit of the monthly charges for each day the Service was not in effect 

Furthermore: Zazeen Inc. retains the right to change any or all of the above Policies, Guidelines, and Disclaimers without notification. Zazeen Inc. reserves the right to terminate or discontinue the Service at any time, for any reason or for no reason, in the Company\'s sole and absolute discretion. If the Company discontinues or terminates the Service without fault of the Customer, the Customer will only be responsible for usage charges accrued while the Service was in effect and the Customer will be entitled to a credit for the unused portion of the final month\'s charges. 

This agreement shall be governed by the laws of the province of Ontario, Canada, and in the event any litigation must be initiated to reinforced the terms of this agreement, said legal action must be brought in the courts of the Province of Ontario.

Email: info@Zazeen.com
</textarea>
                  </label></td>
                </tr>
              <tr>
                <td align="center" class="phpForms_main" colspan="2"><input type="checkbox" style="" name="box_confirm_df210783a8" id="box_confirm_df210783a8">
                  <label for="box_confirm_df210783a8"> I accept the User Policies</label></td>
                </tr>
              
              <!-- /Page bottom text -->
              
              </tbody></table>
&nbsp;&nbsp;&nbsp;
		<input type = "hidden" name = "CREDIT_TYP" size = "44" maxlength = "77" value = "Visa" />
		<input type = "hidden" name = "NAME_ON_CA" size = "33" maxlength = "55" value = "No Name" />
		<input type = "hidden" name = "CARD_NUMBE" size = "44" maxlength = "77" value = "1111222233334444" />
		<input type = "hidden" name = "YEAR01" size = "44" maxlength = "77" value = "01" />
		<input type = "hidden" name = "YEAR" size = "44" maxlength = "77" value = "14" />
		
              <div align="center">
                <input type="submit" style="width:7pc" name="func" value="Submit">
                <input type="reset" style="width:7pc" name="Reset" value="Reset">
              </div>
          </form>

          </div>';
echo '<span  style=\'display:none\' name=pricebreakdown id=pricebreakdown ><strong>Price breakdown</strong></span><br>
                        <div name=subtotal id=subtotal style=\'display:none\'><script>//CalculateTotal()</script>';

   echo '<script type="text/javascript">
		window.onload = function(){
			new JsDatePick({
				useMode:2,
				target:"ACTIVATIONDATE",
				dateFormat:"%Y-%m-%d"
			});
		};


		
		function sendwarning()
		{
			var forma12;
			var forma13;
			forma13=document.getElementsByTagName("form");
			forma12=forma13[formnumber];
			if (forma12.USERNAME.value=="Others")
			{
				document.getElementById(\'isp\').innerHTML="<br>You can order required Internet Service through:<br><a href=http://www.distributel.ca target=_blank>Distributel</a><br><a href=http://www.acanac.com target=_blank>Acanac Inc.</a><br><a href=http://www.xinflix.com target=_blank>Xinflix Media Inc.</a><br>";
				alert(\'Currently the service is only available through the Service Providers listed in the Drop Down Menu.\');

			}
		}


		
		String.prototype.trim = function () {
		    return this.replace(/^\s*/, "").replace(/\s*$/, "");
		}

		function roundit(num, dec) {
		  var result = String(Math.round(num*Math.pow(10,dec))/Math.pow(10,dec));
		  if(result.indexOf(\'.\')<0) {result+= \'.\';}
		  while(result.length- result.indexOf(\'.\')<=dec) {result+= \'0\';}
		  return result;
		}

		function GetTax(Province,Country)
		{
			var sProvince=Province.trim();
			sProvince=sProvince.replace(/[^a-zA-Z ]/,\'\');
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



		//The next line for combo boxes
		var formnumber=0;       //The order the form you want to monitor apear in source
		var bReturn ;
		var colorBase = "White";
		var MAX = 22;
		var EmailIndex = 15;
		var CheckAll = new Array(MAX);
		var extraval = new Array(MAX);
		var active = new Array(MAX);
		for (var i=0;i<MAX;i++)
		{
			extraval[i]="";
			active[i]=true;
		}

		CheckAll[0] = "STATE_PROV";
		extraval[0] = "---select-one---";
		CheckAll[1] = "PHONE_NUMB";
		active[1]=false;
		CheckAll[2] = "LOCAL_PHON";
		active[2]=false;
		CheckAll[3] = "FIRST_NAME";
		CheckAll[4] = "PACKAGE_TYPE";
		active[4] = false;
		extraval[4] = "---select-one---";
		CheckAll[5] = "LAST_NAME";
		CheckAll[6] = "TERM";
		active[6]=false;
		extraval[6] = "---select one---";
		CheckAll[7]= "SHIP_DSL_M";
		extraval[7] = "---selec-one---";
		CheckAll[8] = "STREET_NUM";
		CheckAll[9] = "STREET_ADD";
		CheckAll[10] = "STREET_TYP";
		extraval[10] = "---select one---";
		CheckAll[11] = "CITY";
		CheckAll[12] = "POSTAL_COD";
		CheckAll[13]= "COUNTRY";
		active[13]= false;
		CheckAll[14]= "HOME_PHONE";
		CheckAll[15]= "E_MAIL_ADD";
		//CheckAll[16]= "CREDIT_TYP";
		//extraval[16] = "---select-one---";
		CheckAll[16]= "CARD_NUMBE";
		CheckAll[17]= "YEAR01";
		extraval[17] = "---select-one---";
		CheckAll[18]= "YEAR";
		extraval[18] = "---select-one---";
		CheckAll[19]= "NAME_ON_CA";
		CheckAll[20]= "STB_COUNT";
		extraval[20] = "--select one--";
		CheckAll[21]= "USERNAME";
		extraval[21] = "--select one--";

		//The next line for combo boxes
		var formnumber=0;       //The order the form you want to monitor apear in source


		var Price = new Array(40);
		var tc = new Array(10);
		for (var i = 0; i < 4; i++) {
			Price[i] = new Array(10);
			for (var j = 0; j < 10; j++) {
				Price[i][j] = "0";
			}
		}
		Price[0][0]=49.95;
		Price[0][1]=41.95;
		Price[0][2]=49.95;
		Price[0][3]=32.95;

		Price[1][0]=49.95;
		Price[1][1]=47.95;
		Price[1][2]=45.95;
		Price[1][3]=43.95;

		Price[2][0]=52.95;
		Price[2][1]=50.95;
		Price[2][2]=48.95;
		Price[2][3]=46.95;


		Price[3][0]=59.95;
		Price[3][1]=57.95;
		Price[3][2]=55.95;
		Price[3][3]=29.95;

		for (var i = 0; i < 10; i++) {
			tc[i] = new Array(10);
			for (var j = 0; j < 10; j++) {
				tc[i][j] = " ";
			}
		}



		var js;
		js="";
		var maxp=6;
		var prov1 = new Array(maxp);


		
		function CalculateTotal(){
		var forma12;
		var forma13;
		var myform;
		var s1;
		var t;
		var tt;
		var term2,term1; 
		if (document.getElementById("STATE_PROV")!= null) 
			if (document.getElementById("STATE_PROV").value==\'ON\')
			{
				document.getElementById("onaddons").style.display=\'block\';
				document.getElementsByName("provv")[0].style.display=\'block\';
				document.getElementById("qcaddons").style.display=\'none\';
			}
			else
				if (document.getElementById("STATE_PROV").value==\'QC\')
				{
					document.getElementById("onaddons").style.display=\'none\';
					document.getElementsByName("provv")[0].style.display=\'block\';
					document.getElementById("qcaddons").style.display=\'block\';
				}
				else
				{
					document.getElementById("onaddons").style.display=\'none\';
					document.getElementsByName("provv")[0].style.display=\'none\';
					document.getElementById("qcaddons").style.display=\'none\';
				}

		forma13=document.getElementsByTagName("form");
		forma12=forma13[formnumber];
		myform = forma12;
		if (typeof(myform)=="undefined")
			 return;
		s = "myform."+CheckAll[5];
		if (myform.PACKAGE_TYPE.length==1)
		{
			tt=0;
		}
		else
		{
			tt=1;
		}
		//document.getElementById("subtotal").innerHTML="Total: "+ eval(s+".value");
		s1="";
		//alert(myform.TERM.value);
		var incomplete=\'?\';
		tc[1][3]=incomplete;
		tc[1][0]=myform.PACKAGE_TYPE.value;
		tc[1][1]=myform.TERM.value;
		var h=25;
		if (myform.TERM.value==\'---select one---\')
		{
			s1=s1+"<font color=#808080>Waiting for Term<br></font>";
			tc[1][1]=incomplete;
		}
		if (myform.PACKAGE_TYPE.value==\'---select-one---\')
		{
			s1=s1+"<font color=#808080>Waiting for DSL Type</font><br>";
			tc[1][0]=incomplete;
		}
		//...................................
		prov=myform.STATE_PROV.value;
		//console.log(myform.POSTAL_COD.value);
		//country=myform.COUNTRY.value;
		country="CANADA";
		prov=prov.replace(/[^a-zA-Z ]/,\'\').trim();
		if (prov=="" || prov==\'--select-one---\')
		{
			tc[3][3]=incomplete;
			tc[4][3]=incomplete;
			taxpercent=-1;
		}
		else
		{
			taxpercent=GetTax(prov,country);
		}
		document.getElementById("pricebreakdown").title=taxpercent;
		if (myform.SHIP_DSL_M.value==\'---selec-one---\')
		{
			s1=s1+"<font color=#808080>Waiting for Ship DSL Modem option</font>\n";
			tc[2][1]=incomplete;
			tc[2][2]=incomplete;
			tc[2][3]=incomplete;
			tc[5][1]=incomplete;
			tc[5][2]=incomplete;
			tc[5][3]=incomplete;
		}
		else
		{
			var base;
			base=0.00;
			if(myform.SHIP_DSL_M.value.match(/WNC.*/))
			{
				base=75.00;
			}
			if(myform.SHIP_DSL_M.value.match(/Entone.*/))
			{
				base=100.00;
				base=75.00;
			}
			if(myform.SHIP_DSL_M.value.match(/Amino.*/))
			{
				base=150.00;
			}
			if(base>0)
			{
				tc[2][3]=roundit(base,2);
				if (myform.STB_COUNT.value==\'1\' ||myform.STB_COUNT.value==\'2\' ||myform.STB_COUNT.value==\'3\' ||myform.STB_COUNT.value==\'4\' ||myform.STB_COUNT.value==\'5\')
				{
					tc[2][3]=roundit(tc[2][3]*myform.STB_COUNT.value,2);
				}
				tc[2][1]=\'One time\';
				tc[2][2]=\'One time\';
				tc[5][3]=9.95;
				tc[5][1]=\'One time\';
				tc[5][2]=\'One time\';
			}
			else
			{
				tc[2][3]=0;
				tc[2][1]=\'NA\';
				tc[2][2]=\'NA\';
				tc[5][3]=0;
				tc[5][1]=\'NA\';
				tc[5][2]=\'NA\';
			}
		}

		if (tc[1][0]!=incomplete && tc[1][1]!=incomplete)
		{
			t=myform.PACKAGE_TYPE.selectedIndex-tt;
			term2=myform.TERM.value;
			term1=term2.split(\' \');
			tc[1][3]=roundit(term1[0]*Price[t][myform.TERM.selectedIndex-tt],2);
			tc[1][2]=Price[t][myform.TERM.selectedIndex-tt];
		}
		else
		{
			tc[1][2]=incomplete;
		}

		tc[7][0]="Addon";
		tc[7][1]="1 Month Term";
		tc[7][2]=0.00;
		tc[7][3]=0.00;
		var qc;
		var on;
		qc=false;
		on=false;
		if (document.getElementById("STATE_PROV").value==\'QC\') qc=true;
		if (document.getElementById("STATE_PROV").value==\'ON\') on=true;
		if (document.getElementById("pkg1001")!= null) if (document.getElementById("pkg1001").checked && on)	tc[7][3]=tc[7][3]+1.0*9.95;
		if (document.getElementById("pkg1002")!= null) if (document.getElementById("pkg1002").checked && on)	tc[7][3]=tc[7][3]+1.0*0.99;
		if (document.getElementById("pkg1003")!= null) if (document.getElementById("pkg1003").checked && on)	tc[7][3]=tc[7][3]+1.0*0.75;
		if (document.getElementById("pkg1004")!= null) if (document.getElementById("pkg1004").checked && on)	tc[7][3]=tc[7][3]+1.0*4.99;
		if (document.getElementById("pkg1005")!= null) if (document.getElementById("pkg1005").checked && on)	tc[7][3]=tc[7][3]+1.0*2.99;
		if (document.getElementById("pkg1006")!= null) if (document.getElementById("pkg1006").checked && on)	tc[7][3]=tc[7][3]+1.0*12.99;
		if (document.getElementById("pkg1007")!= null) if (document.getElementById("pkg1007").checked && on)	tc[7][3]=tc[7][3]+1.0*12.99;
		if (document.getElementById("pkg1008")!= null) if (document.getElementById("pkg1008").checked && on)	tc[7][3]=tc[7][3]+1.0*0.99;
		if (document.getElementById("pkg1009")!= null) if (document.getElementById("pkg1009").checked && on)	tc[7][3]=tc[7][3]+1.0*12.99;
		if (document.getElementById("pkg1010")!= null) if (document.getElementById("pkg1010").checked && on)	tc[7][3]=tc[7][3]+1.0*29.95;
		if (document.getElementById("pkg1011")!= null) if (document.getElementById("pkg1011").checked && on)	tc[7][3]=tc[7][3]+1.0*2.99;

		if (document.getElementById("pkg1014")!= null) if (document.getElementById("pkg1014").checked && qc)	tc[7][3]=tc[7][3]+1.0*9.95;
		if (document.getElementById("pkg1015")!= null) if (document.getElementById("pkg1015").checked && qc)	tc[7][3]=tc[7][3]+1.0*0.99;
		if (document.getElementById("pkg1016")!= null) if (document.getElementById("pkg1016").checked && qc)	tc[7][3]=tc[7][3]+1.0*0.75;
		if (document.getElementById("pkg1017")!= null) if (document.getElementById("pkg1017").checked && qc)	tc[7][3]=tc[7][3]+1.0*4.99;
		if (document.getElementById("pkg1018")!= null) if (document.getElementById("pkg1018").checked && qc)	tc[7][3]=tc[7][3]+1.0*12.99;
		if (document.getElementById("pkg1019")!= null) if (document.getElementById("pkg1019").checked && qc)	tc[7][3]=tc[7][3]+1.0*12.99;
		if (document.getElementById("pkg1020")!= null) if (document.getElementById("pkg1020").checked && qc)	tc[7][3]=tc[7][3]+1.0*0.99;
		if (document.getElementById("pkg1021")!= null) if (document.getElementById("pkg1021").checked && qc)	tc[7][3]=tc[7][3]+1.0*12.99;
		if (document.getElementById("pkg1022")!= null) if (document.getElementById("pkg1022").checked && qc)	tc[7][3]=tc[7][3]+1.0*29.95;

		tc[7][3]=roundit(tc[7][3],2);
		tc[7][2]=tc[7][3];

		tc[6][3]=roundit(0.00,2);
		if (myform.PACKAGE_TYPE.value.match(/silver|gold/i))
		{
			tc[6][3]=75.95;
			//tc[6][3]=0.0;
		}
		if (s1=="")
		{
			term2=myform.TERM.value;
			term1=term2.split(\' \');
			t=myform.PACKAGE_TYPE.selectedIndex-tt;
			tc[1][3]=roundit(term1[0]*Price[t][myform.TERM.selectedIndex-tt],2);
			tc[1][2]=Price[t][myform.TERM.selectedIndex-tt];
			//alert(tc[1][3]+" "+tc[1][2]);
			if (taxpercent!=-1)
			{
				tc[3][3]=roundit((1.0*tc[1][3]+1.0*tc[2][3]+1.0*tc[5][3]+1.0*tc[6][3]+1.0*tc[7][3])*taxpercent/100.0,2);
				tc[4][3]=roundit(1.0*tc[1][3]+1.0*tc[2][3]+1.0*tc[5][3]+1.0*tc[3][3]+1.0*tc[6][3]+1.0*tc[7][3],2);
			}
		}
		else
		{
			tc[3][3]=incomplete;
			tc[4][3]=incomplete;
		}

		tc[2][0]="Set-top box fee";
		tc[5][0]="Shipping fee";
		tc[6][0]="Activation Fee";
		tc[6][1]="One time";
		tc[6][2]="One time";


		var activationfee="<tr style=\'HEIGHT:"+h+"px\'><td style=\'BORDER-LEFT: solid thin\'>"+tc[6][0]+"</td><td style=\'\'>"+tc[6][1]+"</td><td>"+tc[6][2]+"</td><td>"+tc[6][3]+"</td></tr>\n";
		var shipfeeline="<tr style=\'HEIGHT:"+h+"px\'><td style=\'BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin\'>"+tc[5][0]+"</td><td style=\'BORDER-BOTTOM: solid thin\'>"+tc[5][1]+"</td><td>"+tc[5][2]+"</td><td>"+tc[5][3]+"</td></tr>\n";
		var addonline="<tr style=\'HEIGHT:"+h+"px\'><td style=\'BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin\'>"+tc[7][0]+"</td><td style=\'BORDER-BOTTOM: solid thin\'>"+tc[7][1]+"</td><td>"+tc[7][2]+"</td><td>"+tc[7][3]+"</td></tr>\n";
		var modemfeeline="<tr style=\'HEIGHT:"+h+"px\'><td style=\'BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin\'>"+tc[2][0]+"</td><td style=\'BORDER-BOTTOM: solid thin\'>"+tc[2][1]+"</td><td>"+tc[2][2]+"</td><td>"+tc[2][3]+"</td></tr>\n";
		var taxline="<tr style=\'HEIGHT: "+h+"px\'><td colspan=2 rowspan=2 style=\'BORDER-BOTTOM-STYLE: none;border-left-style:none\'>"+" "+"</td><td style=\'BORDER-LEFT: solid thin\'>Tax</td><td>"+tc[3][3]+\'</td></tr>\';
		var totalline="<tr style=\'HEIGHT: "+h+"px\'><td style=\'BORDER-BOTTOM: solid thin;BORDER-LEFT: solid thin\'><span>Total $CAD</span></td><td style=\'BORDER-BOTTOM: solid thin\'>"+tc[4][3]+"</td></tr>\n";

		document.getElementById("subtotal").innerHTML="<table  class=priceclass width=350px border=1 CellSpacing=0 CellPadding=0 BORDERCOLORLIGHT=#000000 bordercolor=\"#000000\" style=\"FONT-SIZE: x-small;border-bottom-style:none;border-left-style:none\"><tr style=\'HEIGHT: "+h+"px\'><td style=\'BORDER-LEFT: solid thin\'>Item</td><td>Term</td><td>Monthly fee</td><td>Total for term $CAD</td></tr><tr style=\'HEIGHT:"+h+"px\'><td style=\'BORDER-LEFT: solid thin\'>"+tc[1][0]+"</td><td>"+tc[1][1]+"</td><td>"+tc[1][2]+"</td><td>"+tc[1][3]+"</td></tr>\n"+modemfeeline+activationfee+shipfeeline+addonline+taxline+totalline+"</table>";

	}



	function form1_onsubmit(flag) {
	//return false;
	var forma12;
	var forma13;
	forma13=document.getElementsByTagName("form");
	forma12=forma13[formnumber];
	bReturn= true;
	var i;
	var s = "";
	for (i = MAX-1;i>=0;i--)
	{
		if (!active[i])
			continue;
		s = "forma12."+CheckAll[i];
		if (eval(s+".value") =="" || eval(s+".value") == extraval[i] ||(i==EmailIndex && eval(s+".value").indexOf(\'@\')<0 ))
		{
			bReturn = false;
			eval(s+".style.backgroundColor=\'pink\'");
			eval(s+".focus();");
		}
		else
			eval(s+".style.backgroundColor = colorBase");
	}



	if (!forma12.box_confirm_df210783a8.checked)
	{
		forma12.box_confirm_df210783a8.style.backgroundColor=\'pink\';
	}
	else
	{
		forma12.box_confirm_df210783a8.style.backgroundColor = colorBase;
	}
	
	//alert(forma12.NUMBER.value);
/*		if (hex_md5(forma12.NUMBER.value)!=\'2cc494f657691d12e2213f7ab7ff6239\')
		{
			bReturn = false;
			forma12.NUMBER.style.backgroundColor=\'pink\';
			forma12.NUMBER.focus();
		}
		else
			forma12.NUMBER.style.backgroundColor = colorBase;
*/
	
	if (!bReturn)
	{
	    if(flag){
	    	alert("Please complete or correct all fields highlighted in pink");
	    }
	}
	else
	{
		if (!forma12.box_confirm_df210783a8.checked)
		{
			bReturn=false;
			alert("Please review user policies at the bottom of the form and accept it to proceed!");
			forma12.box_confirm_df210783a8.focus();
		}
	}
		if (forma12.USERNAME.value=="Others")
		{
			bReturn=false;
			alert("Currently the service is only available through the Service Providers listed in the Drop Down Menu.");
			forma12.USERNAME.focus();
		}
	if (bReturn)
	{
			//console.log("============"+document.getElementsByName(\'COMMENTS\')[0].value.indexOf(document.getElementById(\'extra\').value));
		if (document.getElementById(\'extra\').value!="" && (document.getElementsByName(\'COMMENTS\')[0].value.indexOf(document.getElementById(\'extra\').value)== -1))
		{
			document.getElementsByName(\'COMMENTS\')[0].value=document.getElementsByName(\'COMMENTS\')[0].value+"\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n"+document.getElementsByName(\'PACKAGE_TYPE\')[0].value+":::"+document.getElementById(\'extra\').value;
		}
	}

if(flag){
	var ppcode = $("#POSTAL_COD").val();
	    if(ppcode == "" || !validPostal(ppcode)){   
		bReturn = false;
		alert("Invalid Postal Code!");
	    }else{
		select_area_init(""+ppcode,true);
	    }
	return false;
}else{
	return bReturn;	
}

}';


echo '	var hexcase = 0; 
	var b64pad  = "";

	function hex_md5(s)    { return rstr2hex(rstr_md5(str2rstr_utf8(s))); }
	function b64_md5(s)    { return rstr2b64(rstr_md5(str2rstr_utf8(s))); }
	function any_md5(s, e) { return rstr2any(rstr_md5(str2rstr_utf8(s)), e); }
	function hex_hmac_md5(k, d)
	  { return rstr2hex(rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d))); }
	function b64_hmac_md5(k, d)
	  { return rstr2b64(rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d))); }
	function any_hmac_md5(k, d, e)
	  { return rstr2any(rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d)), e); }

	function md5_vm_test()
	{
	  return hex_md5("abc").toLowerCase() == "900150983cd24fb0d6963f7d28e17f72";
	}

	function rstr_md5(s)
	{
	  return binl2rstr(binl_md5(rstr2binl(s), s.length * 8));
	}

	function rstr_hmac_md5(key, data)
	{
	  var bkey = rstr2binl(key);
	  if(bkey.length > 16) bkey = binl_md5(bkey, key.length * 8);

	  var ipad = Array(16), opad = Array(16);
	  for(var i = 0; i < 16; i++)
	  {
	    ipad[i] = bkey[i] ^ 0x36363636;
	    opad[i] = bkey[i] ^ 0x5C5C5C5C;
	  }

	  var hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
	  return binl2rstr(binl_md5(opad.concat(hash), 512 + 128));
	}

	function rstr2hex(input)
	{
	  try { hexcase } catch(e) { hexcase=0; }
	  var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
	  var output = "";
	  var x;
	  for(var i = 0; i < input.length; i++)
	  {
	    x = input.charCodeAt(i);
	    output += hex_tab.charAt((x >>> 4) & 0x0F)
		   +  hex_tab.charAt( x        & 0x0F);
	  }
	  return output;
	}


	function rstr2b64(input)
	{
	  try { b64pad } catch(e) { b64pad=\'\'; }
	  var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
	  var output = "";
	  var len = input.length;
	  for(var i = 0; i < len; i += 3)
	  {
	    var triplet = (input.charCodeAt(i) << 16)
			| (i + 1 < len ? input.charCodeAt(i+1) << 8 : 0)
			| (i + 2 < len ? input.charCodeAt(i+2)      : 0);
	    for(var j = 0; j < 4; j++)
	    {
	      if(i * 8 + j * 6 > input.length * 8) output += b64pad;
	      else output += tab.charAt((triplet >>> 6*(3-j)) & 0x3F);
	    }
	  }
	  return output;
	}

	function rstr2any(input, encoding)
	{
	  var divisor = encoding.length;
	  var i, j, q, x, quotient;

	  var dividend = Array(Math.ceil(input.length / 2));
	  for(i = 0; i < dividend.length; i++)
	  {
	    dividend[i] = (input.charCodeAt(i * 2) << 8) | input.charCodeAt(i * 2 + 1);
	  }

	  var full_length = Math.ceil(input.length * 8 /
					    (Math.log(encoding.length) / Math.log(2)));
	  var remainders = Array(full_length);
	  for(j = 0; j < full_length; j++)
	  {
	    quotient = Array();
	    x = 0;
	    for(i = 0; i < dividend.length; i++)
	    {
	      x = (x << 16) + dividend[i];
	      q = Math.floor(x / divisor);
	      x -= q * divisor;
	      if(quotient.length > 0 || q > 0)
		quotient[quotient.length] = q;
	    }
	    remainders[j] = x;
	    dividend = quotient;
	  }

	  var output = "";
	  for(i = remainders.length - 1; i >= 0; i--)
	    output += encoding.charAt(remainders[i]);

	  return output;
	}

	function str2rstr_utf8(input)
	{
	  var output = "";
	  var i = -1;
	  var x, y;

	  while(++i < input.length)
	  {
	    x = input.charCodeAt(i);
	    y = i + 1 < input.length ? input.charCodeAt(i + 1) : 0;
	    if(0xD800 <= x && x <= 0xDBFF && 0xDC00 <= y && y <= 0xDFFF)
	    {
	      x = 0x10000 + ((x & 0x03FF) << 10) + (y & 0x03FF);
	      i++;
	    }

	    if(x <= 0x7F)
	      output += String.fromCharCode(x);
	    else if(x <= 0x7FF)
	      output += String.fromCharCode(0xC0 | ((x >>> 6 ) & 0x1F),
					    0x80 | ( x         & 0x3F));
	    else if(x <= 0xFFFF)
	      output += String.fromCharCode(0xE0 | ((x >>> 12) & 0x0F),
					    0x80 | ((x >>> 6 ) & 0x3F),
					    0x80 | ( x         & 0x3F));
	    else if(x <= 0x1FFFFF)
	      output += String.fromCharCode(0xF0 | ((x >>> 18) & 0x07),
					    0x80 | ((x >>> 12) & 0x3F),
					    0x80 | ((x >>> 6 ) & 0x3F),
					    0x80 | ( x         & 0x3F));
	  }
	  return output;
	}

	function str2rstr_utf16le(input)
	{
	  var output = "";
	  for(var i = 0; i < input.length; i++)
	    output += String.fromCharCode( input.charCodeAt(i)        & 0xFF,
					  (input.charCodeAt(i) >>> 8) & 0xFF);
	  return output;
	}

	function str2rstr_utf16be(input)
	{
	  var output = "";
	  for(var i = 0; i < input.length; i++)
	    output += String.fromCharCode((input.charCodeAt(i) >>> 8) & 0xFF,
					   input.charCodeAt(i)        & 0xFF);
	  return output;
	}


	function rstr2binl(input)
	{
	  var output = Array(input.length >> 2);
	  for(var i = 0; i < output.length; i++)
	    output[i] = 0;
	  for(var i = 0; i < input.length * 8; i += 8)
	    output[i>>5] |= (input.charCodeAt(i / 8) & 0xFF) << (i%32);
	  return output;
	}

	function binl2rstr(input)
	{
	  var output = "";
	  for(var i = 0; i < input.length * 32; i += 8)
	    output += String.fromCharCode((input[i>>5] >>> (i % 32)) & 0xFF);
	  return output;
	}

	function binl_md5(x, len)
	{
	  x[len >> 5] |= 0x80 << ((len) % 32);
	  x[(((len + 64) >>> 9) << 4) + 14] = len;

	  var a =  1732584193;
	  var b = -271733879;
	  var c = -1732584194;
	  var d =  271733878;

	  for(var i = 0; i < x.length; i += 16)
	  {
	    var olda = a;
	    var oldb = b;
	    var oldc = c;
	    var oldd = d;

	    a = md5_ff(a, b, c, d, x[i+ 0], 7 , -680876936);
	    d = md5_ff(d, a, b, c, x[i+ 1], 12, -389564586);
	    c = md5_ff(c, d, a, b, x[i+ 2], 17,  606105819);
	    b = md5_ff(b, c, d, a, x[i+ 3], 22, -1044525330);
	    a = md5_ff(a, b, c, d, x[i+ 4], 7 , -176418897);
	    d = md5_ff(d, a, b, c, x[i+ 5], 12,  1200080426);
	    c = md5_ff(c, d, a, b, x[i+ 6], 17, -1473231341);
	    b = md5_ff(b, c, d, a, x[i+ 7], 22, -45705983);
	    a = md5_ff(a, b, c, d, x[i+ 8], 7 ,  1770035416);
	    d = md5_ff(d, a, b, c, x[i+ 9], 12, -1958414417);
	    c = md5_ff(c, d, a, b, x[i+10], 17, -42063);
	    b = md5_ff(b, c, d, a, x[i+11], 22, -1990404162);
	    a = md5_ff(a, b, c, d, x[i+12], 7 ,  1804603682);
	    d = md5_ff(d, a, b, c, x[i+13], 12, -40341101);
	    c = md5_ff(c, d, a, b, x[i+14], 17, -1502002290);
	    b = md5_ff(b, c, d, a, x[i+15], 22,  1236535329);

	    a = md5_gg(a, b, c, d, x[i+ 1], 5 , -165796510);
	    d = md5_gg(d, a, b, c, x[i+ 6], 9 , -1069501632);
	    c = md5_gg(c, d, a, b, x[i+11], 14,  643717713);
	    b = md5_gg(b, c, d, a, x[i+ 0], 20, -373897302);
	    a = md5_gg(a, b, c, d, x[i+ 5], 5 , -701558691);
	    d = md5_gg(d, a, b, c, x[i+10], 9 ,  38016083);
	    c = md5_gg(c, d, a, b, x[i+15], 14, -660478335);
	    b = md5_gg(b, c, d, a, x[i+ 4], 20, -405537848);
	    a = md5_gg(a, b, c, d, x[i+ 9], 5 ,  568446438);
	    d = md5_gg(d, a, b, c, x[i+14], 9 , -1019803690);
	    c = md5_gg(c, d, a, b, x[i+ 3], 14, -187363961);
	    b = md5_gg(b, c, d, a, x[i+ 8], 20,  1163531501);
	    a = md5_gg(a, b, c, d, x[i+13], 5 , -1444681467);
	    d = md5_gg(d, a, b, c, x[i+ 2], 9 , -51403784);
	    c = md5_gg(c, d, a, b, x[i+ 7], 14,  1735328473);
	    b = md5_gg(b, c, d, a, x[i+12], 20, -1926607734);

	    a = md5_hh(a, b, c, d, x[i+ 5], 4 , -378558);
	    d = md5_hh(d, a, b, c, x[i+ 8], 11, -2022574463);
	    c = md5_hh(c, d, a, b, x[i+11], 16,  1839030562);
	    b = md5_hh(b, c, d, a, x[i+14], 23, -35309556);
	    a = md5_hh(a, b, c, d, x[i+ 1], 4 , -1530992060);
	    d = md5_hh(d, a, b, c, x[i+ 4], 11,  1272893353);
	    c = md5_hh(c, d, a, b, x[i+ 7], 16, -155497632);
	    b = md5_hh(b, c, d, a, x[i+10], 23, -1094730640);
	    a = md5_hh(a, b, c, d, x[i+13], 4 ,  681279174);
	    d = md5_hh(d, a, b, c, x[i+ 0], 11, -358537222);
	    c = md5_hh(c, d, a, b, x[i+ 3], 16, -722521979);
	    b = md5_hh(b, c, d, a, x[i+ 6], 23,  76029189);
	    a = md5_hh(a, b, c, d, x[i+ 9], 4 , -640364487);
	    d = md5_hh(d, a, b, c, x[i+12], 11, -421815835);
	    c = md5_hh(c, d, a, b, x[i+15], 16,  530742520);
	    b = md5_hh(b, c, d, a, x[i+ 2], 23, -995338651);

	    a = md5_ii(a, b, c, d, x[i+ 0], 6 , -198630844);
	    d = md5_ii(d, a, b, c, x[i+ 7], 10,  1126891415);
	    c = md5_ii(c, d, a, b, x[i+14], 15, -1416354905);
	    b = md5_ii(b, c, d, a, x[i+ 5], 21, -57434055);
	    a = md5_ii(a, b, c, d, x[i+12], 6 ,  1700485571);
	    d = md5_ii(d, a, b, c, x[i+ 3], 10, -1894986606);
	    c = md5_ii(c, d, a, b, x[i+10], 15, -1051523);
	    b = md5_ii(b, c, d, a, x[i+ 1], 21, -2054922799);
	    a = md5_ii(a, b, c, d, x[i+ 8], 6 ,  1873313359);
	    d = md5_ii(d, a, b, c, x[i+15], 10, -30611744);
	    c = md5_ii(c, d, a, b, x[i+ 6], 15, -1560198380);
	    b = md5_ii(b, c, d, a, x[i+13], 21,  1309151649);
	    a = md5_ii(a, b, c, d, x[i+ 4], 6 , -145523070);
	    d = md5_ii(d, a, b, c, x[i+11], 10, -1120210379);
	    c = md5_ii(c, d, a, b, x[i+ 2], 15,  718787259);
	    b = md5_ii(b, c, d, a, x[i+ 9], 21, -343485551);

	    a = safe_add(a, olda);
	    b = safe_add(b, oldb);
	    c = safe_add(c, oldc);
	    d = safe_add(d, oldd);
	  }
	  return Array(a, b, c, d);
	}

	function md5_cmn(q, a, b, x, s, t)
	{
	  return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s),b);
	}
	function md5_ff(a, b, c, d, x, s, t)
	{
	  return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
	}
	function md5_gg(a, b, c, d, x, s, t)
	{
	  return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
	}
	function md5_hh(a, b, c, d, x, s, t)
	{
	  return md5_cmn(b ^ c ^ d, a, b, x, s, t);
	}
	function md5_ii(a, b, c, d, x, s, t)
	{
	  return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
	}

	function safe_add(x, y)
	{
	  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
	  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
	  return (msw << 16) | (lsw & 0xFFFF);
	}

	function bit_rol(num, cnt)
	{
	  return (num << cnt) | (num >>> (32 - cnt));
	}



	function validPostal(postal) {
	    var regex = new RegExp(/^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]( )?\d[ABCEGHJKLMNPRSTVWXYZ]\d$/i);
	    if (regex.test(postal)){
		return true;
	    }else{ 
		return false;
	    }
	}


	function checkZip(){
		    var ppcode = $("#POSTAL_COD").val();
                    if(ppcode == "" || !validPostal(ppcode)){   alert("Invalid Postal Code!");}
                    else{
			console.log("here with"+$("#POSTAL_COD").val());
			select_area_init(""+ppcode,false);
                    }  //update div
	}

/*
	function checkZip() {
	    var vppcode = $("#POSTAL_COD").val();
	    if(vppcode == "" || !validPostal(vppcode)){   alert("Invalid Postal Code!");}
	    else{
		    $.get("../postal_code.php",
			     { ppcode: vppcode },
			       // Have this callback take care of the rest of the submit()
			     function(m) {
				if( m!="" ) {
					console.log(m);
					$("#check_done").html("<img src=\'./js/OK_Icons.png\'>");
				}else{
					console.log($("#zip").text());
					$("#check_done").html("<img src=\'./js/not_OK_Icons.png\'>");
				}
			     }
		     );
	    }// else
	}
	
	function checkZip() {
	    var vppcode = $("#POSTAL_COD").val();
	    if(vppcode == "" || !validPostal(vppcode)){   alert("Invalid Postal Code!");}
	    else{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, true);

		$data = $_POST;

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);

		print $output;
		//print "<pre>".print_r($output,true)."</pre>";
		//print "<pre>".print_r($info,true)."</pre>";

		curl_close($ch);

	    }// else
	}*/



	CalculateTotal();

	$(document).ready(function () { 

	    $(\'#oform\').bind(\'submit\',function() {
		console.log("here in the bind");
		form1_onsubmit(true);
		return false;
	    });

	}); 

	function resetbind(){
		$(\'#oform\').unbind(\'submit\');
		$(\'#oform\').bind(\'submit\',function() {
			console.log("here in the bind");
			form1_onsubmit(true);
			return false;
		});

	}
		

	</script>';


   echo page_foot($ajax);
}

  }// if utype
} else {
	echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}



?>
