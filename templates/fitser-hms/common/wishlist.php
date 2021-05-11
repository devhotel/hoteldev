<?php 
require_once("../../../common/lib.php");
require_once("../../../common/define.php");
$response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "redirect" => "");
if(isset($db) && $db !== false && isset($_POST)){
    $get_query = $db->query("SELECT * FROM pm_wishlist WHERE hotel_id =".$_POST['hotel_id']." AND user_id=".$_POST['user_id']);
    $result = $get_query->fetch();
    if(empty($result)){
        $data = array();
        $data['hotel_id'] = $_POST['hotel_id'];
        $data['user_id'] = $_POST['user_id'];
        $data['date'] = time();
        $result = db_prepareInsert($db, 'pm_wishlist', $data);
        $result->execute();
        $response['success'] = 'Wishlist added successfully';
    }else{
        $response['error'] = 'Already Exist';
    }
    
    
    echo json_encode($response);
}
?>