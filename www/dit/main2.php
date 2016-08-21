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
	if ($stmt->query("SELECT * from agents where id = '$user_id'")) {
		$stmt->next_record();
		$monthfee = $stmt->f("monthlyfee");
		$activeaccount = $stmt->f("activeaccounts");
		$addonfee = $stmt->f("addonfee");
		$addonpackages = $stmt->f("addonpackages");
		$pdis = $stmt->f("pendinginvoice");
		$update_time = $stmt->f("update_time");
		$onetimeRF = $stmt->f("onetimeRFee");
		$newsignup = $stmt->f("newsignup");
		$cancelled = $stmt->f("cancelled");
		$agent_id = $stmt->f("agentID");
		$uname = $stmt->f("username");
		$utype = $stmt->f("type");
		$magent = $stmt->f("mainAgent");
		$onetimeaccount = $stmt->f("onetimeaccount");
		$monthlyaccount = $stmt->f("monthlyaccount");
		$monthlypdi = $stmt->f("monthlypdi");
		$monthlyday = $stmt->f("monthlyday");
		$onetimeday = $stmt->f("onetimeday");
                $usubtoggle = $stmt->f("subtoggle");
                $ubillable = $stmt->f("billable");
                $unsuspend = $stmt->f("nsuspend");
                $unterminate = $stmt->f("nterminate");

		$subagent= $stmt->f("subAgent");

	$today = date('M');

        $extra = '        <link rel="stylesheet" type="text/css" href="./js/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="./js/shCore.css">
        <link rel="stylesheet" type="text/css" href="./js/demo.css">
        <style type="text/css" class="init">
        </style>
';


   echo page_head(true,true,$uname,$extra); 

   $extra2= '
        <script type="text/javascript" language="javascript" src="./js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="./js/shCore.js"></script>
        <script type="text/javascript" language="javascript" src="./js/demo.js"></script>
        <script type="text/javascript" language="javascript"src="./js/bootstrap.min.js"></script>'."\n";
   //$extra.= '<script type="text/javascript" charset="utf-8" language="javascript" src="https://datatables.net/release-datatables/media/js/jquery.dataTables.js"></script>'."\n";
   $extra2.= '<script type="text/javascript" charset="utf-8" language="javascript" src="js/DT_bootstrap.js"></script>'."\n";

   print $extra2;

      echo '<script type="text/javascript">
         var break_link=true;
//
// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//
$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 5,     // number of pages to cache
        url: \'\',      // script url
        data: null,   // function or object with parameters to send to the server
                      // matching how `ajax.data` works in DataTables
        method: \'GET\' // Ajax HTTP method
    }, opts );

    // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;

    return function ( request, drawCallback, settings ) {
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;

        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }

        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );

        if ( ajax ) {
            // Need data from the server
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));

                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }

            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);

            request.start = requestStart;
            request.length = requestLength*conf.pages;

            // Provide the same `data` options as DataTables.
            if ( $.isFunction ( conf.data ) ) {
                // As a function it is executed with the data object as an arg
                // for manipulation. If an object is returned, it is used as the
                // data object to submit
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }

            settings.jqXHR = $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);

                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    json.data.splice( requestLength, json.data.length );

                    drawCallback( json );
                }
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );

            drawCallback(json);
        }
    }
};

// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register( \'clearPipeline()\', function () {
    return this.iterator( \'table\', function ( settings ) {
        settings.clearCache = true;
    } );
} );
         </script>';
   if($utype == "main" || $utype == "subagent"){
	if($utype == "subagent"){
		$agent_id = $magent."-".$user_id;
	}
   	echo        '        <div class="pull-right">'.$n;
   if($user_id==540){
        echo        '          <a class="btn btn-primary" href="viewsales.php">View Sales List</a>'.$n;
   }
        echo        '          <a class="btn btn-primary" href="admin_stat.php">View Stats</a>'.$n;
        echo        '          <a class="btn btn-primary" href="history.php">View History</a>'.$n;
//        echo        '          <a class="btn btn-primary" href="agentlist.php">Customer Management</a>'.$n;
   if($usubtoggle=="Y" || $utype == "main"){
        echo        '          <a class="btn btn-primary" href="user.php">Manage Logins</a>'.$n;
//	echo        '          <a class="btn btn-primary" href="register.php">Add Accounts</a>'.$n;
   }
	echo        '        </div>'.$n.
           	    '        <div class="clearfix"></div>'.$n;
   
	$sub_total = 0;
   }

