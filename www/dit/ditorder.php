<!DOCTYPE html>
<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {
	if(isset($_GET["eid"])){
		$eid=$_GET["eid"];	
		//Read from database
		$stmt = new DB_Sql;
		if ($stmt->query("SELECT * from agentcx where id = '$eid'")) {
			$stmt->next_record();
			$streetaddr = $stmt->f("address");
//			$_GET["prov"] = $stmt->f("prov");

			if($stmt->f("prov")=="ON" || strcasecmp(trim($stmt->f('prov')), "ontario") == 0){
				$_GET["prov"]="ON";
			}else if($stmt->f("prov")=="QC" || strcasecmp(trim($stmt->f('prov')), "quebec") == 0){
                                $_GET["prov"]="QC";
                        }

			$ccnumber = $stmt->f("ccnumber");
			$cxemail= $stmt->f("email")." ...";
			$_GET["acanacid"]= $stmt->f("aid");
			$cxcompany= $stmt->f("company");
			$name_arr = split(" ",$stmt->f("name"),2);
			$_GET["fname"] = $name_arr[0];
			$_GET["lname"] = isset($name_arr[1])?$name_arr[1]:"";
			
			$_GET["phone"]= $stmt->f("phone");
			$_GET["phone1"]= $stmt->f("phone1");
		}

		$stmtx = new DB_Sql;
		if ($stmtx->query("SELECT * from agent_trial where id = 1")) {
			$stmtx->next_record();
			$currenttrial = $stmtx->f("currenttrial");
			$totaltrial = $stmtx->f("totaltrial");
		}
	}
?>
<html lang="en">
<head>
    <title>TV Order Page</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://www.zazeen.com/cgi-bin/css.css" type="text/css" media="screen">
    <link rel="stylesheet" href="https://www.zazeen.com/css/reset.css" type="text/css" media="screen">
    <link rel="stylesheet" href="https://www.zazeen.com/css/style.css" type="text/css" media="screen">
    <link rel="stylesheet" href="https://www.zazeen.com/css/grid.css" type="text/css" media="screen">
    <link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <script src="https://www.zazeen.com/js/jquery-1.6.3.min.js" type="text/javascript"></script>
    <script src="https://www.zazeen.com/js/cufon-yui.js" type="text/javascript"></script>
    <script src="https://www.zazeen.com/js/cufon-replace.js" type="text/javascript"></script>
    <script src="https://www.zazeen.com/js/PT_Sans_Narrow_700.font.js" type="text/javascript"></script>
    <script src="https://www.zazeen.com/js/PT_Sans_Narrow_400.font.js" type="text/javascript"></script>
    <script src="https://www.zazeen.com/js/Scriptina_400.font.js" type="text/javascript"></script>
    <script src="https://www.zazeen.com/js/superfish.js" type="text/javascript"></script>
    <script src="https://www.zazeen.com/js/jquery.hoverIntent.js" type="text/javascript"></script>
    <script src="https://www.zazeen.com/js/FF-cash.js" type="text/javascript"></script>
    <script src="https://www.zazeen.com/js/script.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.zazeen.com/js/easyTooltip.js"></script>
<style type="text/css">
table.priceclass {background-color:transparent;border-collapse:collapse;}
table.priceclass th, table.priceclass td {text-align:center;border:1px solid black;padding:0px}
table.priceclass th {background-color:AntiqueWhite;}
table.priceclass td:first-child {width:20%;}
</style>

        <!--[if lt IE 7]>
        <div style=' clear: both; text-align:center; position: relative;'>
            <a href="https://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
             <img src="https://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
            </a>
        </div>
        <![endif]-->
    <!--[if lt IE 9]>
                <script type="text/javascript" src="js/html5.js"></script>
        <link rel="stylesheet" href="css/ie.css" type="text/css" media="screen">
        <![endif]-->
<meta https-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body id="page6">
        <div class="main-bg">
        <div class="main">
<section id="content">
                    <div class="content-padding-2">
                        <div class="container_24">
                            <div class="wrapper line-height1">
                              <div class="grid_20">
                                <table width="100" border="0">
                                  <tr>
                                    <td><html>
<head>
<title>Zazeen IP TV</title>
<meta https-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<div id="mainWrapper">
<div id="naviHead">
<div id="mainBody">
<div id="mainContent">
<?
if ($_GET["result"]=="submitted")
{
?>
<div id="contentTop" class="topContent">
<?
}

?>

<!--****************************-->
<!-- ************************************************ -->
<style type="text/css">
form#orderform {
 font-family:Arial,Verdana,sans-serif;
 font-size:0.8em;
 padding-bottom:30px;
}

form#orderform p {
 color:#333333;
 font-weight:bold;
 padding-bottom:4px;
 margin:0;
}

form#orderform p:first-child {
 padding-bottom:8px;
}

form#orderform p label {
 border-bottom:1px dotted #bbb;
}

form#orderform input[type=radio] {
 margin-right:15px;
}

extra {
 margin-left:45px;
}

form#orderform ul {
 margin-left:10px;
 padding-left:0;
}

form#orderform ul li {
 margin-left:5px;
 padding-left:0;
}

div.errormessage {
 padding:4px;
 margin-bottom:10px;
 font-weight:normal;
 background-color:#f9bdc1;
 border:#c4202c 1px solid;
}

</style>

<body id="page4">
<?php
?>
<script language="JavaScript">
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
extraval[20] = "0";
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

for (var i = 0; i < 15; i++) {
        tc[i] = new Array(10);
        for (var j = 0; j < 10; j++) {
                tc[i][j] = " ";
        }
}



var js;
js="<?if ($_GET["typ"]!="") print $_GET["typ"];else print "";?>";
var maxp=6;
var prov1 = new Array(maxp);

String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

