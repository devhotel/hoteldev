<?php
/**
 * Script called (Ajax) on customer update
 * fills the customer fields in the booking form
 */
//session_start();
require_once('../../common/lib.php');
require_once('../../common/define.php');

$response = array();
$hotels = array();
$destination = array();
if($db !== false && isset($_GET['search_str']) && $_GET['search_str'] != ''){
     $search_str = htmlentities($_GET['search_str'], ENT_COMPAT, 'UTF-8');
        $sql="SELECT * FROM pm_hotel WHERE title LIKE :keyword AND checked = 1  AND lang = ".DEFAULT_LANG ;
        $q=$db->prepare($sql);
        //$q->bindParam(':keyword','%'.$search_str.'%');
         $q->bindValue(':keyword', '%' . $search_str . '%', PDO::PARAM_STR);
         $q->execute();
	    if($q!== false && $db->last_row_count() > 0){
    	  while ($r=$q->fetch(PDO::FETCH_ASSOC)){
             array_push($hotels, $r);
           }
	    }else{
            $sql="SELECT * FROM pm_destination WHERE title LIKE :keyword AND checked = 1  AND lang = ".DEFAULT_LANG ;
            $q=$db->prepare($sql);
            //$q->bindParam(':keyword','%'.$search_str.'%');
             $q->bindValue(':keyword', '%' . $search_str . '%', PDO::PARAM_STR);
             $q->execute();
    	    if($q!== false && $db->last_row_count() > 0){
        	  while ($r=$q->fetch(PDO::FETCH_ASSOC)){
                 array_push($destination, $r);
               }
    	    }
    }
}
//var_dump($result_hotel);
$response['hotels']=$hotels;
$response['destination']=$destination;
echo json_encode($response);           
exit();