?>

<style>
p.one
{
border-style:solid;
border-color:#ff0000 #0000ff;
}
p.three
{
border-style:solid;
border-color:#ff0000 #00ff00 #0000ff;
}
p.four
{
border-style:solid;
border-color:#0000ff #0000ff #0000ff #0000ff;
}
/*.activediv
{
background-color:green !important;
}
.processdiv
{
background-color:yellow !important;
}
.initdiv
{
background-color:yellow !important;
}*/
</style>
<div class="container-fluid">

<?

$today = date("Y-m");


echo "<br>";

print '<div class="yourcalldiv">
	</div><br><br>';

echo '<div class="yourcallfunc" style="display:none"></div>';
echo '<div class="yourcallbl" style="display:none"></div>';
echo '<div class="yourcalltechbl" style="display:none"></div>';


echo '<div id="loadingdiv" style="display:none;"></div>';
//echo '<div id="loadingdiv"></div>';
echo '<div id="alertdiv"></div>';




/*echo '<div class="input-prepend input-group">
        <span class="input-group-addon add-on">Search:</span>
        <input class="filter form-control" id="prependedInput" type="text" style="width:250px;" placeholder="eg. Number, Status">
      </div>';*/

print "<div class=\"actlistdiv\"></div><br>";

        echo '<div style="float:none;margin: 0 auto;text-align: center;"><h3>Main Call List</h3><br></div>';
        //echo '<div class="center-block"><h3>Number Pool Management</h3><br></div>';

   echo     '        <div class="pull-left">';
        echo '  <ul class="nav nav-pills">';
   echo ' <li class="shortcut defaultsh active">
            <a href="#">All (200)</a>
          </li>
          <li class="shortcut">
            <a href="#">Need Call (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">On Call (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Call Later (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Not Interested (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Invalid (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Tech Call Back (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Ordered (0)</a>
          </li>
        </ul>';
   echo        '        </div>';


        echo '<div class="recordsel" id="All" style="display:none"></div>';
        echo '<div class="clearfix"></div>';

        echo '<script type="text/javascript">
        </script>';

        echo '<script>
		function searchHide(){
	/*	    var rex = new RegExp($(\'input.filter\').val(), \'i\');
		    $(\'.searchable tr\').hide();
		    $(\'.searchable tr:first-child\').show();
			$(\'.searchable tr\').filter(function() {
			    return ( rex.test($(this).text()) && $(this).hasClass($(".recordsel").attr("id")) );
			}).show();*/
		}


         $(\'input.filter\').keyup(function() {
		searchHide();
            });

                $(".nav li a").click(function(){
		  var spliteSel = $(this).text().split("(");
                  var selText = spliteSel[0].replace(/ /g,"");
		  //console.log(selText);
                  $(".pooltr").hide();
                  $("."+selText).show();
                  $(".recordsel").attr("id",selText);
                  $(".shortcut").removeClass("active");
                  $(this).parent().addClass("active");

		  searchHide();
                });
        </script>';



print '<br><div class="pooltablediv">
                <div class="clearfix"></div>
                <div class="pooltableinnerdiv">
                        <table id="pooltable" class="display" cellspacing="0" width="100%">
                                <thead>
                                        <tr>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Phone2</th>
                                                <th>Tasks</th>
                                                <th>Result</th>
                                                <th>Note</th>
                                                <th>History</th>
                                        </tr>
                                </thead>

                                <tfoot>
                                        <tr>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Phone2</th>
                                                <th>Tasks</th>
                                                <th>Result</th>
                                                <th>Note</th>
                                                <th>History</th>
                                        </tr>
                                </tfoot>
                        </table>
                </div>
        </div>';




//////////////////////////////////////// Call Back Later Modal ///////////////////////////////////
$addstbtn = '<!-- Button trigger modal -->
<button class="btn btn-success" data-toggle="modal" data-target="#myModal">
  Add New STB
</button>';

print '<script type="text/javascript" src="./js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>';