function roundit(num, dec) {
  var result = String(Math.round(num*Math.pow(10,dec))/Math.pow(10,dec));
  if(result.indexOf('.')<0) {result+= '.';}
  while(result.length- result.indexOf('.')<=dec) {result+= '0';}
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

function processreturn(){
        var servername="";
        var arr = document.cookie.split(';');
        for(var i=0;i < arr.length ; i++) {
                var c = arr[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf('ServerName=') == 0) servername=c.substring(11,c.length);
        }
        servername=servername.replace(/\+/g,' ');
        servername=servername.replace(/%3A/gi,':');
        servername=unescape(servername);
        document.getElementById('extra').value=servername;
}

function process(){
                if (document.getElementById('extra') && document.getElementById('extra').value=="")
                {
                        document.getElementById('k14').innerHTML="<img src=https://zazeen.com/collect.php onload=processreturn()>";
                }
}


function CalculateTotal1(){
        return;
        var p;
        var i;
        document.getElementById("PACKAGE_TYPE").options.length=0;
        p=document.getElementById("STATE_PROV").value;
        for(i=0;i<=prov1[p].length-1;i++)
        {
                if (js==prov1[p][i])
                {
                        tb=true;
                        js="";
                }
                else
                {
                        tb=false;
                }
                document.getElementById("PACKAGE_TYPE").options[i]= new Option(prov1[p][i],prov1[p][i],false,tb);
        }
}







function CalculateTotal(){
        var forma12;
        var forma13;
        var myform;
        var s1;
        var t;
        var tt;
        var term2,term1;
        var country1;
        var prov;

//      if (document.getElementById("STB_COUNT").value==0)
//              document.getElementById("SHIP_DSL_M").selectedIndex=0;
//      else
//      if (document.getElementById("SHIP_DSL_M").selectedIndex>0)
//              if (document.getElementById("STB_COUNT").value==0)
//                      document.getElementById("STB_COUNT").selectedIndex=1;

        if (document.getElementById("STATE_PROV")!= null)
                if (document.getElementById("STATE_PROV").value=='ON')
                {
                        document.getElementById("onaddons").style.display='block';
                        document.getElementsByName("provv")[0].style.display='block';
                        document.getElementById("qcaddons").style.display='none';
                }
                else
                        if (document.getElementById("STATE_PROV").value=='QC')
                        {
                                document.getElementById("onaddons").style.display='none';
                                document.getElementsByName("provv")[0].style.display='block';
                                document.getElementById("qcaddons").style.display='block';
                        }
                        else
                        {
                                document.getElementById("onaddons").style.display='none';
                                document.getElementsByName("provv")[0].style.display='none';
                                document.getElementById("qcaddons").style.display='none';
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
        //alert(myform.isrental.value);
        if (myform.isrental.value=='Y')
        {
                document.getElementById("entonespan").innerHTML='($7.95/month) &nbsp;&nbsp;';
                document.getElementById("wncspan").innerHTML='($6.95 /month) &nbsp;&nbsp;';
                document.getElementById("aminospan").innerHTML='($7.95 /month) &nbsp;&nbsp;';
        }
        else
        {
                document.getElementById("entonespan").innerHTML='($200.00 ea.) &nbsp;&nbsp;';
                document.getElementById("wncspan").innerHTML='($150.00 ea.) &nbsp;&nbsp;';
                document.getElementById("aminospan").innerHTML='($150.00 ea.) &nbsp;&nbsp;';
        }
        var incomplete='?';
        tc[1][3]=incomplete;
        tc[1][0]=myform.PACKAGE_TYPE.value;
        tc[1][1]=myform.TERM.value;
        tc[8][0]="STB add-on fees";
        tc[8][1]="1 Month Term";
        tc[8][2]=0.00;
        tc[8][3]=0.00;
        var h=25;
        if (myform.isrental.value=='N')
        {
                document.getElementById('insurancesec1').style.display='block';
                document.getElementById('insurancesec2').style.display='block';
                //document.getElementById('insuranaceinfo').style.display='block';
                if (myform.insurance.value=='Y')
                        document.getElementById('insuranceinfo').style.display='block';
                else
                        document.getElementById('insuranceinfo').style.display='none';
        }
        else
        {
                document.getElementById('insurancesec1').style.display='none';
                document.getElementById('insurancesec2').style.display='none';
                //document.getElementById('insuranaceinfo').style.display='none';
                document.getElementById('insuranceinfo').style.display='none';
        }
        if (myform.TERM.value=='---select one---')
        {
                s1=s1+"<font color=#808080>Waiting for Term<br></font>";
                tc[1][1]=incomplete;
        }
        if (myform.PACKAGE_TYPE.value=='---select-one---')
        {
                s1=s1+"<font color=#808080>Waiting for DSL Type</font><br>";
                tc[1][0]=incomplete;
        }
        country1=myform.COUNTRY.value;
        prov=myform.STATE_PROV.value;
        prov=prov.replace(/[^a-zA-Z ]/,'').trim();
        if (prov=="" || prov=='--select-one---')
        {
                tc[3][3]=incomplete;
                tc[4][3]=incomplete;
                taxpercent=-1;
        }
        else
        {
                taxpercent=GetTax(prov,country1);
        }
        document.getElementById("pricebreakdown").title=taxpercent;
        if (myform.SHIP_DSL_M.value=='---selec-one---')
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
                        base=150.00;
                }
                if(myform.SHIP_DSL_M.value.match(/Entone.*/))
                {
                        base=200.00;
                }
                if(myform.SHIP_DSL_M.value.match(/Amino.*/))
                {
                        base=150.00;
                }
                if(base>=0)
                {
                        tc[2][3]=roundit(base,2);
                        if (myform.isrental.value=='Y')
                        {
                                tc[2][3]=roundit(myform.cntwnc.value*6.95+myform.cntentone.value*7.95+myform.cntamino.value*7.95,2);
                                tc[2][2]="";
                                if (myform.cntwnc.value>0)
                                {
                                        if (tc[2][2]!="")
                                                tc[2][2]=tc[2][2]+"+";
                                        tc[2][2]=tc[2][2]+"$6.95x"+myform.cntwnc.value;
                                }
                                if (myform.cntentone.value>0)
                                {
                                        if (tc[2][2]!="")
                                                tc[2][2]=tc[2][2]+"+";
                                        tc[2][2]=tc[2][2]+"$7.95x"+myform.cntentone.value;
                                }
                                if (myform.cntamino.value>0)
                                {
                                        if (tc[2][2]!="")
                                                tc[2][2]=tc[2][2]+"+";
                                        tc[2][2]=tc[2][2]+"$7.95x"+myform.cntamino.value;
                                }
                                if (tc[2][2]=="")
                                        tc[2][2]="0.00";
                        }
                        else
                                tc[2][3]=roundit(myform.cntwnc.value*150.00+myform.cntentone.value*200.00+myform.cntamino.value*150.00,2);
                        if (1.0*myform.STB_COUNT.value>1)
                        {
//                              tc[2][3]=roundit(tc[2][3]*myform.STB_COUNT.value,2);
                                //tc[8][2]='5.00 x '+(myform.STB_COUNT.value-1);
                                //tc[8][3]=(myform.STB_COUNT.value-1)*5.00;
                        }
                        //tc[2][1]='One time';
                        //tc[2][2]='One time';
                        tc[5][3]=9.95;
                        tc[5][1]='One time';
                        tc[5][2]='One time';
                }
                else
                {
                        tc[2][3]=0;
                        tc[2][1]='NA';
                        tc[2][2]='NA';
                        tc[5][3]=0;
                        tc[5][1]='NA';
                        tc[5][2]='NA';
                }
        }

/**********************************************Chris 2014-05-30 Adapters(not done)****************************/

                if (myform.ADAPTER.value=='---selec-one---')
                {
                        s1=s1+"<font color=#808080>Waiting for Adapter option</font>\n";
                        tc[9][1]=incomplete;
                        tc[9][2]=incomplete;
                        tc[9][3]=incomplete;
                }
                else
                {
                        var base;
                        base=0.00;
                        if(myform.ADAPTER.value.match(/PLC.*/))
                        {
                                base=45.00;
                        }
                        if(myform.ADAPTER.value.match(/Coax.*/))
                        {
                                base=65.00;
                        }
                        if(base>0)
                        {
                                tc[9][3]=roundit(base,2);
                                if (myform.ADAPTERCOUNT.value=='1' ||myform.ADAPTERCOUNT.value=='2' ||myform.ADAPTERCOUNT.value=='3' ||myform.ADAPTERCOUNT.value=='4' ||myform.ADAPTERCOUNT.value=='5' || myform.ADAPTERCOUNT.value=='6'  || myform.ADAPTERCOUNT.value=='0')
                                {
                                        tc[9][3]=roundit(tc[9][3]*myform.ADAPTERCOUNT.value,2);
                                }
                                tc[9][1]='One time';
                                tc[9][2]='One time';
                                if(tc[5][3]>0){

                                }else{
                                        tc[5][3]=9.95;
                                        tc[5][1]='One time';
                                        tc[5][2]='One time';
                                }
                        }
                        else
                        {
                                tc[9][3]=0;
                                tc[9][1]='NA';
                                tc[9][2]='NA';
                        }
                }

/**********************************************Chris 2014-05-30 Adapters(not done)****************************/

/******************************************************* Chris Extra Promo 2014-08-26 ******************************************************/
                if (document.getElementById("STATE_PROV").value=='QC') qc=true;
                if (document.getElementById("STATE_PROV").value=='ON') on=true;

                var exInfo = new Array(40);
                for (var i = 0; i < 20; i++) {
                        exInfo[i] = new Array(10);
                        for (var j = 0; j < 10; j++) {
                                exInfo[i][j] = " ";
                        }
                }
                /*exInfo[1][1]="3 month promo";
                                      exInfo[1][2]="";
                                      exInfo[2][1]="1 month promo";
                                      exInfo[2][2]="1009_1021";
                                      exInfo[3][1]="1 month promo";
                                      exInfo[3][2]="1009_1021";*/
                                      exInfo[1][1]="1 month promo";
                                      exInfo[1][2]="1009_1021";
                                                if(myform.EPROMO_TYPE.value != "---select-one---"){
                        var exindex = myform.EPROMO_TYPE.selectedIndex;
                        if(exindex > 0){
                                if(myform.EPROMO_TYPE.value.substring(7,8) == "R"){
                                        //Rental Promos
                                        if(myform.isrental.value=='N'){
                                                $("#EPROMO_TYPE").val('---select-one---');
                                                tc[10][0]='Rental Promo';
                                                tc[10][1]='Invalid STB Option';
                                                tc[10][2]=0.0;
                                                tc[10][3]=0.0;
                                                alert("Rental promo is only applicable for rental option!");
                                        }else{
                                                tc[10][0]='Rental Promo';
                                                tc[10][1]=exInfo[exindex][1];
                                                tc[10][2]=(-1.0)*tc[2][3];
                                                tc[10][3]=(-1.0)*tc[2][3];

                                                $("#rpromo").val(myform.EPROMO_TYPE.value.substring(8));
                                                $("#apromo").val("");
                                        }
                                }else if(myform.EPROMO_TYPE.value.substring(7,8) == "A"){
                                        //Addon Promos
                                        tc[10][0]='Addon Promo';
                                        tc[10][1]=exInfo[exindex][1];
                                        var addpro = exInfo[exindex][2].split("_");


                                        var addonprice = [0.0, 1.0*9.95, 1.0*0.99, 1.0*0.75, 1.0*4.99, 1.0*2.99, 1.0*12.99, 1.0*12.99, 1.0*0.99, 1.0*15.99, 1.0*29.95, 1.0*2.99, 0.0, 0.0, 1.0*9.95, 1.0*0.99, 1.0*0.75, 1.0*4.99, 1.0*12.99, 1.0*12.99, 1.0*0.99, 1.0*15.99, 1.0*29,95];


                                        //Auto check
                                        tc[10][3]=0.0;
                                        for(var l = 0; l < addpro.length; l++){
                                                if (document.getElementById("pkg"+addpro[l])!= null){
                                                        if( (parseInt(addpro[l]) < 1014 && on) || (parseInt(addpro[l]) >= 1014 && qc)){
                                                                if (!document.getElementById("pkg"+addpro[l]).checked){
                                                                        $("#pkg"+addpro[l]).prop("checked", true);
                                                                }
                                                                var subindex = parseInt(addpro[l].slice(-2),10);
								//alert(parseInt(addpro[l].slice(-2)));

                                                                tc[10][3]=tc[10][3]+addonprice[subindex];
                                                        }
                                                }

                                        }

                                        tc[10][3]=(-1.0)*tc[10][3];
                                        tc[10][2]=tc[10][3];

                                        $("#apromo").val(myform.EPROMO_TYPE.value.substring(8));
                                        $("#rpromo").val("");

					if(myform.EPROMO_TYPE.value.substring(8)=="40629"){
                                        	$("#apromo").val("56734");
	                                        $("#rpromo").val("20368");

						//Rental Promos
						if(myform.isrental.value=='N'){
							$("#EPROMO_TYPE").val('---select-one---');
							tc[10][0]+=' + Rental Promo';
							tc[10][1]=' Rental:Invalid STB Option';
							tc[10][2]+=0.0;
							tc[10][3]+=0.0;
							alert("Rental promo is only applicable for rental option!");
						}else{
							tc[10][0]+=' + Rental Promo';
							//tc[10][1]+= ' Rental:'+exInfo[exindex][1];
							tc[10][2]+=(-1.0)*tc[2][3];
							tc[10][3]+=(-1.0)*tc[2][3];
						}
					}

                                }
                        }
                }
                /******************************************************* End Chris Extra Promo 2014-08-26 ******************************************************/

        if (tc[1][0]!=incomplete && tc[1][1]!=incomplete)
        {
                t=myform.PACKAGE_TYPE.selectedIndex-tt;
                term2=myform.TERM.value;
                term1=term2.split(' ');
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
        if (document.getElementById("STATE_PROV").value=='QC') qc=true;
        if (document.getElementById("STATE_PROV").value=='ON') on=true;
        if (document.getElementById("pkg1001")!= null) if (document.getElementById("pkg1001").checked && on)    tc[7][3]=tc[7][3]+1.0*9.95;
        if (document.getElementById("pkg1002")!= null) if (document.getElementById("pkg1002").checked && on)    tc[7][3]=tc[7][3]+1.0*0.99;
        if (document.getElementById("pkg1003")!= null) if (document.getElementById("pkg1003").checked && on)    tc[7][3]=tc[7][3]+1.0*0.75;
        if (document.getElementById("pkg1005")!= null) if (document.getElementById("pkg1005").checked && on)    tc[7][3]=tc[7][3]+1.0*2.99;
        if (document.getElementById("pkg1006")!= null) if (document.getElementById("pkg1006").checked && on)    tc[7][3]=tc[7][3]+1.0*12.99;
        if (document.getElementById("pkg1007")!= null) if (document.getElementById("pkg1007").checked && on)    tc[7][3]=tc[7][3]+1.0*12.99;
        if (document.getElementById("pkg1008")!= null) if (document.getElementById("pkg1008").checked && on)    tc[7][3]=tc[7][3]+1.0*0.99;
        if (document.getElementById("pkg1009")!= null) if (document.getElementById("pkg1009").checked && on)    tc[7][3]=tc[7][3]+1.0*15.99;
        if (document.getElementById("pkg1010")!= null) if (document.getElementById("pkg1010").checked && on)    tc[7][3]=tc[7][3]+1.0*29.95;
        if (document.getElementById("pkg1011")!= null) if (document.getElementById("pkg1011").checked && on)    tc[7][3]=tc[7][3]+1.0*2.99;

        if (document.getElementById("pkg1014")!= null) if (document.getElementById("pkg1014").checked && qc)    tc[7][3]=tc[7][3]+1.0*9.95;
        if (document.getElementById("pkg1015")!= null) if (document.getElementById("pkg1015").checked && qc)    tc[7][3]=tc[7][3]+1.0*0.99;
        if (document.getElementById("pkg1016")!= null) if (document.getElementById("pkg1016").checked && qc)    tc[7][3]=tc[7][3]+1.0*0.75;
        if (document.getElementById("pkg1017")!= null) if (document.getElementById("pkg1017").checked && qc)    tc[7][3]=tc[7][3]+1.0*4.99;
        if (document.getElementById("pkg1018")!= null) if (document.getElementById("pkg1018").checked && qc)    tc[7][3]=tc[7][3]+1.0*12.99;
        if (document.getElementById("pkg1019")!= null) if (document.getElementById("pkg1019").checked && qc)    tc[7][3]=tc[7][3]+1.0*12.99;
        if (document.getElementById("pkg1020")!= null) if (document.getElementById("pkg1020").checked && qc)    tc[7][3]=tc[7][3]+1.0*0.99;
        if (document.getElementById("pkg1021")!= null) if (document.getElementById("pkg1021").checked && qc)    tc[7][3]=tc[7][3]+1.0*15.99;
        if (document.getElementById("pkg1022")!= null) if (document.getElementById("pkg1022").checked && qc)    tc[7][3]=tc[7][3]+1.0*29.95;

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
                term1=term2.split(' ');
                t=myform.PACKAGE_TYPE.selectedIndex-tt;
                tc[1][3]=roundit(term1[0]*Price[t][myform.TERM.selectedIndex-tt],2);
                tc[1][2]=Price[t][myform.TERM.selectedIndex-tt];
                //alert(tc[1][3]+" "+tc[1][2]);
		
		var ppoo = $("#ppromo").val();
		if(ppoo=="9211"){
			tc[1][3]=19.95;
                        tc[1][1]='3 month Promo';
                        tc[1][2]='19.95';

		}


                if (taxpercent!=-1)
                {
                        tc[3][3]=roundit((1.0*tc[1][3]+1.0*tc[2][3]+1.0*tc[5][3]+1.0*tc[6][3]+1.0*tc[7][3]+1.0*tc[8][3]+1.0*tc[9][3]+1.0*tc[10][3])*taxpercent/100.0,2);
                        tc[4][3]=roundit(1.0*tc[1][3]+1.0*tc[2][3]+1.0*tc[5][3]+1.0*tc[3][3]+1.0*tc[6][3]+1.0*tc[7][3]+1.0*tc[8][3]+1.0*tc[9][3]+1.0*tc[10][3],2);
                }
        }
        else
        {
                tc[3][3]=incomplete;
                tc[4][3]=incomplete;
        }

        if (myform.isrental.value=='Y')
        {
                tc[2][0]="Set-top box Rental";
                tc[2][1]="1 Month Term";
        }
        else
        {
                tc[2][0]="Set-top box Purchase";
                tc[2][1]="One time";
                tc[2][2]="One time";
        }
        tc[5][0]="Shipping fee";
        tc[6][0]="Activation Fee";
        tc[6][1]="One time";
        tc[6][2]="One time";


        var activationfee="<tr style='display:none;HEIGHT:"+h+"px'><td style='BORDER-LEFT: solid thin'>"+tc[6][0]+"</td><td style=''>"+tc[6][1]+"</td><td>"+tc[6][2]+"</td><td>"+tc[6][3]+"</td></tr>\n";
        var sstbaddonfees="";
        if (tc[8][3]==0)
                sstbaddonfees="display:none;";
        var saddonline="";
        if (tc[7][3]==0)
                saddonline="display:none;";
        var stbaddonfees="<tr style='"+sstbaddonfees+"HEIGHT:"+h+"px'><td nowrap='nowrap' style='BORDER-LEFT: solid thin'>"+tc[8][0]+"</td><td style=''>"+tc[8][1]+"</td><td>"+tc[8][2]+"</td><td>"+tc[8][3]+"</td></tr>\n";
        var shipfeeline="<tr style='HEIGHT:"+h+"px'><td style='BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin'>"+tc[5][0]+"</td><td style='BORDER-BOTTOM: solid thin'>"+tc[5][1]+"</td><td>"+tc[5][2]+"</td><td>"+tc[5][3]+"</td></tr>\n";
        var addonline="<tr style='"+saddonline+"HEIGHT:"+h+"px'><td style='BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin'>"+tc[7][0]+"</td><td style='BORDER-BOTTOM: solid thin'>"+tc[7][1]+"</td><td>"+tc[7][2]+"</td><td>"+tc[7][3]+"</td></tr>\n";
        var modemfeeline="<tr style='HEIGHT:"+h+"px'><td nowrap='nowrap' style='BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin'>"+tc[2][0]+"</td><td style='BORDER-BOTTOM: solid thin'>"+tc[2][1]+"</td><td>"+tc[2][2]+"</td><td>"+tc[2][3]+"</td></tr>\n";
        var taxline="<tr style='HEIGHT: "+h+"px'><td colspan=2 rowspan=2 style='BORDER-BOTTOM-STYLE: none;border-left-style:none'>"+" "+"</td><td style='BORDER-LEFT: solid thin'>Tax</td><td>"+tc[3][3]+'</td></tr>';
        var totalline="<tr style='HEIGHT: "+h+"px'><td style='BORDER-BOTTOM: solid thin;BORDER-LEFT: solid thin'><span>Total $CAD</span></td><td style='BORDER-BOTTOM: solid thin'>"+tc[4][3]+"</td></tr>\n";


        var adpfees="";
        if (tc[9][3]==0)
        {adpfees="display:none;";}

                tc[9][0]="Adapter fee";
                var adpfeeline="<tr style='"+adpfees+"HEIGHT:"+h+"px'><td style='BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin'>"+tc[9][0]+"</td><td style='BORDER-BOTTOM: solid thin'>"+tc[9][1]+"</td><td>"+tc[9][2]+"</td><td>"+tc[9][3]+"</td></tr>\n";

	var expromostr="";
                if (tc[10][3]==0)
                {expromostr="display:none;";}
		var expline="<tr style='"+expromostr+"HEIGHT:"+h+"px'><td style='BORDER-LEFT: solid thin;BORDER-BOTTOM: solid thin'>"+tc[10][0]+"</td><td style='BORDER-BOTTOM: solid thin'>"+tc[10][1]+"</td><td>"+tc[10][2]+"</td><td>"+tc[10][3]+"</td></tr>\n";


        document.getElementById("subtotal").innerHTML="<table  class=priceclass width=350px border=1 CellSpacing=0 CellPadding=0 BORDERCOLORLIGHT=#000000 bordercolor=\"#000000\" style=\"FONT-SIZE: x-small;border-bottom-style:none;border-left-style:none\"><tr style='HEIGHT: "+h+"px'><td style='BORDER-LEFT: solid thin'>Item</td><td>Term</td><td>Monthly fee</td><td>Total for term $CAD</td></tr><tr style='HEIGHT:"+h+"px'><td style='BORDER-LEFT: solid thin'>"+tc[1][0]+"</td><td>"+tc[1][1]+"</td><td>"+tc[1][2]+"</td><td>"+tc[1][3]+"</td></tr>\n"+modemfeeline+activationfee+adpfeeline+shipfeeline+addonline+expline+taxline+totalline+"</table>";

}






