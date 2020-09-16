<?php 
class db_op {

      
    function __construct($dbhost,$database,$dbUser ,$dbPass ) {
	   global $dbh;
 	   		try { // attempt to create a connection to database
					$dbh = new PDO("mysql:host=".$dbhost.";dbname=".$database,$dbUser,$dbPass);
					
			}  catch(PDOException $e) { // if it fails, we echo the error and die.
							echo $e->getMessage();
			  die();
			}
	 	}
				
		
	public function check($inputarray){ 
	global $dbh;
	$select = $dbh->prepare("select * from users where email='".$inputarray['email']."'");
    $select->execute();
	return $select->rowCount();
	}
	
	
	public function insert($inputarray){ 
	global $dbh;
	$insert = $dbh->prepare("INSERT INTO `users`(`name`, `email`, `oauth_type`, `gender`) 
	VALUES (
	'".$inputarray['name']."','".$inputarray['email']."','".$inputarray['oauth_type']."','".$inputarray['gender']."'
	)");
	$insert->execute();
	$select = $dbh->prepare("select * from users where email='".$inputarray['email']."'");
   	$select->execute();
	return $select->fetch();
	}
	
	public function update($inputarray){ 
	global $dbh;
	$update = $dbh->prepare("UPDATE `users` SET 
	`name`='".$inputarray['name']."',
	`email`='".$inputarray['email']."',
	`oauth_type`='".$inputarray['oauth_type']."',
	`gender`='".$inputarray['gender']."' 
	WHERE email='".$inputarray['email']."'");
	$update->execute();
	$select = $dbh->prepare("select * from users where email='".$inputarray['email']."'");
   	$select->execute();
	return $select->fetch();
	}
	
	
	public function select($email)
	{
	global $dbh;
	$select = $dbh->prepare("select * from users where email='".$email."'");
   	$select->execute();
	return $select->fetch();
	}
	
	
	
		

}