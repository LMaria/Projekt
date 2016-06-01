 <?php
require_once("vaated/head.html");
require_once('funktsioonid.php');
session_start();
connect_db();


$page = "pealeht";
if (isset($_GET['page']) && $_GET['page'] != "") {
    $page = htmlspecialchars($_GET['page']);
}


switch ($page) {
    case "login":
        logi_sisse();
        break;
    case "pealeht":
        include_once("vaated/pealeht.html");
        break;
    case "registreeru":
        registreeru();
        break;
    case "autod":
        kuva_autod();
        break;
    case "bron":
        broneeri_auto();
        break;
    case "logout":
		logout();
		break;
    default:
        include_once("vaated/logi_sisse.html");
        break;
}

require_once("vaated/foot.html");

?> 