function sendwarning()
{
        var forma12;
        var forma13;
        forma13=document.getElementsByTagName("form");
        forma12=forma13[formnumber];
        if (forma12.USERNAME.value=="Others")
        {
                document.getElementById('isp').innerHTML="<br>You can order required Internet Service through:<br><a href=https://www.distributel.ca target=_blank>Distributel</a><br><a href=https://www.acanac.com target=_blank>Acanac Inc.</a><br><a href=https://www.xinflix.com target=_blank>Xinflix Media Inc.</a><br>";
                alert('Currently the service is only available through the Service Providers listed in the Drop Down Menu.');

        }
}


var hexcase = 0;
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
  try { b64pad } catch(e) { b64pad=''; }
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

$(document).ready(function(){
		    $(".ACANACADDRDIV").hide();
		    $(".ACANACEMAILDIV").hide();
		    $(".ACANACCCDIV").hide();
	$('#USEACANACADDR').change(function() {
//	    console.log("addr" + $('#USEACANACADDR').is(':checked'));
	    if($('#USEACANACADDR').is(':checked')){
		    $(".ACANACADDRDIV").hide();
		    active[8]=false;
		    active[9]=false;
		    active[10]=false;
		    active[11]=false;
		    active[12]=false;
		    active[13]=false;
	    }else{
		    active[8]=true;
		    active[9]=true;
		    active[10]=true;
		    active[11]=true;
		    active[12]=true;
		    active[13]=true;
		    $(".ACANACADDRDIV").show();
	    }
	});
	$('#USEACANACEMAIL').click(function() {
	    if($('#USEACANACEMAIL').is(':checked')){
		    $(".ACANACEMAILDIV").hide();
		    active[15]=false;
	    }else{
		    active[15]=true;
		    $(".ACANACEMAILDIV").show();
	    }
	});
	$('#USEACANACCC').click(function() {
	    if($('#USEACANACCC').is(':checked')){
		    $(".ACANACCCDIV").hide();
		    active[16]=false;
		    active[17]=false;
		    active[18]=false;
		    active[19]=false;
	    }else{
		    active[16]=true;
		    active[17]=true;
		    active[18]=true;
		    active[19]=true;
		    $(".ACANACCCDIV").show();
	    }
	});






//function form1_onsubmit() 
jQuery("#form").submit(function(e) {
	var self = this;
        e.preventDefault();
        //return false;
        var forma12;
        var forma13;
        forma13=document.getElementsByTagName("form");
        forma12=forma13[formnumber];
        bReturn= true;

        var i;
        var s = "";

	    if($('#USEACANACADDR').is(':checked')){
		    active[8]=false;
		    active[9]=false;
		    active[10]=false;
		    active[11]=false;
		    active[12]=false;
		    active[13]=false;
	    }
	    if($('#USEACANACEMAIL').is(':checked')){
		    active[15]=false;
	    }
	    if($('#USEACANACCC').is(':checked')){
		    active[16]=false;
		    active[17]=false;
		    active[18]=false;
		    active[19]=false;

		    forma12.CREDIT_TYP.value="Visa";
		    forma12.NAME_ON_CA.value="No Name";
		    forma12.CARD_NUMBE.value="1111222233334444";
		    forma12.YEAR01.value="01";
		    forma12.YEAR.value="2015";
	    }
        for (i = MAX-1;i>=0;i--)

        {
                if (!active[i])
                        continue;
                s = "forma12."+CheckAll[i];
                if (eval(s+".value") =="" || eval(s+".value") == extraval[i] ||(i==EmailIndex && eval(s+".value").indexOf('@')<0 ))
                {
                        bReturn = false;
                        eval(s+".style.backgroundColor='pink'");
                        eval(s+".focus();");
                }
                else
                        eval(s+".style.backgroundColor = colorBase");
        }

        if(!forma12.CARD_NUMBE.value.match(/^[0-9 ]+$/) && !$('#USEACANACCC').is(':checked'))
        {
                bReturn = false;
                forma12.CARD_NUMBE.style.backgroundColor='pink';
                forma12.CARD_NUMBE.focus();
        }
        else
                forma12.CARD_NUMBE.style.backgroundColor = colorBase;

        if (!forma12.box_confirm_df210783a8.checked)
        {
                forma12.box_confirm_df210783a8.style.backgroundColor='pink';
        }
        else
        {
                forma12.box_confirm_df210783a8.style.backgroundColor = colorBase;
        }



        if (!bReturn)
        {
            alert("Please complete or correct all fields highlighted in pink");
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
		if (document.getElementById('extra').value!="" && (document.getElementsByName('COMMENTS')[0].value.indexOf(document.getElementById('extra').value)== -1))
                {
                        document.getElementsByName('COMMENTS')[0].value=document.getElementsByName('COMMENTS')[0].value+"\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n"+document.getElementsByName('PACKAGE_TYPE')[0].value+":::"+document.getElementById('extra').value;
                }

		var acaddr = "on";
		if($("#USEACANACADDR").prop("checked")){
			acaddr = "on";
		}else{
			acaddr = "off";
		}

		var acemail = "on";
		if($("#USEACANACEMAIL").prop("checked")){
			acemail = "on";
		}else{
			acemail = "off";
		}

		var accc = "on";
		if($("#USEACANACCC").prop("checked")){
			accc = "on";
		}else{
			accc = "off";
		}

		$("#COMMENTS").val( $("#COMMENTS").val() + "\r\ncid:<?php print $_GET["acanacid"]; ?>" + "\r\nUSEACANACADDR:"+acaddr + "\r\nUSEACANACEMAIL:"+acemail + "\r\nUSEACANACCC:"+accc );

        }


        if (bReturn){
		var ppromo = forma12.ppromo.value;
		//update load
		$.post( "ctfunction.php", { action: "sorder", eid: "<? print $_GET['eid'];?>", promo: ppromo })
		  .done(function( data ) {
		    //alert( data );
		    self.submit();
		  });	
	}

        return false;

});


});

