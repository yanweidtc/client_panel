<?php
  $n    = "\n";
  $page_title = 'Zazeen Client Panel';

  function page_head($login,$admin,$name="",$extra="")
  {
    global $n, $page_title;
    $page = '<!DOCTYPE html>'.$n.
            '<html lang="en">'.$n.
            '  <head>'.$n.
            '    <meta charset="utf-8">'.$n.
            '    <meta http-equiv="X-UA-Compatible" content="IE=10">'.$n.
            '    <meta name="viewport" content="width=device-width, initial-scale=1.0">'.$n.
            '    <title>'.$page_title.'</title>'.$n.
//	    '<meta http-equiv="Content-Type" content="text/html"  charset="UTF-8">
'        <meta name="description" content="Zazeen provides cutting-edge IPTV service in Ontario & Quebec enjoy over 100 HD channels & save with optional bundles at industry-leading rates." />
        <meta content="True" name="HandheldFriendly">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Signika:600,400,300" rel="stylesheet" type="text/css">
        <!--[if lt IE 9]>
                <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
                <link href="style-ie.css" rel="stylesheet" type="text/css" media="screen">
        <![endif]-->
'.$n.
	    '    <link href="./js/fadediv.css" rel="stylesheet">'.$n.
            '    <link href="./js/new_bootstrap.css" rel="stylesheet">'.$n.
//            '    <link href="./js/css.css" rel="stylesheet">'.$n.
		 $extra.
	    '    <!--[if lt IE 9]><script src="./js/html5.js"></script><![endif]-->'.$n.
            '  </head>'.$n.$n.
            '  <body>'.$n.
//            '     <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>'.$n.
'<script type="text/javascript">
	var isIE11 = !!(navigator.userAgent.match(/Trident/) && !navigator.userAgent.match(/MSIE/)); 
if (isIE11) {
    if (typeof window.attachEvent == "undefined" || !window.attachEvent) {
        window.attachEvent = window.addEventListener;
    }
} 
         </script>'.$n.
            '     <script src="./js/jquery.js"></script>'.$n.
            '     <script src="./js/new_bootstrap.js"></script>'.$n.
	    '     <script src="./js/timeout.js"></script>'.$n.
            '    <div class="container well">'.$n.
            '      <div class="page-header row" style="margin-top:10px;">'.$n.
            '        <div class="pull-left"><a href="../index.html"><img src="../images/ZAZEEN_Logo.png" alt="IPTV & Digital HDTV Services - Zazeen" title="IPTV & Digital HDTV Services - Zazeen" width="182" height="60"></a><label class="hidden-xs" style="font-size:26px;color:#757575;">Client Panel</label></span>
</div>'.$n;
    if($login){
	    if($admin){
		$page .= '   <div class="row pull-right col-md-4 hidden-xs hidden-sm">'.$n.
	 	    '		<div class="col-md-2"></div>'.$n.
		    '          	<div class="col-md-4" style="margin-right: -40px; margin-top: -5px;"><h4>Welcome!</h4><h5> '.$name.' </h5></div>
				<div class="col-md-6 pull-right" style="display:table; width:50%;margin-top: 20px;"><div style="display:table-cell;"><a class="btn btn-success btn-sm" href="main_cli_list.php">Home</a></div><div style="display:table-cell;"><a class="btn btn-success btn-sm" ref="logout.php">Logout</a></div></div>'.$n.
		    '        </div>'.$n;
		$page .= '   <div class="col-md-3 col-md-offset-9 visible-sm visible-xs">'.$n.
		'		<div class="mobb-right" style="margin-top: 10px;"><a class="btn btn-success btn-sm" href="main_cli_list.php">Home</a> <a class="btn btn-success btn-sm" href="logout.php">Logout</a></div>'.$n.
		    '        </div>'.$n;
	    }else{
		$page .= '   <div class="pull-right">'.$n.
		    '          <a class="btn btn-success" href="main_cli_list.php">Home</a>'.$n.
		    '          <a class="btn btn-success" href="logout.php">Logout</a>'.$n.
		    '        </div>'.$n;
	    }
    }
    $page.= '        <div class="clearfix"></div>'.$n.
            '      </div><hr><br>'.$n.$n;
    return $page;
  }
  function page_foot($ajax=false)
  {
    global $n;
    $page = $n.
	    '      <br>'.$n.
            '      <div class="row footer">'.$n.
            '        <div class="col-md-12">'.$n.
            '          <div class="pull-right">&copy; Copyright '.date("Y").'. All Rights Reserved.</div>'.$n.
            '          <div class="pull-left">Beta 1.0</div>'.$n.
            '        </div>'.$n.
            '      </div>'.$n.$n.
            '    </div>'.$n;
    if($ajax)
      $page .= $n;
            
    $page .= $n.
            '    <!--[if lt IE 7 ]>'.$n.
//            '    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>'.$n.
            '    <script src="./CFInstall.min.js"></script>'.$n.
            '    <script>window.attachEvent(\'onload\',function(){CFInstall.check({mode:\'overlay\'})})</script>'.$n.
            '    <![endif]-->'.$n.$n.
            '  </body>'.$n.
            '</html>'.$n.
            '<!-- end :-) -->'.$n;
    return $page;
  }

?>
