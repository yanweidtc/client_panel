<?php
  $n    = "\n";
  $page_title = 'Dependable IT';

  function page_head($login,$admin,$name="",$extra="")
  {
    global $n, $page_title;
    $page = '<!DOCTYPE html>'.$n.
            '<html lang="en">'.$n.
            '  <head>'.$n.
//            '    <meta charset="utf-8">'.$n.
//            '    <meta http-equiv="X-UA-Compatible" content="IE=edge">'.$n.
//            '    <meta name="viewport" content="width=device-width, initial-scale=1.0">'.$n.
   	    '	 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'.$n.
            '    <title>'.$page_title.'</title>'.$n.
		 $extra.
            '    <link href="./js/bootstrap.css" rel="stylesheet">'.$n.
            '    <link href="./js/custom.css" rel="stylesheet">'.$n.
            '    <link href="./js/bootstrap.theme.css" rel="stylesheet">'.$n.
            '    <link href="./js/bootstrap-datetimepicker.min.css" rel="stylesheet">'.$n.
	    '    <link rel="stylesheet" type="text/css" href="js/DT_bootstrap.css">'.$n.
	    '    <!--[if lt IE 9]><script src="./js/html5.js"></script><![endif]-->'.$n.
            '  </head>'.$n.$n.
            '  <body>'.$n.
//            '     <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>'.$n.
            '     <script src="./js/jquery.min.js"></script>'.$n.
//            '     <script src="./js/bootstra"></script>'.$n.
            '     <script src="./js/bootstrap.min.js"></script>'.$n.
            '    <div class="container well" style="width: 1240px;">'.$n.
            '      <div class="page-header" style="padding-bottom: 0px; margin-bottom: 0px; border-bottom-width: 0px;">'.$n.
//            '        <div class="pull-left"><img src="./js/034logo.png" style="float: left;"><h1 style="position: relative; padding-top: 4px; padding-left: 45px;">'.$page_title.'</h1></div>'.$n;
            '        <div class="pull-left"><h2 style="position: relative; padding-top: 4px; padding-left: 45px;">'.$page_title.'</h2></div>'.$n;
    if($login){
	    if($admin){
		$page .= '   <div class="pull-right">'.$n.
		    '          	<div class="span2" style="margin-right: -40px; margin-top: -5px;"><h4>Welcome!</h4><h5> '.$name.' </h5></div>
				<div class="span2"><a class="btn btn-primary" href="main.php">Home</a>   <a class="btn btn-primary" href="logout.php">Logout</a></div>'.$n.
		    '        </div>'.$n;
	    }else{
		$page .= '   <div class="pull-right">'.$n.
		    '          <a class="btn btn-primary" href="main.php">Home</a>'.$n.
		    '          <a class="btn btn-primary" href="logout.php">Logout</a>'.$n.
		    '        </div>'.$n;
	    }
    }
    $page.= '        <div class="clearfix"></div>'.$n.
            '      </div><hr>'.$n.$n;
    return $page;
  }
  function page_foot($ajax=false)
  {
    global $n;
    $page = $n.
	    '      </br>'.$n.
            '      <div class="row footer">'.$n.
            '        <div class="span12">'.$n.
            '          <div class="pull-right">&copy; Copyright '.date("Y").'. All Rights Reserved.&nbsp;&nbsp;&nbsp;</div>'.$n.
            '          <div class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;Beta 1.0</div>'.$n.
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
