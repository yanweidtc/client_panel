<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'agentcx';

// Table's primary key
$primaryKey = 'id';

$agent_id=$_GET["agid"];

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 'id', 'dt' => 0 ),
	array( 'db' => 'callagent', 'dt' => 0 ),
	array( 'db' => 'callnum', 'dt' => 0 ),
	array( 'db' => 'email', 'dt' => 0 ),
	array( 'db' => 'agentid', 'dt' => 0 ),
	array( 'db' => 'unlockflag', 'dt' => 0 ),
	array( 'db' => 'company', 'dt' => 0 ),
	array( 'db' => 'emailflag', 'dt' => 0 ),
	array( 'db' => 'callbefore', 'dt' => 0 ),
	array( 'db' => 'callafter', 'dt' => 0 ),
	array( 'db' => 'name', 'dt' => 0 ),
	array( 'db' => 'phone',  'dt' => 1 ),
	array( 'db' => 'phone2',  'dt' => 2 ),
	array(
		'db'        => 'status',
		'dt'        => 3,
		'formatter' => function( $status, $row ) {
		if($status=="active"){
                }else if($status=="zorder"){
                        $statuslabel = '<span class="label label-success">'.$row["result"].'</span>';
                        $statusclass = "Ordered success pooltr All";
                        $btn = '<span class="label label-success">Customer submitted an order</span>';
                }else if($status=="failed"){
                        $statuslabel = '<span class="label label-danger">'.$status.'</span>&nbsp;&nbsp;&nbsp;<span class="label label-default">'.$row["result"].'</span>';
                        $statusclass = "NotInterested danger pooltr All";
                        $btn = '<span class="label label-default">Customer not interested</span>';
                }else if($status=="invald"){
                        $statuslabel = '<span class="label label-danger">'.$status.'</span>&nbsp;&nbsp;&nbsp;<span class="label label-default">'.$row["result"].'</span>';
                        $statusclass = "Invalid danger pooltr All";
                        if($row["result"]=='<span class="label label-danger"> Invalid Number </span>'){
                                $btn = '<span class="label label-default">The phone number is invalid.</span>';
                        }else{
                                $btn = '<span class="label label-default">Customer not interested</span>';
                        }
                }else if($status=="oncall"){
                        $statuslabel = '<span class="label label-primary">'.$status.'</span>';
                        $statusclass = "OnCall success pooltr All";

                        $callingstr='<span class="label label-warning">'.$row["callagent"].' is calling</span>';


                        // All Func
                        $btn = '<button type="button" class="btn btn-xs btn-warning funcbtn" id="noansw'.$row["id"].'">No Answer</button>&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-info funcbtn" id="voicem'.$row["id"].'">Voicemail</button>&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-danger funcbtn" id="nointr'.$row["id"].'">Not Interested</button>&nbsp;&nbsp;';
                        //$btn .= '<button type="button" class="btn btn-xs btn-default funcbtn" id="callbl'.$eid.'">Call Back Later</button>&nbsp;&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-default funcbtn callbl" data-id="'.$row["id"].'" data-toggle="modal" data-target="#myModal" id="callbl'.$row["id"].'">Call Back Later</button>&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-primary funcbtn techsu" data-id="'.$row["id"].'" data-toggle="modal" data-target="#myModal2" id="techsu'.$row["id"].'"><span class="glyphicon glyphicon-wrench"></span>&nbsp;&nbsp;Tech Support</button>&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-primary funcbtn zorder" id="zorder'.$row["id"].'">&nbsp;&nbsp;Order Now</button>&nbsp;&nbsp;';
//                      $btn .= '<button type="button" class="btn btn-mini btn-success funcbtn" id="oncall'.$eid.'"></button>';

/*                        if($row["agentid"] == $agent_id){
                                $phonestr = $callingstr;
                        }else{*/
                                if(strpos($agent_id, '-') !== false){
                                        $btn = $callingstr.'&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-danger funcbtn hangup" id="hangup'.$row["id"].'"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Force Hang Up </button>';
                                }else{
                                        $btn = $callingstr;
                                }
//                        }

                }else if($status=="techsu"){
                        $statuslabel = '<span class="label label-primary">Waiting Callback</span>';
                        $statusclass = "Init TechCallBack pooltr All info";

                        // All Func
                        if($calltimes>=4){
                                if($row["unlockflag"]!="Y"){
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn disabled" id="oncall'.$row["id"].'" disabled> Call Back ( Locked )</button>';
                                }else{
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$row["id"].'"> Call Back </button>';
                                }

                                if(strpos($agent_id, '-') !== false && $row["unlockflag"]!="Y"){
                                        $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-default funcbtn" id="unlock'.$row["id"].'"> Unlock </button>';
                                }
                        }else{
                                $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$row["id"].'"> Call Back </button>';
                        }
                        if($row["callnum"]>0){
                                $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"> Called '.$row["callnum"].' times</span>';
                        }

                        if($row["callbefore"]!="0000-00-00 00:00:00"){
                                $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$row["callafter"].' ==> '.$row["callbefore"].'</span> ) ';
                        }else{
                                $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$row["callafter"].'</span> ) ';
                        }

//                      $btn .= '<button type="button" class="btn btn-mini btn-success funcbtn" id="oncall'.$eid.'"></button>';

                }else if($status=="waitcb"){
                        $statuslabel = '<span class="label label-primary">Waiting Callback</span>';
                        $statusclass = "Init CallLater pooltr All info NeedCall";

                        // How many times called


                                                // All Func
                        if($row["callnum"]>=4){
                                if($row["unlockflag"]!="Y"){
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn disabled" id="oncall'.$row["id"].'" disabled> Call Back ( Locked )</button>';
                                }else{
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$row["id"].'"> Call Back </button>';
                                }

                                if(strpos($agent_id, '-') !== false && $row["unlockflag"]!="Y"){
                                        $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-default funcbtn" id="unlock'.$row["id"].'"> Unlock </button>';
                                }
                        }else{
                                $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$row["id"].'"> Call Back </button>';
                        }

                        if($row["calnum"]>0){
                                $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"> Called '.$row["callnum"].' times</span>';
                        }

                        if($row["callbefore"]!="0000-00-00 00:00:00"){
                                $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$row["callafter"].' ==> '.$row["callbefore"].'</span> ) ';
                        }else{
                                $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$row["callafter"].'</span> ) ';
                        }


                }else if($status=="init"){
                        $statuslabel = '<span class="label label-primary">'.$status.'</span>';
                        $statusclass = "Init NeedCall pooltr All";

                        // All Func
                        if($row["callnum"]>=4){
                                if($row["unlockflag"]!="Y"){
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn disabled" id="oncall'.$row["id"].'" disabled> Call ( Locked )</button>';
                                }else{
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$row["id"].'"> Call </button>';
                                }

                                if(strpos($agent_id, '-') !== false && $row["unlockflag"]!="Y"){
                                        $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-default funcbtn" id="unlock'.$row["id"].'"> Unlock </button>';
                                }
                        }else{
                                $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$row["id"].'"> Call </button>';
                        }
                        if($row["callnum"]>0){
                                $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"> Called '.$row["callnum"].' times</span>';
                        }

                }else{
                        $statuslabel = '<span class="label label-default">'.$status.'</span>';
                        $statusclass = "Available pooltr All";
                        $btn = '<button type="button" class="btn btn-mini btn-success" id="">Invalid</button>';
                }

			//return 'Tasks for status: '.$d;
			return $btn;
		}
	),
	array(
		'db'        => 'result',
		'dt'        => 4,
		'formatter' => function( $d, $row ) {
			if($d==""){
				return "<span class=\"label label-info\"> New </span>";
			}else{
				return $d;
			}
		}
	),
	array(
		'db'        => 'note',
		'dt'        => 5,
		'formatter' => function( $d, $row ) {
			if($d==""){
				$notestr = '<button type="button" class="btn btn-xs btn-default notebtn" data-id="'.$row["id"].'" data-name="'.$row["name"].'" data-toggle="modal" data-target="#myNote" id="note'.$row["id"].'">Note</button>';
			}else{
				$notestr = '<button type="button" class="btn btn-xs btn-primary notebtn" data-id="'.$row["id"].'" data-name="'.$row["name"].'" data-toggle="modal" data-target="#myNote" id="note'.$row["id"].'">Note</button>';
			}
			return $notestr;
		}
	),
	array(
		'db'        => 'note',
		'dt'        => 6,
		'formatter' => function( $d, $row ) {
			$histbtn = '<a class="btn btn-xs btn-default" href="history.php?eid='.$row["id"].'"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Log </a>';
			return $histbtn;
		}
	)
);

// SQL server connection information
$sql_details = array(
	'user' => 'dit_agents',
	'pass' => '8nKMAyAsLfzy',
	'db'   => 'dit_agents',
	'host' => '127.0.0.1'
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);



