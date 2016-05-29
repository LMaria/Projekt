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
			      $_SESSION['user']= $nimi;
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
 
 function kuva_autod(){
	 
	  global $connection;
	  $autod=array();
	  $p= mysqli_query($connection, "select distinct(id) as id from L__autod order by id asc");
	
	  while ($r=mysqli_fetch_assoc($p)){
		$l=mysqli_query($connection, "SELECT * FROM L__autod WHERE  id=".mysqli_real_escape_string($connection, $r['id']));
		while ($row=mysqli_fetch_assoc($l)) {
			$autod[$r['id']][]=$row;
		}
	}
	foreach ($autod as $id){
          foreach($id as $key => $val){
           include 'autod.html';
           
        }
	
	
    }
  }
	  /*
	  $sql = "SELECT * FROM L__autod";
	  $query =mysqli_query($connection, $sql);
	
	  $result = mysql_fetch_assoc($query, MYSQL_NUM);
	  while($result = $query->fetch_assoc()){
      $autod[] = $result;
    }
    
    */
  
	
 
 
 function lisa_auto(){
	 global $connection;
	 if(!isset($_SESSION['user'])){
		 $errors[]= "Auto lisamiseks logi sisse või registreeru";
	 
 }else{
	 if(!empty($_POST)){
	 if(empty($_POST['mark'])|| empty($_POST['mudel'])|| empty($_POST['aasta'])|| 
        empty($_POST['k2igukast'])|| empty($_POST['hind'])|| empty($_POST['tel'])||  upload("pilt")== ""){
		              $errors[]= "palun täida kõik tärniga märgitud väljad!";
	             }else{
		            if(isset($_POST['mark']))
		            $mark = mysqli_real_escape_string($connection, $_POST['mark']);
		            if(isset($_POST['mudel']))
                    $mudel = mysqli_real_escape_string($connection, $_POST['mudel']);
                    if(isset($_POST['aasta']))
                    $aasta = mysqli_real_escape_string($connection, $_POST['aasta']);
                    if(isset($_POST['keretyyp']))
                    $kere = mysqli_real_escape_string($connection, $_POST['keretyyp']);
                    if(isset($_POST['kW'])&& is_numeric($_POST['kW'])){
                    $kW = mysqli_real_escape_string($connection, $_POST['kW']);
                    }elseif(isset($_POST['kW'])&& !is_numeric($_POST['kW'])){
	                    $errors[] = "palun sisesta numbriline väärtus";
                   }
                    if(isset($_POST['v2rv']))
                    $v2rv = mysqli_real_escape_string($connection, $_POST['v2rv']);
                    if(isset($_POST['k2igukast']))
                    $k2igukast = mysqli_real_escape_string($connection, $_POST['k2igukast']);
                    if(isset($_POST['kytus']))
                    $kytus = mysqli_real_escape_string($connection, $_POST['kytus']);
                    if(isset($_POST['l2bisõit']))
                    $km = mysqli_real_escape_string($connection, $_POST['l2bisõit']);
                    if(isset($_POST['asukoht']))
                    $asukoht = mysqli_real_escape_string($connection, $_POST['asukoht']);
                    if(isset($_POST['hind'])&& !is_numeric($_POST['hind'])){
	                    $errors[]= "palun sisesta numbriline väärtus";
                   }else{
	                   $hind= mysqli_real_escape_string($connection, $_POST['hind']);
                   }
                    if(isset($_POST['tel'])&& !is_numeric($_POST['tel'])){
	                    $errors[]= "palun sisesta numbriline väärtus";
                   }else{
	                   $tel= mysqli_real_escape_string($connection, $_POST['tel']);
                   }
                      
                      $img = upload("pilt");
                      $pilt = mysqli_real_escape_string($connection, $img);
                    
                  
                    $sql = "INSERT into L__autod(id, mark, mudel, aasta, kere, kW, värv, käigukast, kütus, läbisõit, hind, tel, pilt) 
                    VALUES (null, '$mark', '$mudel', '$aasta', '$kere', '$kW', '$v2rv', '$k2igukast', '$kytus', '$km', '$asukoht',
                    '$hind', '$tel', '$pilt')";
                    $query = mysqli_query($connection, $sql);
                    
                   if(mysqli_insert_id($connection)> 0){
	                   header("Location: ?page=autod");
                       exit(0);
                   }
 }
}
 
}
  include_once('lisa.html');
}

function upload($name){
	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$allowedTypes = array("image/gif", "image/jpeg", "image/png","image/pjpeg");
	$tmp = explode(".", $_FILES[$name]["name"]);
	$extension = end($tmp);
	$target_dir = "pildid/";

	if ( in_array($_FILES[$name]["type"], $allowedTypes)
		&& ($_FILES[$name]["size"] < 100000)
		&& in_array($extension,$allowedExts)){
		
	
    // fail õiget tüüpi ja suurusega
		if ($_FILES[$name]["error"] > 0) {
			$_SESSION['notices'][]= "Return Code: " . $_FILES[$name]["error"];
			return "";
		} else {
      // vigu ei ole
			if (file_exists("pildid/" . $_FILES[$name]["name"])) {
        // fail olemas ära uuesti lae, tagasta failinimi
				$_SESSION['notices'][]= $_FILES[$name]["name"] . " juba eksisteerib. ";
				return "pildid/" .$_FILES[$name]["name"];
			} else {
        // kõik ok, aseta pilt
				//move_uploaded_file($_FILES[$name]["tmp_name"], "pildid/" . $_FILES[$name]["name"]);
				//$upload_path = "pildid/" . $_FILES[$name]["name"];
				move_uploaded_file($_FILES[$name]["tmp_name"],"/home/lklaving/public_html/projekt/". $_FILES[$name]["name"]) ;
				return "/home/lklaving/public_html/projekt/". $_FILES[$name]["name"];
			}
		}
	} else {
		return "";
	}
}
      




?>

