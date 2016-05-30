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
		lisa_auto();
	break;
	case "registreeru":
		registreeru();
	break;
	case "autod":
	    kuva_autod();
	    break;
	case "bron":
	   include("broneering.html");;
	    break;    
	default:
		include_once("logi_sisse.html");
	break;
}

require_once("foot.html");

?>