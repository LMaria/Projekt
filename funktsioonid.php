<?php


function connect_db(){
	global $connection;
	$host="localhost";
	$user="test";
	$pass="t3st3r123";
	$db="test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa ühendust mootoriga- ".mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - ".mysqli_error($connection));
}

function logi_sisse(){
	$errors = array();
	global $connection;
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	 if(empty($_POST['kasutaja']) || empty($_POST['parool'])){
		     $errors[]= "palun täida kõik väljad";
	     
	  
           
  }else if(isset($_POST['kasutaja']) && isset($_POST['parool'])){
		   $nimi = mysqli_real_escape_string($connection, $_POST['kasutaja']);
           $parool = mysqli_real_escape_string($connection, $_POST['parool']);
           $sql = "SELECT nimi from L__kasutajad WHERE nimi = '$nimi' AND parool =  '".sha1($parool)."'";
           $query = mysqli_query($connection, $sql);
           $rownums = mysqli_num_rows($query);
        
		      if ($rownums > 0){
			      $_SESSION['user']= $username;
			      header("Location: ?page=pealeht");
			      exit(0);
			     }else{
				     $errors[]= "vigane kasutajanimi või parool!";
           }
      	   
  }
  
}
  
 include_once("logi_sisse.html");  
  
}
  
 
  


function registreeru(){
	
	$errors = array();
	global $connection;
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(empty($_POST['nimi'])){
		     $errors[]= "palun sisesta soovitud kasutajanimi";
	     }
	   
	    if(empty($_POST['parool1'])|| empty($_POST['parool2'])){
		     $errors[]= "palun sisesta parool";
	    }
	    if (strcmp($_POST['parool1'], $_POST['parool2']) != 0){
	    $errors[]= "paroolid on erinevad";
}
	    


        if(isset($_POST['nimi'])&& isset($_POST['parool1']) && isset($_POST['parool2']) && strcmp($_POST['parool1'], $_POST['parool2']) == 0) {
	                $nimi = mysqli_real_escape_string($connection, $_POST['nimi']);
                    $parool = mysqli_real_escape_string($connection, $_POST['parool1']);
                    if(isset($_POST['mail'])){
                    $email = mysqli_real_escape_string($connection, $_POST['mail']);
                    }else{
	                $email =  mysqli_real_escape_string($connection, null);
                }
                    $sql = "INSERT into L__kasutajad(id, nimi, parool, email) VALUES (null, '$nimi', sha1('$parool'), '$email')";
                    $query = mysqli_query($connection, $sql);
                    
                   if(mysqli_insert_id($connection)> 0){
	                   header("Location: ?page=pealeht");
                       exit(0);
                   }
	         }
     }
     include_once("registreeru.html");
 }
 
      




?>

