<?php
session_start();
require_once("core.inc.php");

/* File For Database Operation */
require_once('config/config.php');
require_once('db_op.class.php');

/* Constructor Invoke database class */
$database = new db_op($host,$data_b,$db_user,$db_pwd );





/* Start database operation and login process */
if(!empty($user_profile)):
	/* 
	to get the user's facebook or google full data
	
	just 
	
	print_r($user_profile); 
	
	*/
	
	$user_profile['oauth_type'] = $_SESSION['oauth_type'];
	
			if($database->check($user_profile)>0){ //Returning User
			
				$data=$database->update($user_profile);
				$_SESSION['email'] = $data['email'];
				
		
								
			} else { //New User
				
				$data=$database->insert($user_profile);
				$_SESSION['email'] = $data['email'];
				
			}
endif;
/* End of database operation and user successfully login */

if($_SESSION['email']){
		header('Location: profile.php'); 
} else { ?>
<html>
<head>
<title>Buffer Now | Login with facbook and google</title>
</head>
<body>
<a href="<?php echo $loginUrl; ?>"><img src="images/fb_login.png"></a>
 <?php if($authUrl)	?>	
<a href="<?php echo $authUrl ?>"><img src="images/googlebtn.png"></a>
<?php } ?>
</body>
</html>