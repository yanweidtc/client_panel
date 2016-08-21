<?php
  $n    = "\n";
  $page_title = 'Zazeen Agent Panel';

  function page_head($login,$admin,$name="")
  {
    global $n, $page_title;
    $page = '<!DOCTYPE html>'.$n.
            '<html lang="en">'.$n.
            '  <head>'.$n.
            '    <meta charset="utf-8">'.$n.
            '    <meta http-equiv="X-UA-Compatible" content="IE=edge">'.$n.
            '    <meta name="viewport" content="width=device-width, initial-scale=1.0">'.$n.
            '    <title>'.$page_title.'</title>'.$n.
            '    <link href="./js/bootstrap.css" rel="stylesheet">'.$n.
	    '    <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->'.$n.
            '  </head>'.$n.$n.
            '  <body>'.$n.
            '     <script src="http://code.jquery.com/jquery.js"></script>'.$n.
            '     <script src="./js/bootstrap.js"></script>'.$n.
            '    <div class="container well">'.$n.
            '      <div class="page-header">'.$n.
            '        <div class="pull-left"><h1>'.$page_title.'</h1></div>'.$n;
    if($login){
	    if($admin){
		$page .= '   <div class="row pull-right">'.$n.
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
            '      </div><hr><br>'.$n.$n;
    return $page;
  }
  function page_foot($ajax=false)
  {
    global $n;
    $page = $n.
	    '      </br>'.$n.
            '      <div class="row footer">'.$n.
            '        <div class="span12">'.$n.
            '          <div class="pull-right">&copy; Copyright 2012. All Rights Reserved.</div>'.$n.
            '          <div class="pull-left">Beta 1.0</div>'.$n.
            '        </div>'.$n.
            '      </div>'.$n.$n.
            '    </div>'.$n;
    if($ajax)
      $page .= $n;
            
    $page .= $n.
            '    <!--[if lt IE 7 ]>'.$n.
            '    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>'.$n.
            '    <script>window.attachEvent(\'onload\',function(){CFInstall.check({mode:\'overlay\'})})</script>'.$n.
            '    <![endif]-->'.$n.$n.
            '  </body>'.$n.
            '</html>'.$n.
            '<!-- end :-) -->'.$n;
    return $page;
  }

?>
