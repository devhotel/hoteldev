<?php
session_start();
/* File For Database Operation */
require_once('config/config.php');
require_once('db_op.class.php');
/* Constructor Invoke database class */
$database = new db_op($host,$data_b,$db_user,$db_pwd );
if($_SESSION['email']){
$data=$database->select($_SESSION['email']);
?>
<a href="index.php?logout">Logout</a>
<table border="1" align="center">
<tr><td>Name :</td><td><?php echo $data['name'];  ?></td></tr>
<tr><td>Email :</td><td><?php echo $data['email']; ?></td></tr>
<tr><td>Gender :</td><td><?php echo $data['gender']; ?></td></tr>
<?php } else { 
header('Location: user_login.php');
}
?>