print '<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Select Call Back Time: </h4>
      </div>
      <div class="modal-body">
   <form action="" class="form-horizontal"  role="form" name=form1 id=form1>
	<fieldset>
	    <div class="form-group">
		<label for="dtp_input1" class="col-md-5 control-label">Call Back After: </label>
		<div class="input-group date form_datetime col-md-5" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="dtp_input1">
		    <input class="form-control" size="16" type="text" value="" readonly>
		    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		</div>
				<input type="hidden" id="dtp_input1" value="" /><br/>
	    </div>
	    <div class="form-group">
		<label for="dtp_input2" class="col-md-5 control-label">Call Back Before( Optional ): </label>
		<div class="input-group date form_datetime col-md-5" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="dtp_input2">
		    <input class="form-control" size="16" type="text" value="" readonly>
		    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		</div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
	    </div>
			<input type="hidden" name="meid" id="meid" value=""/>
	</fieldset>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" class="cbsubmit" id="cbsubmit">Submit</button>
      </div>
    </div>
  </div>
</div>';

print '<script type="text/javascript">
    $(\'.form_datetime\').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	forceParse: 0,
        showMeridian: 1
    });
/*	$(\'.form_date\').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0
    });*/

	$(document).on("click", ".callbl", function () {
	     var entryId = $(this).data(\'id\');
	     $(".modal-body #meid").val( entryId );
	     // it is superfluous to have to manually call the modal.
	     // $(\'#addBookDialog\').modal(\'show\');
	});

</script>';

//////////////////////////////////////// Note Modal ///////////////////////////////////

print '<!-- Modal -->
<div class="modal fade" id="myNote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel2">Note</h4>
      </div>
      <div class="modal-body">
	<input type="hidden" name="noteeid" id="noteeid" value=""/>
	<textarea class="form-control notetextarea" rows="5" name="notetext" id="notetext"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" class="notesubmit" id="notesubmit">Save changes</button>
      </div>
    </div>
  </div>
</div>';

print '<script type="text/javascript">
	$(document).on("click", ".notebtn", function () {
	     var entryId = $(this).data(\'id\');
	     $("#noteeid").val( entryId );
	     var entryName = $(this).data(\'name\');
	     $("#myModalLabel2").text( "Note for "+entryName );

		$("#notetext").load("noteload.php", { \'eid\': entryId}, function(respons) {
			$("#notetext").val( respons );
		});
	     // it is superfluous to have to manually call the modal.
	     // $(\'#addBookDialog\').modal(\'show\');
	});

</script>';




//////////////////////////////////////// Tech Modal ///////////////////////////////////


print '<!-- Modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Select Call Back Time: </h4>
      </div>
      <div class="modal-body">
   <form action="" class="form-horizontal"  role="form" name=form2 id=form2>
	<fieldset>
	    <div class="form-group">
		<label for="sdtp_input1" class="col-md-5 control-label">Call Back After: </label>
		<div class="input-group date form_datetime col-md-5" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="sdtp_input1">
		    <input class="form-control" size="16" type="text" value="" readonly>
		    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		</div>
				<input type="hidden" id="sdtp_input1" value="" /><br/>
	    </div>
	    <div class="form-group">
		<label for="sdtp_input2" class="col-md-5 control-label">Call Back Before( Optional ): </label>
		<div class="input-group date form_datetime col-md-5" data-date="" data-date-format="yyyy-mm-dd HH:ii p" data-link-field="sdtp_input2">
		    <input class="form-control" size="16" type="text" value="" readonly>
		    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
		</div>
				<input type="hidden" id="sdtp_input2" value="" /><br/>
	    </div>
			<input type="hidden" name="smeid" id="smeid" value=""/>
	</fieldset>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" class="techsubmit" id="techsubmit">Submit</button>
      </div>
    </div>
  </div>
</div>';

print '<script type="text/javascript">
    $(\'.form_datetime\').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	forceParse: 0,
        showMeridian: 1
    });
/*	$(\'.form_date\').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0
    });*/

	$(document).on("click", ".techsu", function () {
	     var entryId = $(this).data(\'id\');
	     $(".modal-body #smeid").val( entryId );
	     // it is superfluous to have to manually call the modal.
	     // $(\'#addBookDialog\').modal(\'show\');
	});

</script>';








echo '<script type="text/javascript">
        $(document).ready(function(){

	function alertTimeout(wait){
	    setTimeout(function(){
		$(\'#alertdiv\').children(\'.alert:first-child\').fadeTo(500, 0).slideUp(500, function(){
			$(this).remove(); 
	        });
	    }, wait);
	}

	var actArray = new Array();
	var uniactArray = new Array("noansw","voicem","invald","nointr");

	$.arrayIntersect = function(a, b)
	{
	    return $.grep(a, function(i)
	    {
		return $.inArray(i, b) > -1;
	    });
	};
/*		$.fn.dataTable.ext.type.order[\'result-grade-pre\'] = function ( d ) {
		    if()
		    return 0;
		};
*/


                // Init datatable with pipeline
                    $(\'#pooltable\').dataTable( {
                        "processing": true,
                        "serverSide": true,
			"order": [[ 4, "desc" ],[ 3, "asc" ]],
			"iDisplayLength": 10,
                        "ajax": $.fn.dataTable.pipeline( {
                            url: \'./server_processing.php?agid='.$agent_id.'\',
                            pages: 5 // number of pages to cache
                        } )
                    } );

    		    // DataTable
		    var table = $(\'#pooltable\').DataTable();

		    // Setup - add a text input to each footer cell
		    $(\'#pooltable tfoot th\').each( function (i) {
		        if(i<3){
				var title = $(\'#pooltable thead th\').eq( $(this).index() ).text();
				$(this).html( \'<input type="text" style="width: 140px;" placeholder="Search \'+title+\'" />\' );
			}else if(i==3 || i==4){
				var select = $(\'<select><option value=""></option></select>\')
				    .appendTo( $(this).empty() )
				    .on( \'change\', function () {
					var val = $(this).val();
			 
					table.column( i )
					    .search( val ? \'^\'+$(this).val()+\'$\' : val, true, false )
					    .draw();
				    } );

				table.column( i ).data().unique().sort().each( function ( d, j ) {
				    select.append( \'<option value="\'+d+\'">\'+d+\'</option>\' );
				} );

//			    select.append( \'<option value="\'+\'<span class="label label-warning"> No Answer </span>\'+\'">\'+\'<span class="label label-warning"> No Answer </span>\'+\'</option>\' );
			}
		    } );
				table.column( 5 ).data().unique().sort().each( function ( d, j ) {
					console.log(d);
				} );
		 
		    // Apply the search
		    table.columns().eq( 0 ).each( function ( colIdx ) {
			$( \'input\', table.column( colIdx ).footer() ).on( \'keyup change\', function () {
			    table
				.column( colIdx )
				.search( this.value )
				.draw();
			} );
		    } );

/*		    $("#pooltable tfoot th").each( function ( i ) {
			var select = $(\'<select><option value=""></option></select>\')
			    .appendTo( $(this).empty() )
			    .on( \'change\', function () {
				var val = $(this).val();
		 
				table.column( i )
				    .search( val ? \'^\'+$(this).val()+\'$\' : val, true, false )
				    .draw();
			    } );
		 
			table.column( i ).data().unique().sort().each( function ( d, j ) {
			    select.append( \'<option value="\'+d+\'">\'+d+\'</option>\' )
			} );
		    } );*/


	function checkUnique(){
		var submitact = new Array();
		submitact = $(".yourcallfunc").text().split(",");
		var ret = $.arrayIntersect(submitact,uniactArray);		

		if(ret.length > 1){
			return false;
		}else if(ret.length <= 0){
			return "";
		}else{
			return ret[0];
		}
	}

	function addIfNeed(instr, act){
		var tempArray = new Array();
		if(instr!=""){
			tempArray = instr.split(",");
		}
			if(jQuery.inArray(act, tempArray)!==-1){
				//Exsit, do nothing
			}else{
				//Add to array
				tempArray.push(act);
			}
		return tempArray.join();
	}

	function removeIfNeed(instr, act){
		var tempArray = new Array();
		if(instr!=""){
			tempArray = instr.split(",");
		}
			if(jQuery.inArray(act, tempArray)!==-1){
				//Remove from array;
				var index = jQuery.inArray(act, tempArray);
				tempArray.splice(index, 1);	
			}else{
				//Do nothing
			}
		return tempArray.join();
	}


                $(".nav li a").each(function(){
                  var aspliteSel = $(this).text().split("(");
                  var aselText = aspliteSel[0].replace(/ /g,"");
                  //console.log(selText);

		  $(this).load("countcx.php", { \'cate\': aselText}, function() {
                  });

                });




/*		$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
			var selText = $(".recordsel").attr("id");;
			$(".pooltr").hide();
			$("."+selText).show();

			searchHide();
			//rebind the events
		});*/

		$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
			searchHide();
		});

		var actarrstr = $(".yourcallfunc").text();
		var cbarrstr = $(".yourcallbl").text();
		var techcbarrstr = $(".yourcalltechbl").text();
		$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
		});


	$(document).on("click", "#cbsubmit", function () {
	     var xcbbefore = $("#form1 #dtp_input2").val();
	     var xcbafter = $("#form1 #dtp_input1").val();
	     var cbbefore="";
	     var cbafter="";
	     if(xcbbefore!=""){
	     	cbbefore = xcbbefore.slice(0,-2)+"00";
	     }
	     if(xcbafter!=""){
	     	cbafter = xcbafter.slice(0,-2)+"00";
	     }
	     var cbid = $("#form1 #meid").val();

		$(".yourcallbl").text(cbafter+","+cbbefore);

		var actarrstr = $(".yourcallfunc").text();
		var cbarrstr = $(".yourcallbl").text();
		var techcbarrstr = $(".yourcalltechbl").text();
		$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
		});

		$(\'#myModal\').modal(\'hide\');
	     
/*		$("#loadingdiv").load("update_cbtime.php", { \'dtp_input1\': cbbefore, \'dtp_input2\': cbafter, \'eid\': cbid }, function(response) {
			$("#alertdiv").append(response);
			if(response!=""){
				alertTimeout(7000);
			}

			$(\'#myModal\').modal(\'hide\');

			$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
				var selText = $(".recordsel").attr("id");;
				$(".pooltr").hide();
				$("."+selText).show();

				searchHide();
				//rebind the events
			});

			$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
				searchHide();
			});

			$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\'}, function() {
			});
		});*/

	});



	$(document).on("click", "#techsubmit", function () {
	     var xscbbefore = $("#form2 #sdtp_input2").val();
	     var xscbafter = $("#form2 #sdtp_input1").val();
	     var scbbefore="";
	     var scbafter="";
	     if(xscbbefore!=""){
	     	scbbefore = xscbbefore.slice(0,-2)+"00";
	     }
	     if(xscbafter!=""){
	     	scbafter = xscbafter.slice(0,-2)+"00";
	     }
	     var scbid = $("#form2 #smeid").val();

		$(".yourcalltechbl").text(scbafter+","+scbbefore);

		var actarrstr = $(".yourcallfunc").text();
		var cbarrstr = $(".yourcallbl").text();
		var techcbarrstr = $(".yourcalltechbl").text();
		$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
		});

		$(\'#myModal2\').modal(\'hide\');

/*		$("#loadingdiv").load("ctfunction.php", { \'sdtp_input1\': scbbefore, \'sdtp_input2\': scbafter, \'eid\': scbid, \'action\': \'techsu\' }, function(response) {
			$("#alertdiv").append(response);
			if(response!=""){
				alertTimeout(7000);
			}

			$(\'#myModal2\').modal(\'hide\');

			$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
				var selText = $(".recordsel").attr("id");;
				$(".pooltr").hide();
				$("."+selText).show();

				searchHide();
				//rebind the events
			});

			$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
				searchHide();
			});

			$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\'}, function() {
			});
		});*/

	});

	$(document).on("click", "#notesubmit", function () {
	     var notetext = $("#notetext").val();
	     var noteid = $("#noteeid").val();

		$("#loadingdiv").load("update_note.php", { \'notetext\': notetext, \'eid\': noteid }, function(response) {
			$("#alertdiv").append(response);
			if(response!=""){
				alertTimeout(7000);
			}

/*			$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
				var selText = $(".recordsel").attr("id");;
				$(".pooltr").hide();
				$("."+selText).show();

				searchHide();
				//rebind the events
			});*/


			$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\'}, function() {
			});

			$(\'#myNote\').modal(\'hide\');
		});

	});


                $(document).on(\'click\', \'.donebtn\', function() {
                        var dclassname = $(this).attr(\'id\');
                        var eid = dclassname.substring(6);
                        var dact = dclassname.substring(0,6);

			if(dact == "zorder"){
				//Submitting Order
                                $("#loadingdiv").load("ctfunction.php", { \'action\': dact, \'eid\': eid }, function(response) {
                                        $("#alertdiv").append(response);
                                        if(response!=""){
                                                alertTimeout(7000);
                                        }

/*                                        $(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
                                                var selText = $(".recordsel").attr("id");;
                                                $(".pooltr").hide();
                                                $("."+selText).show();

                                                //rebind the events
                                                searchHide();
                                        });*/

                                        $(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
                                                searchHide();
                                        });

					var actarrstr = $(".yourcallfunc").text();
					var cbarrstr = $(".yourcallbl").text();
					var techcbarrstr = $(".yourcalltechbl").text();
					$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
					});
                                });
                                window.open(\'ditorder.php?eid=\'+eid,\'_blank\',\'\') ;
			}else if(dact == "godone"){
				//Done selection
				var sact = new Array();
				sact = $(".yourcallfunc").text().split(",");

				var uniact = checkUnique();

				if(uniact === false){
					alert("Invalid Action Combo! You can only select one from No Answer, Voicemail, Invalid Phone, and Not Interested.");
				}else{
//					console.log("processing "+uniact);
					if(uniact != ""){
						$("#loadingdiv").load("ctfunction.php", { \'action\': uniact, \'eid\': eid }, function(response) {
							$("#alertdiv").append(response);
							if(response!=""){
								alertTimeout(7000);
							}
							
							//If Send Email
							if(jQuery.inArray("sendem", sact)!==-1){
								$("#loadingdiv").load("ctfunction.php", { \'action\': \'sendem\', \'eid\': eid, \'group\':\'Y\' }, function(response) {
									$("#alertdiv").append(response);
									if(response!=""){
										alertTimeout(7000);
									}
								});
							}


							//If Tech Support
							if(jQuery.inArray("techsu", sact)!==-1){
								var tybl = $(".yourcalltechbl").text().split(",");
								$("#loadingdiv").load("ctfunction.php", { \'sdtp_input1\': tybl[0], \'sdtp_input2\': tybl[1], \'eid\': eid, \'action\': \'techsu\' }, function(response) {
									$("#alertdiv").append(response);
									if(response!=""){
										alertTimeout(7000);
									}


									//If Callback
									if(jQuery.inArray("callbl", sact)!==-1){
										var ybl = $(".yourcallbl").text().split(",");
										$("#loadingdiv").load("update_cbtime.php", { \'dtp_input1\': ybl[0], \'dtp_input2\': ybl[1], \'eid\': eid }, function(response) {
											$("#alertdiv").append(response);
											if(response!=""){
												alertTimeout(7000);
											}

											//Final Refresh
/*											$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
												var selText = $(".recordsel").attr("id");;
												$(".pooltr").hide();
												$("."+selText).show();

												//rebind the events
												searchHide();
											});*/

											$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
												searchHide();
											});

											var actarrstr = $(".yourcallfunc").text();
											var cbarrstr = $(".yourcallbl").text();
											var techcbarrstr = $(".yourcalltechbl").text();
											$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
											});
										});
										
									}else{

										//Final Refresh
/*										$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
											var selText = $(".recordsel").attr("id");;
											$(".pooltr").hide();
											$("."+selText).show();

											//rebind the events
											searchHide();
										});*/

										$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
											searchHide();
										});

										var actarrstr = $(".yourcallfunc").text();
										var cbarrstr = $(".yourcallbl").text();
										var techcbarrstr = $(".yourcalltechbl").text();
										$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
										});
									}
								});

							}else{

									//If Callback
									if(jQuery.inArray("callbl", sact)!==-1){
										var ybl = $(".yourcallbl").text().split(",");
										$("#loadingdiv").load("update_cbtime.php", { \'dtp_input1\': ybl[0], \'dtp_input2\': ybl[1], \'eid\': eid }, function(response) {
											$("#alertdiv").append(response);
											if(response!=""){
												alertTimeout(7000);
											}

											//Final Refresh
/*											$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
												var selText = $(".recordsel").attr("id");;
												$(".pooltr").hide();
												$("."+selText).show();

												//rebind the events
												searchHide();
											});*/

											$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
												searchHide();
											});

											var actarrstr = $(".yourcallfunc").text();
											var cbarrstr = $(".yourcallbl").text();
											var techcbarrstr = $(".yourcalltechbl").text();
											$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
											});
										});
										
									}else{

										//Final Refresh
/*										$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
											var selText = $(".recordsel").attr("id");;
											$(".pooltr").hide();
											$("."+selText).show();

											//rebind the events
											searchHide();
										});*/

										$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
											searchHide();
										});

										var actarrstr = $(".yourcallfunc").text();
										var cbarrstr = $(".yourcallbl").text();
										var techcbarrstr = $(".yourcalltechbl").text();
										$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
										});
									}
							}
						});
					}else{
							//If Send Email
							if(jQuery.inArray("sendem", sact)!==-1){
								if(sact.length==1){
									$("#loadingdiv").load("ctfunction.php", { \'action\': \'sendem\', \'eid\': eid }, function(response) {
										$("#alertdiv").append(response);
										if(response!=""){
											alertTimeout(7000);
										}
									});
								}else{
									$("#loadingdiv").load("ctfunction.php", { \'action\': \'sendem\', \'eid\': eid, \'group\':\'Y\' }, function(response) {
										$("#alertdiv").append(response);
										if(response!=""){
											alertTimeout(7000);
										}
									});
								}
							}


							//If Tech Support
							if(jQuery.inArray("techsu", sact)!==-1){
								var tybl = $(".yourcalltechbl").text().split(",");
								$("#loadingdiv").load("ctfunction.php", { \'sdtp_input1\': tybl[0], \'sdtp_input2\': tybl[1], \'eid\': eid, \'action\': \'techsu\' }, function(response) {
									$("#alertdiv").append(response);
									if(response!=""){
										alertTimeout(7000);
									}


									//If Callback
									if(jQuery.inArray("callbl", sact)!==-1){
										var ybl = $(".yourcallbl").text().split(",");
										$("#loadingdiv").load("update_cbtime.php", { \'dtp_input1\': ybl[0], \'dtp_input2\': ybl[1], \'eid\': eid }, function(response) {
											$("#alertdiv").append(response);
											if(response!=""){
												alertTimeout(7000);
											}

											//Final Refresh
/*											$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
												var selText = $(".recordsel").attr("id");;
												$(".pooltr").hide();
												$("."+selText).show();

												//rebind the events
												searchHide();
											});*/

											$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
												searchHide();
											});

											var actarrstr = $(".yourcallfunc").text();
											var cbarrstr = $(".yourcallbl").text();
											var techcbarrstr = $(".yourcalltechbl").text();
											$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
											});
										});
										
									}else{

										//Final Refresh
/*										$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
											var selText = $(".recordsel").attr("id");;
											$(".pooltr").hide();
											$("."+selText).show();

											//rebind the events
											searchHide();
										});*/

										$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
											searchHide();
										});

										var actarrstr = $(".yourcallfunc").text();
										var cbarrstr = $(".yourcallbl").text();
										var techcbarrstr = $(".yourcalltechbl").text();
										$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
										});
									}
								});

							}else{

									//If Callback
									if(jQuery.inArray("callbl", sact)!==-1){
										var ybl = $(".yourcallbl").text().split(",");
										$("#loadingdiv").load("update_cbtime.php", { \'dtp_input1\': ybl[0], \'dtp_input2\': ybl[1], \'eid\': eid }, function(response) {
											$("#alertdiv").append(response);
											if(response!=""){
												alertTimeout(7000);
											}

											//Final Refresh
/*											$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
												var selText = $(".recordsel").attr("id");;
												$(".pooltr").hide();
												$("."+selText).show();

												//rebind the events
												searchHide();
											});*/

											$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
												searchHide();
											});

											var actarrstr = $(".yourcallfunc").text();
											var cbarrstr = $(".yourcallbl").text();
											var techcbarrstr = $(".yourcalltechbl").text();
											$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
											});
										});
										
									}else{

										//Final Refresh
/*										$(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
											var selText = $(".recordsel").attr("id");;
											$(".pooltr").hide();
											$("."+selText).show();

											//rebind the events
											searchHide();
										});*/

										$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
											searchHide();
										});

										var actarrstr = $(".yourcallfunc").text();
										var cbarrstr = $(".yourcallbl").text();
										var techcbarrstr = $(".yourcalltechbl").text();
										$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
										});
									}
							}
						
					}

				}
			}else{
				alert("Invalid Action!");
			}
                });

                $(document).on(\'click\', \'.yfuncbtn\', function() {
                        var classname = $(this).attr(\'id\');
                        var eid = classname.substring(6);
                        var act = classname.substring(0,6);

    			$(this).button(\'toggle\');

			if($(this).hasClass("active")){
				var newActArr = addIfNeed($(".yourcallfunc").text(), act );
				$(".yourcallfunc").text(newActArr);


				//Show Modals if check
				if(act == "callbl"){
					var callbls = $(".yourcallbl").text();
					var callblarr = callbls.split(",");
				        $("#form1 #dtp_input1").val(callblarr[0]);
				        $("#form1 #dtp_input2").val(callblarr[1]);
					$(\'#myModal\').modal(\'show\');
				}else if(act == "techsu"){
					var scallbls = $(".yourcalltechbl").text();
					var scallblarr = scallbls.split(",");
				        $("#form2 #sdtp_input1").val(scallblarr[0]);
				        $("#form2 #sdtp_input2").val(scallblarr[1]);
					$(\'#myModal2\').modal(\'show\');
				}
			}else{
				var newActArr = removeIfNeed($(".yourcallfunc").text(), act );
				$(".yourcallfunc").text(newActArr);
			}

			var actarrstr = $(".yourcallfunc").text();
			var cbarrstr = $(".yourcallbl").text();
			var techcbarrstr = $(".yourcalltechbl").text();
			$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
			});

                });

                $(document).on(\'click\', \'.funcbtn\', function() {
                        var classname = $(this).attr(\'id\');
                        var eid = classname.substring(6);
                        var act = classname.substring(0,6);

                        if(act == "callbl"){
			}else if(act == "techsu"){

			}else if(act == "zorder"){
                                $("#loadingdiv").load("ctfunction.php", { \'action\': act, \'eid\': eid }, function(response) {
                                        $("#alertdiv").append(response);
                                        if(response!=""){
                                                alertTimeout(7000);
                                        }

/*                                        $(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
                                                var selText = $(".recordsel").attr("id");;
                                                $(".pooltr").hide();
                                                $("."+selText).show();

                                                //rebind the events
                                                searchHide();
                                        });*/

                                        $(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
                                                searchHide();
                                        });

					var actarrstr = $(".yourcallfunc").text();
					var cbarrstr = $(".yourcallbl").text();
					var techcbarrstr = $(".yourcalltechbl").text();
					$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
					});
                                });
                                window.open(\'ditorder.php?eid=\'+eid,\'_blank\',\'\') ;
                        }else{
				if(act=="oncall"){
					$(".yourcallfunc").text("");
					$(".yourcallbl").text("");
					$(".yourcalltechbl").text("");
				}

                                //console.log(fmark);
                                $("#loadingdiv").load("ctfunction.php", { \'action\': act, \'eid\': eid }, function(response) {
                                        $("#alertdiv").append(response);
					if(response!=""){
						alertTimeout(7000);
					}

/*                                        $(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
                                                var selText = $(".recordsel").attr("id");;
                                                $(".pooltr").hide();
                                                $("."+selText).show();

                                                //rebind the events
						searchHide();
                                        });*/

					$(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
						searchHide();
					});

					var actarrstr = $(".yourcallfunc").text();
					var cbarrstr = $(".yourcallbl").text();
					var techcbarrstr = $(".yourcalltechbl").text();
					$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
					});
                                });
                        }
			
                });

                var refreshId = setInterval(function()
                {
/*                        $(".pooltablediv").load("maintable.php", { \'sel\': \'go\'}, function() {
                                var selText = $(".recordsel").attr("id");;
                                $(".pooltr").hide();
                                $("."+selText).show();
				searchHide();
                        });*/

			table.ajax.reload( function ( json ) {
				table.draw(false);
			}, false);

                        $(".actlistdiv").load("cbtable.php", { \'sel\': \'go\'}, function() {
				searchHide();
                        });

			var actarrstr = $(".yourcallfunc").text();
			var cbarrstr = $(".yourcallbl").text();
			var techcbarrstr = $(".yourcalltechbl").text();
			$(".yourcalldiv").load("yourcall.php", { \'sel\': \'go\', \'actarr\': actarrstr, \'cbarr\': cbarrstr, \'techcbarr\': techcbarrstr}, function() {
			});

			

			$(".nav li a").each(function(){
			  var aspliteSel = $(this).text().split("(");
			  var aselText = aspliteSel[0].replace(/ /g,"");
			  //console.log(selText);

			  $(this).load("countcx.php", { \'cate\': aselText}, function() {
			  });

			});
                }, 5000);
        });
</script>';

?>
</div>

<?

   echo page_foot($ajax);
 }
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}


?>
