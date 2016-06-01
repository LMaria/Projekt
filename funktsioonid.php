 <?php

function connect_db()
{
    
    global $connection;
    $host = "localhost";
    $user = "test";
    $pass = "t3st3r123";
    $db   = "test";
    $connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa ühendust mootoriga- " . mysqli_error());
    mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - " . mysqli_error($connection));
}

function logi_sisse()
{
    
    $errors = array();
    global $connection;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($_POST['kasutaja']) || empty($_POST['parool'])) {
            $errors[] = "palun täida kõik väljad";
            
            
        } else if (isset($_POST['kasutaja']) && isset($_POST['parool'])) {
            $nimi    = mysqli_real_escape_string($connection, $_POST['kasutaja']);
            $parool  = mysqli_real_escape_string($connection, $_POST['parool']);
            $sql     = "SELECT nimi from L__kasutajad WHERE nimi = '$nimi' AND parool =  '" . sha1($parool) . "'";
            $query   = mysqli_query($connection, $sql);
            $rownums = mysqli_num_rows($query);
            
            if ($rownums > 0) {
                $_SESSION['user'] = $nimi;
                header("Location: ?page=autod");
                exit(0);
            } else {
                $errors[] = "vigane kasutajanimi või parool!";
            }
            
        }
        
    }
    
    include_once("vaated/logi_sisse.html");
    
}

function logout()
{
    $_SESSION = array();
    session_destroy();
    header("Location: ?");
}

function registreeru()
{
    
    $errors = array();
    global $connection;
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($_POST['nimi'])) {
            $errors[] = "palun sisesta soovitud kasutajanimi";
        }
        
        if (empty($_POST['parool1']) || empty($_POST['parool2'])) {
            $errors[] = "palun sisesta parool";
        }
        if (strcmp($_POST['parool1'], $_POST['parool2']) != 0) {
            $errors[] = "paroolid on erinevad";
        }
        
        
        if (empty($errors)) {
            $nimi   = mysqli_real_escape_string($connection, $_POST['nimi']);
            $parool = mysqli_real_escape_string($connection, $_POST['parool1']);
            if (isset($_POST['mail'])) {
                $email = mysqli_real_escape_string($connection, $_POST['mail']);
            } else {
                $email = mysqli_real_escape_string($connection, null);
            }
            $sql   = "INSERT into L__kasutajad(id, nimi, parool, email) VALUES (null, '$nimi', sha1('$parool'), '$email')";
            $query = mysqli_query($connection, $sql);
            
            if (mysqli_insert_id($connection) > 0) {
                header("Location: ?page=login");
                exit(0);
            }
        }
    }
    include_once("vaated/registreeru.html");
}

function kuva_autod()
{
    
    global $connection;
    $autod = array();
    
    $p = mysqli_query($connection, "select distinct(id) as id from L__autod order by id asc");
    
    while ($r = mysqli_fetch_assoc($p)) {
        $l = mysqli_query($connection, "SELECT * FROM L__autod WHERE  id=" . mysqli_real_escape_string($connection, $r['id']));
        while ($row = mysqli_fetch_assoc($l)) {
            $autod[$r['id']][] = $row;
        }
    }
    foreach ($autod as $id) {
        foreach ($id as $key => $val) {
            include 'vaated/autod.html';
            
        }
        
    }
}

function broneeri_auto()
{
    $errors = array();
    global $connection;
    
    if (!isset($_SESSION['user'])) {
        
        header("Location: ?page=login");
    } else {
        if (isset($_POST['valitudMark']) && isset($_POST['valitudMudel']) && isset($_POST['valitudPilt'])) {
            
            
            
            $id    = mysqli_real_escape_string($connection, $_POST['autoId']);
            $sql   = "SELECT algus, lõpp from L__autod WHERE id = '$id'";
            $query = mysqli_query($connection, $sql);
            $row   = $query->fetch_assoc();
            
            if ($row['algus'] != null && $row['lõpp'] != null) {
                $errors[] = "See auto on broneeritud alates " . $row['algus'] . " kuni " . $row['lõpp'];
            }
            
            
        }
        
        
        
        if (isset($_POST['valmis'])) {
            header("Location: ?page=pealeht");
        }
        
        //sama auto mitmekordsel broneerimisel kirjutatakse andmebaasis hetkel olemasolevad kuupäevad üle.
        
        if (isset($_POST['algus']) && isset($_POST['l6pp']) && isset($_POST['id'])) {
            $id    = mysqli_real_escape_string($connection, $_POST['id']);
            $algus = mysqli_real_escape_string($connection, $_POST['algus']);
            $l6pp  = mysqli_real_escape_string($connection, $_POST['l6pp']);
            $sql   = "UPDATE L__autod SET algus = '$algus', lõpp = '$l6pp' WHERE id = $id";
            $query = mysqli_query($connection, $sql);
        }
        
        include_once("vaated/broneering.html");
    }
    
}


?> 