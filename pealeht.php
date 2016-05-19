<?php
require_once("head.html");
require_once('funktsioonid.php');
session_start();
connect_db();


$page="pealeht";
if (isset($_GET['page']) && $_GET['page']!=""){
	$page=htmlspecialchars($_GET['page']);
}



switch($page){
	case "login":
		logi_sisse();
	break;
	case "lisa":
		include("lisa.html");
	break;
	case "registreeru":
		registreeru();
	break;
	
	default:
		include_once("logi_sisse.html");
	break;
}

require_once("foot.html");

?>