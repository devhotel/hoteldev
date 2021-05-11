<?php
class Notification{
	private $title;
	//private $message;
	private $body;
	//private $image_url;
	//private $action;
	//private $action_destination;
	private $sound;
	private $data;
	
	function __construct(){
         
	}
 
	public function setTitle($title){
		$this->title = $title;
	}
 
	/*public function setMessage($message){
		$this->message = $message;
	}*/
	
	public function setBody($body){
		$this->body = $body;
	}
 
	/*public function setImage($imageUrl){
		$this->image_url = $imageUrl;
	}

	public function setAction($action){
		$this->action = $action;
	}
 
	public function setActionDestination($actionDestination){
		$this->action_destination = $actionDestination;
	}*/
	
	public function setSound($sound){
		$this->sound = $sound;
	}
 
	public function setPayload($data){
		$this->data = $data;
	}
	
	public function getNotificatin(){
		$notification = array();
		$notification['title'] = $this->title;
		//$notification['message'] = $this->message;
		$notification['body'] = $this->body;
		//$notification['image'] = $this->image_url;
		//$notification['action'] = $this->action;
		//$notification['action_destination'] = $this->action_destination;
		$notification['sound'] = $this->sound;
		return $notification;
	}
}
?>