</script>
<span name=k14 id=k14 style='display:none'></span> <span name=k15 id=k15 style='display:none'><img src=https://www.zazeen.com/tips.gif onload='process()'></span>
<div class="tail-bottom">
<div id="main">
<!-- header -->

<!-- content -->
<div id="content">
<div class="cont-box">
<div class="left-top-corner">
  <div class="right-top-corner">
    <div class="border-top"></div>
  </div>
</div>
<div class="xcontent">
<div class="wrapper">
<div class="col-1">
<div class="box indent">
<div class="box indent">
<div class="border-top">
  <div class="left-top-corner">
    <div class="right-top-corner"></div>
  </div>
</div>
<div class="border-bot">
<div class="border-left">
<div class="border-right">
<div class="left-bot-corner">
<div class="right-bot-corner">
<ul class="list" style='list-style-type: none'>
<li>
  <div align="center">
    <table  width="803"   border="0"  cellpadding="0"  cellspacing="0">
      <tr  height="0">
        <td width="703"  height="400" align="center"  valign = "top" ><br>
          <?$session=session_id();?>
          </p>
          <?

if ($_GET["result"]=="submitted")
{
?>
          <div align=left> Thank You. Your order<b><?if ($_GET["id"]!="") print "(ID: ".$_GET["id"].")"?></b> is being processed and you will receive an email confirmation within the next 48 hours. <br />
            <br />
            Once again thank you for your order and we look forward to a long term relationship.
            If you have any questions please feel free to call us.
            Sales Numbers
            Toll Free: 1 877 814 0280 </div>
          <?
}
else
{
?>

<?
        if (isset($_GET["addon"]) && !isset($_GET["package"]))
        {
        print "<font color=lighblue><u>You have not selected a package, do you already have an IPTV package with us?<br>\nIf you already have one please call us and add the addons you want</u></span></font>\n";
        }
        if (false && isset($_GET["package"]) && !(isset($_GET["p1"]) || isset($_GET["p2"]) || isset($_GET["p3"]) || isset($_GET["p4"]) || isset($_GET["p5"]) || isset($_GET["p6"]) || isset($_GET["p7"]) || isset($_GET["p0"] )))
        {
?>
        You have not selected any addon, Would you want to select any of these addon channels?<br>
<?

        }
        else
        {






?>

          <div align=center>
<!--          <form  action = "https://www.zazeen.com/agent/showpost.php" method = "post" onSubmit="return form1_onsubmit();">-->
          <form  action = "https://www.zazeen.com/agent/showpost.php" method = "post" id="form">
            <input type="hidden" value="Wzxp8VvMFC3xuvJQ6ktRseTwf9YfYeKL" name="key">
            <input type="hidden" value="beb1c93e2bda4f227b5375497e8f1999" name="sid">
            <input type="hidden" value="DIT" name="SUBMITTYPE">
            <input style="display:none" value="" id="extra" name="extra">
            <input type="hidden" name="p_formdb" value="iptv" />
            <input type="hidden" id="ACANACID" name="ACANACID" value="<?if ($_GET["acanacid"]!="") print $_GET["acanacid"];?>" />
            <input type="hidden" id="PROMO" name="PROMO" value="" />

<?
        foreach($_POST as $a=>$b)
        {
                if (preg_match("/pkg/",$a))
                {
                        //print $a."-".$b."<br>\n";
                ?>
                <!--<input type="hidden" name="<?print $a?>" value="<? print $b?>">-->
<?
                }
        }
        function sel($i)
        {
                $result="";
                $ii=preg_replace("/[^0-9]/","",$i);
                if (isset($_POST["pkg".$ii]))
                        if ($_POST["pkg".$ii]==1)
                                $result="checked";
                return $result;
        }

?>
            <table width="778" border="0" cellpadding="6" cellspacing="6" bgcolor="#efefef" class="phpForms_main" id="pg_789c622134" style="display:table;border: solid 1px #777777;">
              <tr>
                <td colspan="2" align="center" valign="top"><span class="phpForms_pgtitle"><strong>Zazeen IP TV Order Form</strong></span></td>
              </tr>
              <tr>
                <td colspan="2" align="center" valign="top">&nbsp;</td>
              </tr>
              <tr>

                <!-- Page title -->

                <td colspan="2" align="center" valign="top"><table width="765" border="0" cellspacing="5" cellpadding="5">
                  <tr>
                    <td><div align="left"></div></td>
                  </tr>
                </table></td>
                </tr>

              <!-- /Page title -->

              <tr>

                <!-- Page top text -->

                <td colspan="2" align="center"></td>
                </tr>

              <!-- /Page top text -->

              <tr>
                <td align="left" valign="top" bgcolor="#ffffff">&nbsp;</td>
                <td align="left" bgcolor="#ffffff">
              <tr>
                <td width="364" align="left" valign="top" bgcolor="#ffffff"><p><span style=""> &nbsp;Province:</span> <font color='red'>*</font><br>
                </p></td>
                <td width="370" align="left" bgcolor="#ffffff"><select name=STATE_PROV id=STATE_PROV onfocusout='CalculateTotal();' onchange='CalculateTotal();'>
                  <option value="---select-one---"  <?if ($_GET["prov"]!="Ontario" && $_GET["prov"]!="Quebec"  && $_GET["prov"]!="Quebec" && $_GET["prov"]!="Quebec" && $_GET["prov"]!="Quebec") print "selected";?>>---select-one---</option>
                  <option value="ON" <?if ($_GET["prov"]=="ON") print "selected"?>>Ontario</option>
                  <option value="QC" <?if ($_GET["prov"]=="QC") print "selected"?>>Quebec</option>
                  <!--                  <option value="AB" <?if ($_GET["prov"]=="AB") print "selected"?>>Alberta</option>
                  <option value="BC" <?if ($_GET["prov"]=="BC") print "selected"?>>British Columbia</option>
                  <option value="MB" <?if ($_GET["prov"]=="MB") print "selected"?>>Manitoba</option>
                  <option value="NB" <?if ($_GET["prov"]=="NB") print "selected"?>>New Brunswick</option>
                  <option value="NL" <?if ($_GET["prov"]=="NL") print "selected"?>>Newfoundland and Labrador</option>
                  <option value="NS" <?if ($_GET["prov"]=="NS") print "selected"?>>Nova Scotia</option>
                  <option value="NU" <?if ($_GET["prov"]=="NS") print "selected"?>>Nunavut</option>
                  <option value="PE" <?if ($_GET["prov"]=="PE") print "selected"?>>Prince Edward Island</option>
                  <option value="SK" <?if ($_GET["prov"]=="SK") print "selected"?>>Saskatchewan</option>
                  <option value="YT" <?if ($_GET["prov"]=="YT") print "selected"?>>Yukon</option>
                  <option value="NT" <?if ($_GET["prov"]=="NT") print "selected"?>>Northwest Territories</option>
-->
                  </select>
                  <a href='https://community.zazeen.com/viewforum.php?f=6' target='_blank' title='To see the paces we can provide IP TV please click here' onclick='if (document.getElementById("STATE_PROV").value=="ON") window.open("https://zazeen.com/postal_code.php");else if (document.getElementById("STATE_PROV").value=="QC") window.open("https://zazeen.com/postal_code.php");else alert("Please select a province first");return false'>Map of supported locations</a>
                  <tr>

                  <tr style='display:none'>
                    <td align="left" valign="top" bgcolor="#ffffff"><p> <p> &nbsp; Activation Phone Number or<br />
                       &nbsp;Naked DSL/Dry Loop*: <font color='red'>*</font><font color='red'><br>
                    </font></p></td>
                    <td align="left" bgcolor="#ffffff"><input type = "text" name = "PHONE_NUMB" size = "33" maxlength = "33" value = "1111111111" /></td>
                  </tr>
              <tr style=''>
                <td align="left" valign="top" bgcolor="#ffffff">&nbsp;Package Term: <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><select  style="background-color:silver" name="TERM" size="1" onfocusout='CalculateTotal();' onchange='CalculateTotal();'>
                  <option value="1 Month Term">1 Month Term</option>
                  <!--                  <option value="3 Month Term">3 Month Term</option>
                  <option value="6 Month Term">6 Month Term</option>
                  <option value="12 Month Term">12 Month Term</option>
-->
                  </select></td>
                </tr>
              <tr>
                <td align="left" valign="top" bgcolor="#ffffff"> &nbsp;IPTV package type: <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><select  style='background-color:silver' name="PACKAGE_TYPE" id='PACKAGE_TYPE' size="1" onfocusout='CalculateTotal();' onchange='CalculateTotal();'>
                  <!--                  <option selected="selected" value="---select-one---">---select-one---</option>-->
                  <option value="Basic">Not-So-Basic $(49.95)</option>
                  <!--                  <option value="Advanced">Advanced</option>-->
                  </select>
                </td></tr>
              <tr style=''>
                <td align="left" valign="top" bgcolor="#ffffff">&nbsp;Package Promo: <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><select  style="background-color:silver" name="ppromo" id="ppromo" size="1" onfocusout='CalculateTotal();' onchange='CalculateTotal();'>
                  <option value="9211" selected>First Three Month $(19.95)</option>
                  <option value="7038" <? if($currenttrial>=$totaltrial){print 'disabled';} ?>>First Month Free ( <? print $currenttrial." / ".$totaltrial;?> )</option>
                  <!--                  <option value="3 Month Term">3 Month Term</option>
                  <option value="6 Month Term">6 Month Term</option>
                  <option value="12 Month Term">12 Month Term</option>
-->
                  </select></td>
                </tr>
	<tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Extra Promo: <br>
                  <br>
                </td>
                <td bgcolor="#ffffff" align="left"><select onchange="CalculateTotal();" onfocusout="CalculateTotal();" size="1" id="EPROMO_TYPE" name="EPROMO_TYPE" style="">
			<option value="---select-one---">---select-one---</option>
			<option value="expromoA40629" selected="selected">[DIT special] 1st month free TMN and Rental</option>
                   <!--                  <option value="Advanced">Advanced</option>-->
                  </select>
                <!--    <br><table width=100%><tr><td style=\'width:8px\'>*</td><td><span style=\'color:gray\'>Promo is for the first 3 months only. $49.95/mth is applicable after the promo has expired. Promo term is billable up front</span></td></table><br><br>-->
                </td></tr>




              <tr>
                <td align="left" valign="top" ><div name=provv id=provv <?if (($_GET["prov"]!='ON')&&($_GET["prov"]!='QC')) print "style='display:none'";?>> &nbsp;Addon channels: <font color='red'></font></div></td>
<td align="left">
<?if ($_GET["prov"]=='ON') $onon="block";else $onon='none';?>
<div name=onaddons id=onaddons style="display:<?print $onon ?>">
<input name=pkg1001 id=pkg1001 type=checkbox value="1" <?print sel(1001)?> onchange='CalculateTotal();'>Bell Addon: $9.95 p/month<br>
<input name=pkg1002 id=pkg1002 type=checkbox value="1" <?print sel(1002)?> onchange='CalculateTotal();'>Blue Ant Addon: $0.99 p/month<br>
<input name=pkg1003 id=pkg1003 type=checkbox value="1" <?print sel(1003)?> onchange='CalculateTotal();'>ZoomerMedia Addon: $0.75 p/month<br>
<input name=pkg1005 id=pkg1005 type=checkbox value="1" <?print sel(1005)?> onchange='CalculateTotal();'>Astral French Addon: $2.99 p/month<br>
<input name=pkg1011 id=pkg1011 type=checkbox value="1" <?print sel(1011)?> onchange='CalculateTotal();'>TVA Addon: $2.99 p/month<br>
<input name=pkg1007 id=pkg1007 type=checkbox value="1" <?print sel(1007)?> onchange='CalculateTotal();'>Super Ecran Addon: $12.99 p/month<br>
<input name=pkg1008 id=pkg1008 type=checkbox value="1" <?print sel(1008)?> onchange='CalculateTotal();'>Standalone Addon: ichannel (Issue Channel) $0.99<br>
<input name=pkg1009 id=pkg1009 type=checkbox value="1" <?print sel(1009)?> onchange='CalculateTotal();'>The Movie Network Package $15.99<br>
<input name=pkg1010 id=pkg1010 type=checkbox value="1" <?print sel(1010)?> onchange='CalculateTotal();'>Standalone Addon: Sportsnet World $29.95<br>
<br>
</div>
<?if ($_GET["prov"]=='QC') $qcon="block";else $qcon="none";?>
<div name=qcaddons id=qcaddons style="display:<?print $qcon?>">
<input name=pkg1014 id=pkg1014 type=checkbox value="1" <?print sel(1014)?> onchange='CalculateTotal();'>Bell Addon: $9.95 p/month<br>
<input name=pkg1015 id=pkg1015 type=checkbox value="1" <?print sel(1015)?> onchange='CalculateTotal();'>Blue Ant Addon: $0.99 p/month<br>
<input name=pkg1016 id=pkg1016 type=checkbox value="1" <?print sel(1016)?> onchange='CalculateTotal();'>ZoomerMedia Addon: $0.75 p/month<br>
<input name=pkg1017 id=pkg1017 type=checkbox value="1" <?print sel(1017)?> onchange='CalculateTotal();'>Astral English Addon: $4.99 p/month<br>
<input name=pkg1019 id=pkg1019 type=checkbox value="1" <?print sel(1019)?> onchange='CalculateTotal();'>Super Ecran Addon: $12.99 p/month<br>
<input name=pkg1020 id=pkg1020 type=checkbox value="1" <?print sel(1020)?> onchange='CalculateTotal();'>Standalone Addon: ichannel (Issue Channel) $0.99<br>
<input name=pkg1021 id=pkg1021 type=checkbox value="1" <?print sel(1021)?> onchange='CalculateTotal();'>The Movie Network Package $15.99<br>
<input name=pkg1022 id=pkg1022 type=checkbox value="1" <?print sel(1022)?> onchange='CalculateTotal();'>Standalone Addon: Sportsnet World $29.95<br>
<br>
</div>
</td>
                </tr>
              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"><font size=-1>  &nbsp;Current Internet Service Provider:</font><font color=red>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><select name=USERNAME onchange='sendwarning();'><option value="--select one--">--select one--</option><option value='Acanac' <?if($cxcompany=="acanac") print ' selected';?>>Acanac</option><option value='Distibutel'>Distributel</option><option value='Xinflix'>Xinflix</option><option value='Others'>Other</option></select><font size=-2 color=grey><span name=isp id=isp></span><br>
</td>

                </tr>
              <script>

CalculateTotal();

</script>
              <tr style='display:none'>
                <td align="left" bgcolor="#ffffff" valign="top"> &nbsp;Preferred STB unit<font size=-2 color=grey> (IPTV <br>
                   &nbsp;streaming  &nbsp;device)</font>: <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><select  id='SHIP_DSL_M' name = "SHIP_DSL_M" size="1" onfocusout='if (document.getElementById("SHIP_DSL_M").selectedIndex>0) if (document.getElementById("STB_COUNT").value==0) document.getElementById("STB_COUNT").selectedIndex=2;if (document.getElementById("SHIP_DSL_M").selectedIndex==0) if (document.getElementById("STB_COUNT").value!=0) document.getElementById("STB_COUNT").selectedIndex=1;CalculateTotal();' onchange='if (document.getElementById("SHIP_DSL_M").selectedIndex>0) if (document.getElementById("STB_COUNT").value==0) document.getElementById("STB_COUNT").selectedIndex=2;if (document.getElementById("SHIP_DSL_M").selectedIndex==0) if (document.getElementById("STB_COUNT").value!=0) document.getElementById("STB_COUNT").selectedIndex=1;CalculateTotal();'>
                  <option value="0">I already have Zazeen STB</option>
                  <option value="WNC $75.00 +  9.95 Shipping fee">WNC $75.00 + 9.95 Shipping fee</option>
                  <option value="Entone $125.00 +  9.95 Shipping fee(Not Available)" selected>Entone $125 + 9.95 Shipping fee</option>
                  <option value="Amino $150.00 +  9.95 Shipping fee(Not Available)" disabled>Amino $150.00 + 9.95 Shipping fee(Not available)</option>
                </select><br><br></td>
                </tr>


<script>
        function changenum(id,id1)
        {
                if (id=='dec')
                        if(document.getElementById(id1).value>0) document.getElementById(id1).value=1.0*document.getElementById(id1).value-1;
                if (id=='inc')
                        if(document.getElementById(id1).value<9) document.getElementById(id1).value=1.0*document.getElementById(id1).value+1;
                document.getElementById('STB_COUNT').value=
                        1*document.getElementById('cntentone').value+
                        1*document.getElementById('cntwnc').value+
                        1*document.getElementById('cntamino').value+
                        1*document.getElementById('cntown').value
                        ;
                CalculateTotal();

        }
</script>

              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"><span style='margin-left:0px'> &nbsp;Rent or Buy? <font color='red'>*</font></span><br><span style='margin-left:0px'></span></td>
                <td align="left" bgcolor="#ffffff"><select name=isrental id=isrental onchange='CalculateTotal()'><option value='Y' selected>Rental</option><option value='N'>Purchase</option></select>
                <br><br></td>
                </tr>

              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"><span style='margin-left:0px' name=insurancesec1 id=insurancesec1> &nbsp;Extended Warranty? <font color='red'>*</font></span><br><span style='margin-left:0px'></span></td>
                <td align="left" bgcolor="#ffffff"><div id=insurancesec2 name=insurancesec2><select name=insurance id=insurance onchange='CalculateTotal()'><option value='Y'>Yes</option><option value='N'>No</option></select><span id=insuranceinfo name=insuranceinfo style='display:none'>$3.95/mth after first year</span>
                <br><table width=95%><tr><td style='padding-top:5px'>STB's come with an automatic 1 year hardware warranty.  Additional extended warranty may be subscribed to at any time prior to the original hardware warranty expriration date.  Extended warranty fees only begin after the 1 year factory warrantee has expired<br><br></td></tr></table></div>
                </td>
                </tr>

              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"><span style='margin-left:0px'> &nbsp;Set Top Box units count</span><br><span style='margin-left:0px'> &nbsp;(IPTV streaming  device)</span><font color='red'>*</font></td>
                <td align="left" bgcolor="#ffffff">

                <table>
                        <tr>
                                <td>
                                </td>
                                <td>
                                <div class="incrementer" style='height:35px'><input name=STB_COUNT id=STB_COUNT class="plus"  type="text" maxlength="2" pattern="[0-9]*" value="1" data-max="15" readonly style='background: none; border: currentColor; border-image: none; margin-left: 23px;' onchange='CalculateTotal();'></input></div>
                                </td>
                        </tr>

                        <tr>
                                <td>Entone:<span style='float:right' id=entonespan name=entonespan>($200.00 ea.)&nbsp;&nbsp;</span></td>
                                <td>
                                        <div class="incrementer" style='height:35px'><a class="btn-secondary" href="" onclick="changenum('dec','cntentone');return false;"><i class="icon-minus">-</i></a><input name=cntentone id=cntentone class="plus"  type="text" maxlength="2" pattern="[0-9]*" value="1" data-max="15"><a class="btn-secondary"  href="" onclick="changenum('inc','cntentone');return false;"><i class="icon-plus">+</i></a><br></div>
                </td>
                                </td>
                        </tr>
                        <tr>
                                <td>Wnc: <span style='float:right' id=wncspan name=wncspan>($150.00 ea.)&nbsp;&nbsp;</span></td>
                                <td>
                                        <div class="incrementer" style='height:35px'><a class="btn-secondary" href="" onclick="changenum('dec','cntwnc');return false;"><i class="icon-minus">-</i></a><input name=cntwnc id=cntwnc class="plus"  type="text" maxlength="2" pattern="[0-9]*" value="0" data-max="15"><a class="btn-secondary"  href="" onclick="changenum('inc','cntwnc');return false;"><i class="icon-plus">+</i></a></div>
                </td>
                                </td>
                        </tr>
                        <tr style='display:none'>
                                <td>Amino: <span style='float:right' id=aminospan name=aminospan>($150.00 ea.)&nbsp;&nbsp;</span></td>
                                <td>
                                        <div class="incrementer" style='height:35px'><a class="btn-secondary" href="" onclick="changenum('dec','cntamino');return false;"><i class="icon-minus">-</i></a><input name=cntamino id=cntamino class="plus"  type="text" maxlength="2" pattern="[0-9]*" value="0" data-max="15"><a class="btn-secondary"  href="" onclick="changenum('inc','cntamino');return false;"><i class="icon-plus">+</i></a></div>
                </td>
                                </td>
                        </tr>
                        <tr>
                                <td>Use my own device:</td>
                                <td>
                                        <div class="incrementer" style='height:35px'><a class="btn-secondary" href="" onclick="changenum('dec','cntown');return false"><i class="icon-minus">-</i></a><input name=cntown id=cntown class="plus"  type="text" maxlength="2" pattern="[0-9]*" value="0" data-max="15"><a class="btn-secondary"  href="" onclick="changenum('inc','cntown');return false;"><i class="icon-plus">+</i></a><br></div>
                </td>
                                </td>
                        </tr>
                </table>

                <br><br></td>
                </tr>




<tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Adapters: <br>
                  <br>
                </td>
                <td bgcolor="#ffffff" align="left"><select onchange="CalculateTotal();" onfocusout="CalculateTotal();" size="1" name="ADAPTER" id="ADAPTER">
                  <option>--select one--</option>
                  <option value="no">Not required</option>
                  <option value="PLC">PLC $45</option>
                  <option disabled="" value="Coax">MOCA(Coax) $65 (Coming Soon)</option>
                </select></td>
                </tr>
                <tr>
                <td valign="top" bgcolor="#ffffff" align="left"> &nbsp;Number of Adapters: <br><br></td>
                <td bgcolor="#ffffff" align="left"><select onchange="CalculateTotal();" onfocusout="CalculateTotal();" size="1" id="ADAPTERCOUNT" name="ADAPTERCOUNT">
                  <option selected="" value="0">0</option>
                  <option value="--select one--">--select one--</option>
                  <!--<option value="1">1</option>-->
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                </select>
                 </td>
                </tr>






<br>
              <tr style='display:none'>
                <td align="left" bgcolor="#ffffff" valign="top"> &nbsp;Local Phone Provider: <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "LOCAL_PHON" size = "25" maxlength = "25" value = "Bell" /></td>
                </tr>
<link rel="stylesheet" type="text/css" media="all" href="https://www.zazeen.com/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="https://www.zazeen.com/js/jsDatePick.jquery.min.1.3.js"></script>
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
                <td align="left" bgcolor="#ffffff"><input type = "text" id="ACTIVATIONDATE" name = "ACTIVATIONDATE" size = "10" maxlength = "66" value = "<?print date('Y-m-d')?>" /></td>
                </tr>
              <tr>
                <td colspan="2" bgcolor="#ffffff"><br />
                  <center>
                    <b>Customer Information</b>
                    </center>
                  <br /></td>
                </tr>
              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"> &nbsp;Company: <br>
                  <br></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "COMPANY" size = "44" maxlength = "66" value = "" /></td>
                </tr>
              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;First Name: <font color='red'>*<br>
                  <br>
                </font></span></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "FIRST_NAME" size = "44" maxlength = "66" value = "<?if ($_GET["fname"]!="") print $_GET["fname"];?>" /></td>
                </tr>
              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Last Name:</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "LAST_NAME" size = "44" maxlength = "66" value = "<?if ($_GET["lname"]!="") print $_GET["lname"];?>" /></td>
                </tr>
              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"> &nbsp;Work Phone:<br>
                  <br></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "WORK_PHONE" size = "44" maxlength = "66" value = "<?if ($_GET["phone1"]!="") print $_GET["phone1"];?>" /></td>
                </tr>
              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Home Phone #:</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "HOME_PHONE" size = "44" maxlength = "66" value = "<?if ($_GET["phone"]!="") print $_GET["phone"];?>" /></td>
                </tr>
              <tr>
                <td colspan="2" bgcolor="#dddddd">
                  <center>
                    <input type="checkbox" id="USEACANACADDR" name="USEACANACADDR" checked/><b>Use Exsiting Address Info</b> <? print $streetaddr; ?>
                    </center>
                  </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#ffffff">
                  <br></td>
              </tr>
              <tr class="ACANACADDRDIV">
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Street Number:</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "STREET_NUM" size = "25" maxlength = "25" value = "" /></td>
                </tr>
              <tr class="ACANACADDRDIV">
                <td align="left" bgcolor="#ffffff" valign="top"> &nbsp;Street Name: <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "STREET_ADD" size = "44" maxlength = "66" value = "" /></td>
                </tr>
              <tr class="ACANACADDRDIV">
                <td align="left" bgcolor="#ffffff" valign="top"> &nbsp;Street Type: <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><select  name = "STREET_TYP" size="1">
                  <option selected="selected" value = "---select one---">---select one--- </option>
                  <option value = "AV">AV </option>
                  <option value = "BL">BL </option>
                  <option value = "CH">CH </option>
                  <option value = "CIR">CIR </option>
                  <option value = "Cote">Cote </option>
                  <option value = "CR">CR </option>
                  <option value = "CTR">CTR </option>
                  <option value = "DR">DR </option>
                  <option value = "GATE">GATE </option>
                  <option value = "GDNS">GDNS </option>
                  <option value = "GRV">GRV </option>
                  <option value = "HWY">HWY </option>
                  <option value = "Line">Line </option>
                  <option value = "LN">LN </option>
                  <option value = "MTEE">MTEE </option>
                  <option value = "PKWY">PKWY </option>
                  <option value = "PL">PL </option>
                  <option value = "PRIV">PRIV </option>
                  <option value = "RD">RD </option>
                  <option value = "RDWY">RDWY </option>
                  <option value = "ROW">ROW </option>
                  <option value = "RTE">RTE </option>
                  <option value = "RUE">RUE </option>
                  <option value = "SD">SD </option>
                  <option value = "SDRD">SDRD </option>
                  <option value = "SQ">SQ </option>
                  <option value = "ST">ST </option>
                  <option value = "TERR">TERR </option>
                  <option value = "TRL">TRL </option>
                  <option value = "WAY">WAY</option>
                  <option value = "OTHER">OTHER</option>
                  </select></td>
                </tr>
              <tr class="ACANACADDRDIV">
                <td align="left" bgcolor="#ffffff" valign="top"> &nbsp;Buzzer Code #:<br>
                  <br></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "BUZZER_COD" size = "25" maxlength = "25" value = "" /></td>
                </tr>
              <tr class="ACANACADDRDIV">
                <td align="left" bgcolor="#ffffff" valign="top"> &nbsp;Apartment Number:<br>
                  <br></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "APT_OR_UNI" size = "30" maxlength = "30" value = "" /></td>
                </tr>
              <tr class="ACANACADDRDIV">
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;City:</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "CITY" size = "44" maxlength = "66" value = "" /></td>
                </tr>
              <tr class="ACANACADDRDIV">
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Postal Code:</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "POSTAL_COD" size = "44" maxlength = "66" value = "<?if (isset($_GET["pcode"])) print $_GET["pcode"]?>" /></td>
                </tr>
              <tr class="ACANACADDRDIV">
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Country:</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff">

                  <select name='COUNTRY' id='COUNTRY' style='background-color:silver'>
                    <option value='CANADA'>CANADA</option>
                    </select>
                  </tr>
              <tr>
                <td colspan="2" bgcolor="#dddddd">
                  <center>
                    <input type="checkbox" id="USEACANACEMAIL" name="USEACANACEMAIL" checked/><b>Use Exsiting Email Info</b>  <? print $cxemail;?>
                    </center>
                  </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#ffffff">
                  <br></td>
              </tr>

              <tr class="ACANACEMAILDIV">
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;E-mail Address:</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "E_MAIL_ADD" size = "44" maxlength = "66" value = "" /></td>
                </tr>

              <tr>
                <td colspan="2" bgcolor="#ffffff"><br />
                  <center>
                  <b> Credit Card Information </b>
                  <center>
                  <br /></td>
                </tr>
              <tr>
                <td colspan="2" bgcolor="#dddddd">
                  <center>
                    <input type="checkbox" id="USEACANACCC" name="USEACANACCC" checked/><b>Use Exsiting CC Info</b>  <? print $ccnumber;?>
                    </center>
                  </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#ffffff">
                  <br></td>
              </tr>

              <tr class="ACANACCCDIV">
                <td colspan="2" bgcolor="#ffffff"><br /></td>
                </tr>
              <select  style='display:none' name = "CREDIT_TYP" size="1">
                <option value = "---select-one---">---select-one--- </option>
                <option value = "Visa" selected>Visa </option>
                <option value = "Mastercard">Mastercard </option>
                <option value = "Amex">Amex</option>
                </select>
              <!--
                                          <tr>
                                            <td align="left" bgcolor="#ffffff" valign="top"><span style="">Credit Card Type</span> <font color='red'>*</font></td>
                                            <td align="left" bgcolor="#ffffff"><select  name = "CREDIT_TYP" size="1">
                                              <option selected="selected" value = "---select-one---">---select-one--- </option>
                                              <option value = "Visa">Visa </option>
                                              <option value = "Mastercard">Mastercard </option>
                                              <option value = "Amex">Amex</option>
                                            </select></td>
                                          </tr>
                -->
              <tr class="ACANACCCDIV">
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Name on Credit card:</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "NAME_ON_CA" size = "33" maxlength = "55" value = "" /></td>
                </tr>
              <tr class="ACANACCCDIV">
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Credit Card Number:</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><input type = "text" name = "CARD_NUMBE" size = "44" maxlength = "77" value = "" /></td>
                </tr>
              <tr class="ACANACCCDIV">
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Credit Card Expiration Date</span> <font color='red'>*<br>
                  <br>
                </font></td>
                <td align="left" bgcolor="#ffffff"><select  name = "YEAR01" size="1">
                  <option selected="selected" value = "---select-one---">---select-one--- </option>
                  <option value = "01">01 </option>
                  <option value = "02">02 </option>
                  <option value = "03">03 </option>
                  <option value = "04">04 </option>
                  <option value = "05">05 </option>
                  <option value = "06">06 </option>
                  <option value = "07">07 </option>
                  <option value = "08">08 </option>
                  <option value = "09">09 </option>
                  <option value = "10">10 </option>
                  <option value = "11">11 </option>
                  <option value = "12">12</option>
                  </select>
                  <select  name = "YEAR" size="1">
                    <option selected="selected" value = "---select-one---">---select-one--- </option>
                    <option value = "2014">2014 </option>
                    <option value = "2015">2015 </option>
                    <option value = "2016">2016 </option>
                    <option value = "2017">2017 </option>
                    <option value = "2018">2018 </option>
                    <option value = "2019">2019 </option>
                    <option value = "2020">2020</option>
                    <option value = "2021">2021</option>
                    <option value = "2022">2022</option>
                    <option value = "2023">2023</option>
                    <option value = "2024">2024</option>
                    </select></td>
                </tr>
                                          <tr>
                                            <td colspan="2" ><br />
                                              <center>
                                              <b> Account Security </b>
                                              <center>
                                              <br /></td>
                                          </tr>
                                          <tr>
                                            <td align="left" valign="top" title='Use this password to access and modify account information.'>


<span style="margin-left:5px">Security Password(Optional)</font>
<a onclick="document.getElementById('informationcontent2').style.display='none';document.getElementById('informationcontent1').style.display='block';document.getElementById('information').style.display='block'"><img src=https://www.zazeen.com/images/Info_Icon.gif></a>
</span><br><br>

<style>
table.ex6,div.ex7
{
border-radius: 3px;
-moz-border-radius: 3px;
-khtml-border-radius: 3px;
-webkit-border-radius: 3px;

-moz-border-radius: 5px;
 border-radius: 5px;
 -moz-box-shadow: 5px 5px 5px black;
 -webkit-box-shadow: 5px 5px 5px black;
 box-shadow: 5px 5px 5px black;
}
a.exi8:active,a.ex8:hover,a.ex8:link {color:#404040;text-decoration:none;}
a.ex8:visited {color:#404040;text-decoration:none;}

</style>
<div style='position: absolute'>
<div class=ex7 id="information" style="display:none;top: -100px; left: 200px; width: 350px;height:150px; position: absolute; visibility: visible;display:none;background-color:silver">
<table class=ex6 border=0 width=100% style='height:100%;background-color:grey;border-collapse: collapse'>
<tr><td>
<table class=ex6 border=1 width=100% style='height:100%;background-color:#e0e0e0;border-collapse: collapse'>
<tr><td valign=top>
<table>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2 align='center'><h5>Information</h5></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr>
        <td colspan=2 valign=top>
        <label id=informationcontent1  name=informationcontent1 style='display:none;margin-left:20px'>Use this password to access and modify account information.</label>
        <label id=informationcontent2  name=informationcontent2 style='display:none'>NA</label><br></td>
</tr>
<tr>
        <td align=center>
        <a class=ex8 style="TEXT-DECORATION: none; BACKGROUND-COLOR: Silver;border:solid;border-width:1px;border-color:Grey" href='javascript:void(0)' onclick='document.getElementById("information").style.display="none";return false;'>&nbsp;Close&nbsp;</a>
        </td>
</tr>
</table>
</td></tr>
</table>
</td></tr>
</table>
</div>
</div>
</td>
                                            <td align="left"><input type = "text" name = "userpassword" size = "44" maxlength = "55" value = "" /></td>
                                          </tr>

                                          <tr>
                                            <td colspan="2" ><br />
                                              <center>
                                              <b> Giving Back </b>
                                              <center>
                                              <br /></td>
                                          </tr>
              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;<span>Date Of Birth(Optional):</span></span>
                  <br>
                </td>
                <td align="left" bgcolor="#ffffff">

Month: <select name=birthdaym id=birthdaym>
<option>
<option>Jan
<option>Feb
<option>Mar
<option>Apr
<option>May
<option>Jun
<option>Jul
<option>Aug
<option>Sep
<option>Oct
<option>Nov
<option>Dec
</select>
&nbsp;Day: <select name=birthdayd id=birthdayd>
<option>
<?for ($i=1;$i<=31;$i++) print "<option>".$i."\n";?>
</select>
&nbsp;Year:
<select name=birthdayy id=birthdayy>
<option>
<?for ($i=2005;$i>=1910;$i--) print "<option>".$i."\n";?>
</select>
<br><table width=95%><tr><td style='padding-top:5px'>Why do we ask for your date of birth? Zazeen believes in giving back to our customers for their loyalty. By entering
your birth date you automatically qualify for service discounts or gifts on your anniversary.<br><br></td></tr></table></td>
                </tr>
                                          <tr>
                                            <td colspan="2" ><br />
                                              <center>
                                              <b> Installation Instruction </b>
                                              <center>
                                              <br /></td>
                                          </tr>
              <input style='display:none' type="text" name="CSV" id="CSV" />

              <!--
                                          <tr>
                                            <td align="left" bgcolor="#ffffff" valign="top"><span style="">CSV Code:</span></td>
                                            <td align="left" bgcolor="#ffffff"><input type="text" name="CSV" id="CSV" /></td>
                                          </tr>
                -->
             <?/*
require_once "http://zazeen/home/zazeen/public_html/inc.php";
if ($ConfirmedAgentID!=="")
        $_GET['id']=$ConfirmedAgentID;*/
?>
<!--              <tr <?$agentid="";if ($_GET['id']!="") $agentid=preg_replace("/[^0-9\-]/","",$_GET['id']);?>>
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Agent ID if applicable<br>
                  <br>
                </span></td>
                <td align="left" bgcolor="#ffffff"><input name = "AGENTID" id="AGENTID"  <? if ($agentid!="") echo " value='$agentid'"; if ($agentid!="") print "readonly style='background-color:silver'";?>></td>
                </tr>-->
              <tr>
                <td align="left" bgcolor="#ffffff" valign="top"><span style=""> &nbsp;Additional Comments</span><br>
                  <br>
                  <br>
                  <br>
                  <br>
<br></td>
                <td align="left" bgcolor="#ffffff"><textarea id = "COMMENTS" name = "COMMENTS" rows = "7" cols="44"></textarea></td>
                </tr>

                <tr>
                        <td align="left" bgcolor="#ffffff" valign="top" <? if ($_GET["result"]=="submitted") print "style='display:none'";?>>
                                <span  name=pricebreakdown id=pricebreakdown >Price breakdown:</span>
                        </td>
                        <td align="left" bgcolor="#ffffff" valign="top">
                                <div name=subtotal id=subtotal><script>CalculateTotal()</script></div>
                        </td>
                </tr>
<!--              <tr>
                <td colspan="2" align=center>
<div class="col-2">
                  <div class="box2 indent">
                    <div class="border-top">
                      <div class="border-right">
                        <div class="border-bot">
                          <div class="border-left">
                            <div class="left-top-corner">
                              <div class="right-top-corner">
                                <div class="right-bot-corner">
                                  <div class="left-bot-corner">
                                    <div class="inner">
                                      <p><br />
<p>

                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </td>
              </tr>
-->
              <tr>
                <td colspan="2" align="center" class="phpForms_main"><label>
                  <textarea name="User Policeis" style='width:100%' rows="10" readonly="readonly" id="User Policeis">

The rendering of an invoice(s) by Zazeen Inc. shall be construed as an offer to extend this agreement and the payment of such invoice(s) by customer shall be construed as an acceptance. If such invoice(s) are not paid within fifteen (15) days of presentment, in the legal money of Canada, Zazeen Inc. may terminate this agreement and discontinue services.

 Limitation of Liability and Indemnification

Neither Zazeen Inc. nor its officers, directors or employees may be held liable for (i) any claim, damage, or loss (including but not limited to profit loss), or (ii) any damage as a result of service outage, data loss. The customer hereby waives any and all such claims or causes of action, arising from or relating to any service outage and unless it is proven that the act or omission proximately causing the claim, damage, or loss constitutes gross negligence, recklessness, or intentional misconduct on the part of Zazeen Inc.. Subject to the provisions of this agreement, Zazeen Inc. does not provide any other warranties of any kind either express or implied, including without limitation the warranties of merchantability and fitness for a particular purpose.

The customer agrees to defend, indemnify, and hold harmless Zazeen Inc., its officers, directors, employees, affiliates, agents, legal representatives and any other service provider who offers services to the customer or Zazeen Inc. in relation with the present agreement or the service provided, from any and all claims, losses, damages, fines, penalties, costs and expenses (including, without limitation, legal fees and expenses) by, or on behalf of, the customer, any third party or user of the customers' service relating to the absence, failure or outage of the service.

1. Confidentiality and information security policy.Online shopping with Zazeen Inc is secure. Personal and credit card information entered on the site is transmitted electronically in a format that cannot be intercepted, altered or decoded by a third party as it is encrypted to ensure its confidentiality. Zazeen Inc. complies with SSL encryption standards, under which transaction information will always be transmitted securely.

2.0 Shipping - Zazeen only will only ship items within canada. STBs shipped within 5 - 7 business days unless otherwise advised by a customer service representative.

Auto Downgrades
 Any client that is on a term larger then 1 month and has outstanding invoices will be automatically downgraded to the month to month term. This means that if you normally pay annually and your credit card fails we will automatically start to bill you month to month on the monthly rate. If you wish to cancel please e-mail billing@Zazeen.com and request cancelation.

The decisions made by the Canadian Radio and Television Commission (the "CRTC") and other regulatory bodies with jurisdiction over Zazeen and its services affect the contracts for services you have contracted for with us and Zazeen's costs of providing them . Therefore all cited prices for all services Zazeen offers to you either on an initial term or on a renewal of your contract will be increased immediately in the event that the CRTC or other regulatory bodies may issue orders that may apply to the services you contract for or the price for those services. The increases in costs to Zazeen for providing those services will be passed on to you, the consumer. Zazeen Inc. therefore reserves the right to increase the rates you pay and increase the amount on your bills to reflect these increased costs

Even if Zazeen were to be found to be negligent or at fault, Zazeen Inc. shall not be liable for more than a refund of the monies paid by Customer to Zazeen. Zazeen makes no representation as to the merchantability or fitness for any purpose of the Phone or DSl service and ancillary derive to be provided to customer.

Customer agrees to comply with all applicable governmental laws in their use of their service and ancillary services provided by Zazeen Inc., and, in the event of any non-compliance, agrees to hold harmless Zazeen and its personnel and contractors from the consequence of such non-compliance.

If any action in law or equity is instituted by either party here to with respect to the subject matter of this agreement, the prevailing party shall be entitled to recover, in addition to any other relief granted, reasonable attorney's fees, legal costs, and expenses reasonably incurred. This is the entire agreement. It may not be changed orally. Any waiver, alteration, or modification of any of the provisions of this agreement will not be valid unless in writing signed by both parties.

Billing. All terms are due up front. If you sign up for the lowest rate then you are likely on the 1 year term. You will be billed the entire term up front and then billed on a yearly basis. All credit cards are billed automatically on their renewal dates. If you do not want to renew your account please cancel the account on or before the renewal date. Cancellations must be done by e-mail and sent to accounting@Zazeen.com or billing@Zazeen.com. Please make sure you obtain the cancellation ID or ticket number for your request to confirm cancellation of service.

Promo Codes. Promo codes are only applicable for the first term. After the initial term is over the accounts will auto renew at the regular rate. If you wish to cancel please do so by contacting billing@Zazeen.com. Please note that any customer that has already used the promo code once will not be able to sign up using the promo code again. Any client who attempts to cancel service and initiate again to obtain the promo price will be refused.

Furthermore, promo prices are only applicable once per household. Any attempt to place orders with a different name at an address previously supplied with service will also be denied.

Disclosure. We may disclose the personal information of the client such as the client's identity and the clients address and phone numbers and related information without the knowledge or consent of the client when

a) we are required to comply with a subpoena or warrant issued or an order made by a court, person or body with jurisdiction to compel the production of information, or

b) to comply with rules of court relating to the production of records; or c) made to a government institution or part of a government institution that has made a request for the information, identified its lawful authority to obtain the information and indicated that

(i) it suspects that the information relates to national security, the defence of Canada or the conduct of international affairs,

(ii) the disclosure is requested for the purpose of enforcing any law of Canada, a province or a foreign jurisdiction, carrying out an investigation relating to the enforcement of any such law or gathering intelligence for the purpose of enforcing any such law, or

(iii) the disclosure is requested for the purpose of administering any law of Canada or a province;

30 Day Money Back. If you are not satisfied with our service for any reason within the first 30 days, Zazeen will provide a full refund. . After the initial 30 day period a customer must complete the remainder of the term. Customer(s) may choose to cancel the account prior to the end of term however, no refund will be issued for that period. Zazeen Inc. understands that due to the nature of our technology and affiliations with other partners, issues can occur which are out of our control. In such cases Zazeen Billing department may choose at our sole discretion to grant refunds on a case by case basis. Furthermore the 30 day unconditional money back guarentee does not apply for renewals. Customers must terminate the agreement before or on the renewal date. Should a client forget to terminate before the renewal date, clients will be required to pay each additional month of service ( at the monthly rate ) and the remainder will be refunded. We also provide a full refund for the STV if cancellation is made within the 30 day no obligation period. After the initial 30 day period Zazeen Inc. will not accept the return of your hardware. Shipping and Handling fees are non-refundable.

Should a client purchase an STB from Zazeen, but decide that they wish to return it must notify us in writing with 10 days of the original purchase date to qualify for a refund. After 10 days, the modem fee is non-refundable. Equipment that is purchased directly from Zazeen has full 1 year warranty, but will not cover physical damage to equipment not consistent with normal wear and tear. As long as your internet services are active with Zazeen and the modem was purchased directly from us, we will troubleshoot any equipment troubles and will provide a replacement modem should one be required, free of additional cost. The defective modem must be returned to Zazeen Inc within 30 days of receiving the replacement equipment.

Disclaimer

Zazeen Inc.cannot be held liable for system downtime, crashes, or data loss. We cannot be held liable for any predicted estimate of profits in which a client would have gained if their service was functioning. Thus, certain equipment, routing, software, and programming used by Zazeen Inc. are not directly owned and written by Zazeen Inc.. Moreover, Zazeen Inc.holds no responsibility for the use of our clients accounts. If any terms or conditions are failed to be followed, the account in question will be automatically deactivated. We reserve the right to remove any account without advanced notice for any reason without restitution as Zazeen Inc.sees fit.

Suspension of Service. The Company reserves the right to suspend the Service, in whole or in part, including any features, at any time in the Company's sole and absolute discretion. If the Company determines that the suspension of the Service is without fault of the Customer, then the Customer may request a credit of the monthly charges for each day the Service was not in effect

Furthermore: Zazeen Inc. retains the right to change any or all of the above Policies, Guidelines, and Disclaimers without notification. Zazeen Inc. reserves the right to terminate or discontinue the Service at any time, for any reason or for no reason, in the Company's sole and absolute discretion. If the Company discontinues or terminates the Service without fault of the Customer, the Customer will only be responsible for usage charges accrued while the Service was in effect and the Customer will be entitled to a credit for the unused portion of the final month's charges.

This agreement shall be governed by the laws of the province of Ontario, Canada, and in the event any litigation must be initiated to reinforced the terms of this agreement, said legal action must be brought in the courts of the Province of Ontario.

Email: info@Zazeen.com
</textarea>
                  </label></td>
                </tr>
              <tr>
                <td colspan="2" align="center" class="phpForms_main"><input type="checkbox" id="box_confirm_df210783a8" name="box_confirm_df210783a8"  style="" />
                  <label for="box_confirm_df210783a8"> I accept the User Policies</label></td>
                </tr>

              <!-- /Page bottom text -->

              </table>
&nbsp;&nbsp;&nbsp;
                <input type = "hidden" name = "rpromo" id="rpromo" size = "44" maxlength = "55" value = "" />
                <input type = "hidden" name = "apromo" id="apromo" size = "44" maxlength = "55" value = "" />
              <div align="center">
                <input type="submit" value="Submit" name="func" style='width:7pc'/>
                <input type="reset" value="Reset" name="Reset" style='width:7pc'/>
              </div>
          </form>

          <?
        }
}

?></td>
        </tr>
    </table>
  </div>
<div align="center">
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
</div>
<div class="col-2"></div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>

<!--******************************************************-->

<?

if ($_GET["result"]=="submitted")

{

?>
</div>
<?

}

?>
</div>
</body>
</html>
&nbsp;</td>
                                  </tr>
                                </table>
                                <h3 class="prev-indent-bot2"><strong></strong><br>
</h3>
</div>
                            </div>
                        </div>
                    </div>
                         <span  style='display:none' name=pricebreakdown id=pricebreakdown <? if ($_GET["result"]=="submitted") print "style='display:none'";?>><strong>Price breakdown</strong></span><br>
                        <div name=subtotal id=subtotal style='display:none'><script>CalculateTotal()</script>
            </section>
            <!--==============================footer=================================-->
        </div>
</div>
        <script type="text/javascript"> Cufon.now(); </script>
    <!-- coded by hitch -->
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['setConversionAttributionFirstReferrer', true]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://testpiwik.canaca.com/graph/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 7]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="https://testpiwik.canaca.com/graph/piwik/piwik.php?idsite=7" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
</body>
</html>
<?php
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}

?>

